<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\StandarCapaian;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarElemenBanptD3;
use App\Models\StandarElemenLamdikS1;
use App\Models\TransaksiAmi;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class KoreksiAmiAuditorController extends Controller
{
    public function index()
    {
        $data_kesiapan = TransaksiAmi::whereHas('auditorAmi', function($query) {
            $query->where('users_kode', session('user_kode'));
        })
        ->where('status', 'Koreksi')
        ->latest()
        ->get();

        return view('pages.auditor.koreksi-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }

    
    public function revisiAmi(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+|D[0-9]+)\b/', $prodi, $matches);
        $degree = $matches[0] ?? 'S1'; 

        $key = trim($akses . ' ' . $degree);

        $standar_names_banpt = [
            'Kondisi Eksternal',
            'Profil Unit Pengelola Program Studi',
            '1. Visi, Misi, Tujuan dan Strategi',
            '2. Tata Pamong dan Kerjasama',
            '3. Mahasiswa',
            '4. Sumber Daya Manusia',
            '5. Keuangan, Sarana dan Prasarana',
            '6. Pendidikan',
            '7. Penelitian',
            '8. Pengabdian Kepada Masyarakat',
            '9. Luaran dan Capaian Tridharma',
            'Analisis dan Penetapan Program Pengembangan'
        ];

        $standar_names_lamdik = [
            'Visi Keilmuan',
            'Tata Kelola',
            'Mahasiswa',
            'Dosen dan Tenaga Kependidikan',
            'Keuangan, Sarana dan Prasarana Pendidikan',
            'Pendidikan',
            'Pengabdian Kepada Masyarakat',
            'Penjaminan Mutu',
        ];

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
                'standarTargetsRelation' => 'standarTargetsD3',
                'standarCapaiansRelation' => 'standarCapaiansD3',
                'standarNilaisRelation' => 'standarNilaisD3',
                'standarNames' => $standar_names_banpt,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
                'standarTargetsRelation' => 'standarTargetsBanptS1',
                'standarCapaiansRelation' => 'standarCapaiansBanptS1',
                'standarNilaisRelation' => 'standarNilaisBanptS1',
                'standarNames' => $standar_names_banpt,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS1',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS1',
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
                'standarNames' => $standar_names_lamdik,
            ],
        ];

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }
        $degreeInfo = $degreeMappings[$key] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarTargetsRelation = $degreeInfo['standarTargetsRelation'];
        $standarCapaiansRelation = $degreeInfo['standarCapaiansRelation'];
        $standarNilaisRelation = $degreeInfo['standarNilaisRelation'];
        $standarNames = $degreeInfo['standarNames'];

        $data_standar = [];
        $degree = trim($degree);

        foreach ($standarNames as $index => $name) {
            $data_standar['data_standar_k' . ($index + 1)] = $modelClass::with([
                $standarTargetsRelation => function ($query) use ($key) {
                    $query->where('jenjang', $key);
                },
                $standarCapaiansRelation => function ($query) use ($prodi) {
                    $query->where('prodi', $prodi);
                },
                $standarNilaisRelation => function ($query) use ($periode, $prodi) {
                    $query->where('periode', $periode)
                            ->where('prodi', $prodi);
                },
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('elemen_nama', 'like', '%' . $request->q . '%');
            })
            ->where('standar_nama', $name)
            ->whereDoesntHave($standarNilaisRelation, function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode)
                        ->where('prodi', $prodi)
                        ->where('jenis_temuan', 'Sesuai');
            })
            ->latest()
            ->paginate(30)
            ->appends(['q' => $request->q]);
        }
        
        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->when($request->q, function($query) use ($request) {
                $query->whereHas('auditor_ami.user', function($q) use ($request) {
                    $q->where('user_nama', 'like', '%' . $request->q . '%');
                })
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%');
            })
            ->latest()
            ->get(); 
    
        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')  // Eager load the auditorAmi relationship
            ->first();

        return view('pages.auditor.koreksi-ami.revisi-ami.index', [
            'nama_data_standar' => $standarNames,
            'data_standar' => $data_standar,
            'standarTargetsRelation' => $standarTargetsRelation,
            'standarCapaiansRelation' => $standarCapaiansRelation,
            'standarNilaisRelation' => $standarNilaisRelation,
            'periode' => $request->periode,
            'prodi' => $prodi,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
            'key' => $key,

        ]);
    }

    public function store(Request $request)
    {
        Log::info('Incoming Data:', $request->all());

        $validatedData = $request->validate([
            'ami_kodes' => 'required|string|max:255',
            'indikator_kodes' => 'required|string|max:255',
            'indikator_bobots' => 'required',
            'status_akhirs' => 'required|string|max:255',
            'prodis' => 'required|string|max:255',
            'periodes' => 'required|string|max:255',
            'hasil_nilais' => 'required|numeric|between:0,4',
        ]);

        try {
            $amiInput = StandarNilai::where('indikator_kode', $validatedData['indikator_kodes'])
                ->where('periode', $validatedData['periodes'])
                ->where('prodi', $validatedData['prodis'])
                ->first();

            if ($amiInput) {
                $amiInput->hasil_nilai = $validatedData['hasil_nilais'];
                $amiInput->status_akhir = $validatedData['status_akhirs'];

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
                $amiInput->indikator_kode = $validatedData['indikator_kodes'];
                $amiInput->bobot = $validatedData['indikator_bobots'];
                $amiInput->status_akhir = $validatedData['status_akhirs'];
                $amiInput->prodi = $validatedData['prodis'];
                $amiInput->periode = $validatedData['periodes'];
                $amiInput->hasil_nilai = $validatedData['hasil_nilais'];

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

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|exists:transaksi_amis,id', // Ensure the ID exists
            'status' => 'required|string',
        ]);

        $pengajuan = TransaksiAmi::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return redirect()->route('auditor.koreksi-ami.index')->with('success', 'Siklus AMI sudah selesai dan hasilnya dapat dilihat.');
    }

}
