<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\StandarCapaian;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\TransaksiAmi;
use App\Models\User;
use App\Models\Standard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PengajuanAmiUserController extends Controller
{
    public function index()
    {
        $prodi = session('user_penempatan'); 

        $data_kesiapan = StandarCapaian::with('standarCapaiansBanptS1')
            ->select('periode', 'prodi')
            ->where('prodi', $prodi)
            ->groupBy('periode', 'prodi')
            ->latest()
            ->paginate(10);

        return view('pages.user.pengajuan-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }

    public function inputAmi(Request $request, $periode)
    {

        $akreditasi_kode  = session('user_akses');         
        $jenjang_raw      = session('user_penempatan');       

        $jenjang_nama = trim(explode(' - ', (string)$jenjang_raw, 2)[0]);
        if ($jenjang_nama === '') $jenjang_nama = 'S1';

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang    = Jenjang::pluck('nama')->toArray();

        if (!in_array($akreditasi_kode, $validAkreditasi, true)) {
            Log::warning('Nilai akreditasi sesi tidak valid, fallback ke BAN-PT', ['session' => $akreditasi_kode]);
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, $validJenjang, true)) {
            Log::warning('Nilai jenjang sesi tidak valid, fallback ke S1', ['session' => $jenjang_nama]);
            $jenjang_nama = 'S1';
        }

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

        $standards = Standard::with([
            'elements.indicators.dokumen_nilais' => function ($query) use ($periode, $jenjang_raw) {
                $query->where('periode', $periode)
                    ->where('prodi', $jenjang_raw);
            },
            'elements.indicators.dokumen_targets',
            'elements.indicators.dokumen_capaians',
            'elements.indicators',
            'elements.standard',
            'buktiStandar'
        ])
        ->where('standar_akreditasi_id', $akreditasi->id)
        ->where('jenjang_id', $jenjang->id)
        ->get();


        // dd($standards);

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->when($request->q, function ($query) use ($request) {
                $query->whereHas('auditor_ami.user', function ($q) use ($request) {
                    $q->where('user_nama', 'like', '%' . $request->q . '%');
                })
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%');
            })
            ->where('prodi', $jenjang_raw)
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $jenjang_raw)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });
            // dd($standards);
        if ($transaksi_ami) {
            return view('pages.user.pengajuan-ami.input-ami.index', [
                'akreditasi' => $akreditasi,
                'jenjang'    => $jenjang,
                'standards'  => $standards,
                'periode' => $periode,
                'prodi' => $jenjang_raw,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors,
            ]);
        } else {
            return view('pages.user.pengajuan-ami.input-ami.empty', [
                'periode' => $periode,
                'prodi' => $jenjang_raw,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors,
            ]);
        }
    }

    public function inputAmiStore(Request $request)
    {
        Log::info('Incoming Data:', $request->all());

        $validatedData = $request->validate([
            'ami_kodes' => 'required|string|max:255',
            'indikator_ids' => 'required|string|max:255',
            'indikator_bobots' => 'required',
            'prodis' => 'required|string|max:255',
            'periodes' => 'required|string|max:255',
            'nilai_mandiris' => 'required|numeric|between:0,4',
        ]);

        try {
            $amiInput = StandarNilai::where('indikator_id', $validatedData['indikator_ids'])
                ->where('periode', $validatedData['periodes'])
                ->where('prodi', $validatedData['prodis'])
                ->first();

            if ($amiInput) {
                $amiInput->bobot = $validatedData['indikator_bobots'];
                $amiInput->mandiri_nilai = $validatedData['nilai_mandiris'];

                if ($amiInput->save()) {
                    Log::info('Data updated successfully:', $amiInput->toArray());
                    return redirect()->back()->with('success', 'Data successfully updated!');
                } else {
                    Log::error('Failed to update data.');
                    return redirect()->back()->with('error', 'Failed to update data.');
                }
            } else {
                $amiInput = new StandarNilai();
                $amiInput->ami_kode = $validatedData['ami_kodes'];
                $amiInput->indikator_id = $validatedData['indikator_ids'];
                $amiInput->bobot = $validatedData['indikator_bobots'];
                $amiInput->prodi = $validatedData['prodis'];
                $amiInput->periode = $validatedData['periodes'];
                $amiInput->mandiri_nilai = $validatedData['nilai_mandiris'];

                if ($amiInput->save()) {
                    Log::info('Data saved successfully:', $amiInput->toArray());
                    return redirect()->back()->with('success', 'Data successfully saved!');
                } else {
                    Log::error('Failed to save data.');
                    return redirect()->back()->with('error', 'Failed to save data.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error saving or updating data:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function inputAmiUpdate(Request $request)
    {
        $transaksi_ami = TransaksiAmi::find($request->id); 

        $transaksi_ami->status = $request->status;

        $transaksi_ami->save();

        return redirect()->back()->with('success', 'Status has been updated to "Diajukan".');
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
    
        $prodi = session('user_penempatan');
        $fakultas = session('user_fakultas');
        $standar_akreditasi = session('user_akses');
    
        TransaksiAmi::create([
            'ami_kode' => 'ami-' . Str::uuid() . uniqid(),
            'auditor_kode' => $request->auditor_kode,
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'standar_akreditasi' => $standar_akreditasi,
            'periode' => $request->periode,
            'status' => $request->status,
        ]);
    
        return redirect()->back()->with('success', 'Data Pengajuan AMI successfully submitted.');
    }

}
