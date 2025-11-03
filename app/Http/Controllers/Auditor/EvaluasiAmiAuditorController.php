<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\StandarCapaian;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\TransaksiAmi;
use App\Models\User;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class EvaluasiAmiAuditorController extends Controller
{
    public function index()
    {
        $data_kesiapan = TransaksiAmi::whereHas('auditorAmi', function($query) {
            $query->where('users_kode', session('user_kode'));
        })
        ->where('status', 'Diterima')
        ->latest()
        ->get();

        return view('pages.auditor.evaluasi-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }


    public function auditAmi(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akreditasi_kode  =$transaksi_ami->standar_akreditasi;       
        $jenjang_raw      = $prodi;       

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

        $standardsQuery = Standard::query()
            ->with(['elements.indicators.dokumen_nilais', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id);

        $standards = $standardsQuery->get();

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

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });
        
        return view('pages.auditor.evaluasi-ami.audit-ami.index', [
            'akreditasi' => $akreditasi,
            'jenjang'    => $jenjang,
            'standards'  => $standards,
            'periode' => $periode,
            'prodi' => $jenjang_raw,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Incoming Data:', $request->all());

        $validatedData = $request->validate([
            'ami_kodes' => 'required|string',
            'indikator_ids' => 'required|string',
            'indikator_bobots' => 'nullable|string',
            'mandiri_nilais' => 'required|numeric|min:0|max:4',
            'hasil_nilais' => 'required|numeric|min:0|max:4',
            'hasil_kriterias' => 'nullable|string',
            'hasil_deskripsis' => 'nullable|string',
            'jenis_temuans' => 'required|string',
            'hasil_akibats' => 'nullable|string',
            'hasil_masalahs' => 'nullable|string',
            'hasil_rekomendasis' => 'nullable|string',
            'prodis' => 'nullable|string',
            'periodes' => 'nullable|string',
        ]);

        try {
            $standard = StandarNilai::where('indikator_id', $validatedData['indikator_ids'])
                ->where('ami_kode', $validatedData['ami_kodes'])
                ->first();

            if ($standard) {
                $standard->mandiri_nilai = $validatedData['mandiri_nilais'];
                $standard->hasil_nilai = $validatedData['hasil_nilais'];
                $standard->hasil_kriteria = $validatedData['hasil_kriterias'];
                $standard->hasil_deskripsi = $validatedData['hasil_deskripsis'];
                $standard->jenis_temuan = $validatedData['jenis_temuans'];
                $standard->hasil_akibat = $validatedData['hasil_akibats'];
                $standard->hasil_masalah = $validatedData['hasil_masalahs'];
                $standard->hasil_rekomendasi = $validatedData['hasil_rekomendasis'];

                if ($standard->save()) {
                    Log::info('Data updated successfully:', $standard->toArray());
                    return redirect()->back()->with('success', 'Data berhasil diperbarui!');
                } else {
                    Log::error('Failed to update data.');
                    return redirect()->back()->with('error', 'Failed to update data.');
                }
            } else {
                $standard = new StandarNilai();
                $standard->ami_kode = $validatedData['ami_kodes'];
                $standard->indikator_id = $validatedData['indikator_ids'];
                $standard->mandiri_nilai = $validatedData['mandiri_nilais'];
                $standard->hasil_nilai = $validatedData['hasil_nilais'];
                $standard->bobot = $validatedData['indikator_bobots'];
                $standard->hasil_kriteria = $validatedData['hasil_kriterias'];
                $standard->hasil_deskripsi = $validatedData['hasil_deskripsis'];
                $standard->jenis_temuan = $validatedData['jenis_temuans'];
                $standard->hasil_akibat = $validatedData['hasil_akibats'];
                $standard->hasil_masalah = $validatedData['hasil_masalahs'];
                $standard->hasil_rekomendasi = $validatedData['hasil_rekomendasis'];
                $standard->prodi = $validatedData['prodis'];
                $standard->periode = $validatedData['periodes'];

                if ($standard->save()) {
                    Log::info('Data saved successfully:', $standard->toArray());
                    return redirect()->back()->with('success', 'Data berhasil disimpan!');
                } else {
                    Log::error('Failed to save data.');
                    return redirect()->back()->with('error', 'Failed to save data.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error saving or updating data:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $pengajuan = TransaksiAmi::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return redirect()->route('auditor.evaluasi-ami.index')->with('success', 'Pengajuan updated successfully.');
    }

}
