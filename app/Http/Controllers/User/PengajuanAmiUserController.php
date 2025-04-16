<?php

namespace App\Http\Controllers\User;

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
        $penempatan = session('user_penempatan'); 
        $akses = session('user_akses'); 

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $penempatan, $matches);
        $degree = $matches[0] ?? 'PPG';

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
                $standarCapaiansRelation => function ($query) use ($penempatan) {
                    $query->where('prodi', $penempatan);
                },
                $standarNilaisRelation => function ($query) use ($penempatan) {
                    $query->where('prodi', $penempatan);
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
            ->when($request->q, function ($query) use ($request) {
                $query->whereHas('auditor_ami.user', function ($q) use ($request) {
                    $q->where('user_nama', 'like', '%' . $request->q . '%');
                })
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%');
            })
            ->where('prodi', $penempatan)
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $penempatan)
            ->with('auditorAmi.user')
            ->first();

        if ($transaksi_ami) {
            return view('pages.user.pengajuan-ami.input-ami.index', [
                'nama_data_standar' => $standarNames,
                'data_standar' => $data_standar,
                'standarTargetsRelation' => $standarTargetsRelation,
                'standarCapaiansRelation' => $standarCapaiansRelation,
                'standarNilaisRelation' => $standarNilaisRelation,
                'periode' => $request->periode,
                'prodi' => $penempatan,
                'key' => $key,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors,
            ]);
        } else {
            return view('pages.user.pengajuan-ami.input-ami.empty', [
                'periode' => $request->periode,
                'prodi' => $penempatan,
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
            'indikator_kodes' => 'required|string|max:255',
            'indikator_bobots' => 'required',
            'prodis' => 'required|string|max:255',
            'periodes' => 'required|string|max:255',
            'nilai_mandiris' => 'required|numeric|between:0,4',
        ]);

        try {
            $amiInput = StandarNilai::where('indikator_kode', $validatedData['indikator_kodes'])
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
                $amiInput->indikator_kode = $validatedData['indikator_kodes'];
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
