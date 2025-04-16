<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\StandarCapaian;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarElemenBanptD3;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarElemenBanptS2;
use App\Models\StandarElemenBanptS3;
use App\Models\StandarElemenBanptTerapanS1;
use App\Models\StandarElemenBanptTerapanS2;
use App\Models\StandarElemenBanptTerapanS3;
use App\Models\StandarElemenLamdikD3;
use App\Models\StandarElemenLamdikS1;
use App\Models\StandarElemenLamdikS2;
use App\Models\StandarElemenLamdikS3;
use App\Models\StandarElemenLamdikTerapanS1;
use App\Models\StandarElemenLamdikTerapanS2;
use App\Models\StandarElemenLamdikTerapanS3;
use App\Models\TransaksiAmi;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $prodi, $matches);
        $degree = $matches[0] ?? 'PPG';

        $key = trim($akses . ' ' . $degree);
// dd($key);
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
            'Tata Pamong dan Tata Kelola',
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
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarTargetsRelation' => 'standarTargetsLamdikD3',
                'standarCapaiansRelation' => 'standarCapaiansLamdikD3',
                'standarNilaisRelation' => 'standarNilaisLamdikD3',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS2',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS2',
                'standarNilaisRelation' => 'standarNilaisLamdikS2',
                'standarNames' => $standar_names_lamdik,
            ],
        ];

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }
        $degreeInfo = $degreeMappings[$key];

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
                    $query->where('periode', $periode)->where('prodi', $prodi);
                },
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('elemen_nama', 'like', '%' . $request->q . '%');
            })
            ->where('standar_nama', $name)
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

        return view('pages.auditor.evaluasi-ami.audit-ami.index', [
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
            'ami_kodes' => 'required|string',
            'indikator_kodes' => 'required|string',
            'indikator_bobots' => 'nullable|string',
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
            $standard = StandarNilai::where('indikator_kode', $validatedData['indikator_kodes'])
                ->where('ami_kode', $validatedData['ami_kodes'])
                ->first();

            if ($standard) {
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
                $standard->indikator_kode = $validatedData['indikator_kodes'];
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
