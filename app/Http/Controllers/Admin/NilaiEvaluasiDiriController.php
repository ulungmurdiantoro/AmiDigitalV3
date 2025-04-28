<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Carbon\Carbon;
class NilaiEvaluasiDiriController extends Controller
{
    public function index()
    {
        $transaksi_ami = TransaksiAmi::with('auditorAmi')
            ->where('status', 'Selesai')
            ->latest()
            ->paginate(10);

        foreach ($transaksi_ami as $item) {
            Carbon::setLocale('id');
            $item->formatted_created_at = Carbon::parse($item->created_at)->isoFormat('D MMMM Y');
        }

        return view('pages.admin.nilai-evaluasi-diri.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    
    public function rekapNilai(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $prodi, $matches);
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
            ->latest()
            ->get();
    
        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user') 
            ->first();

            return view('pages.admin.nilai-evaluasi-diri.rekap-nilai.index', [
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

    public function reportLha(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $prodi, $matches);
        $degree = $matches[0] ?? 'PPG';

        $accreditationKey = trim("{$akses} {$degree}"); 
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
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarTargetsRelation' => 'standarTargetsLamdikD3',
                'standarCapaiansRelation' => 'standarCapaiansLamdikD3',
                'standarNilaisRelation' => 'standarNilaisLamdikD3',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS1',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS1',
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
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
            ->get();
        }        

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with(['auditorAmi.user', 'penempatanUser']) 
            ->first();

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        $totalData = $this->calculateTotal($periode, $prodi, $accreditationKey);
        $total = $totalData['total'];

        Carbon::setLocale('id');
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $tanggal_audit = $transaksi_ami->updated_at->isoFormat('dddd, D MMMM Y');

        $html = view('pages.report.report-lha', [
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
            'tanggal' => $tanggal, 
            'tanggal_audit' => $tanggal_audit, 
            'total' => $total,
            'prodiPrefix' => $prodiPrefix,
        ])->render();

        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

        $mpdf = new Mpdf();

        $mpdf->WriteHTML($html);

        $mpdf->Output('rekap_nilai.pdf', 'I');
    }

    public function calculateTotal($periode, $prodi, $accreditationKey)
    {
        $compositeIndicatorsConfig = [
            'BAN-PT D3' => [
                
            ],
            'BAN-PT S1' => [
                'S1-6' => [
                    'components' => [
                        ['kode' => 'S1-6A', 'weight' => 1],
                        ['kode' => 'S1-6B', 'weight' => 2],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 0.34,
                ],
                'S1-7' => [
                    'components' => [
                        ['kode' => 'S1-7A', 'weight' => 1],
                        ['kode' => 'S1-7B', 'weight' => 2],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 0.34,
                ],
                'S1-9' => [
                    'components' => [
                        ['kode' => 'S1-9A', 'weight' => 2],
                        ['kode' => 'S1-9B', 'weight' => 1],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 0.34,
                ],
                'S1-15' => [
                    'components' => [
                        ['kode' => 'S1-15A', 'weight' => 2],
                        ['kode' => 'S1-15B', 'weight' => 1],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 3.07,
                ],
                'S1-16' => [
                    'components' => [
                        ['kode' => 'S1-16A', 'weight' => 1],
                        ['kode' => 'S1-16B', 'weight' => 2],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 1.53,
                ],
                'S1-31' => [
                    'components' => [
                        ['kode' => 'S1-31A', 'weight' => 1],
                        ['kode' => 'S1-31B', 'weight' => 1],
                    ],
                    'divisor'    => 2,
                    'multiplier' => 1.12,
                ],
                'S1-38' => [
                    'components' => [
                        ['kode' => 'S1-38A', 'weight' => 1],
                        ['kode' => 'S1-38B', 'weight' => 2],
                        ['kode' => 'S1-38C', 'weight' => 2],
                    ],
                    'divisor'    => 5,
                    'multiplier' => 2.51,
                ],
                'S1-40' => [
                    'components' => [
                        ['kode' => 'S1-40A', 'weight' => 1],
                        ['kode' => 'S1-40B', 'weight' => 2],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 1.67,
                ],
                'S1-41' => [
                    'components' => [
                        ['kode' => 'S1-41A', 'weight' => 1],
                        ['kode' => 'S1-41B', 'weight' => 2],
                        ['kode' => 'S1-41C', 'weight' => 2],
                        ['kode' => 'S1-41D', 'weight' => 2],
                        ['kode' => 'S1-41E', 'weight' => 2],
                    ],
                    'divisor'    => 9,
                    'multiplier' => 1.12,
                ],
                'S1-44' => [
                    'components' => [
                        ['kode' => 'S1-44A', 'weight' => 1],
                        ['kode' => 'S1-44B', 'weight' => 2],
                        ['kode' => 'S1-44C', 'weight' => 2],
                    ],
                    'divisor'    => 5,
                    'multiplier' => 1.67,
                ],
                'S1-47' => [
                    'components' => [
                        ['kode' => 'S1-47A', 'weight' => 1],
                        ['kode' => 'S1-47B', 'weight' => 2],
                    ],
                    'divisor'    => 3,
                    'multiplier' => 3.35,
                ],
            ],
            'LAMDIK S1' => [
                
            ],
            'LAMDIK PPG' => [

            ],
            'LAMDIK S2' => [
                
            ],
        ];

        $nilaiCollection = StandarNilai::where('periode', $periode)
            ->where('prodi', $prodi)
            ->get()
            ->keyBy('indikator_kode');

        $compositeTotal    = 0;
        $nonCompositeTotal = 0;

        $prodiParts  = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        if ($accreditationKey === 'BAN-PT S1') {
            $indicatorPrefix = 'S1';
            $indicatorRange  = 69;
        } elseif ($accreditationKey === 'BAN-PT D3') {
            $indicatorPrefix = 'D3';
            $indicatorRange  = 67;
        } elseif ($accreditationKey === 'LAMDIK PPG') {
            $indicatorPrefix = 'PPG';
            $indicatorRange  = 60;
        } elseif ($accreditationKey === 'LAMDIK S1') {
            $indicatorPrefix = 'S1';
            $indicatorRange  = 64;
        } elseif ($accreditationKey === 'LAMDIK S2') {
            $indicatorPrefix = 'S2';
            $indicatorRange  = 60;
        } else {
            $indicatorPrefix = 'Unknown';
            $indicatorRange  = 0;
        }

        $compositeConfigKey = $accreditationKey;
        $compositeIndicators = isset($compositeIndicatorsConfig[$compositeConfigKey])
            ? array_keys($compositeIndicatorsConfig[$compositeConfigKey])
            : [];

        $allIndicators = [];
        for ($i = 1; $i <= $indicatorRange; $i++) {
            $allIndicators[] = $indicatorPrefix . '-' . $i;
        }

        foreach ($compositeIndicators as $indicator) {
            $config = $compositeIndicatorsConfig[$compositeConfigKey][$indicator];

            $totalComponent = 0;
            foreach ($config['components'] as $component) {
                $kode   = $component['kode'];
                $weight = $component['weight'];

                if ($nilaiCollection->has($kode)) {
                    $nilai = $nilaiCollection[$kode]->hasil_nilai;
                    $totalComponent += $nilai * $weight;
                }
            }
            $compositeScore = ($totalComponent / $config['divisor']) * $config['multiplier'];
            $compositeTotal += $compositeScore;
        }

        foreach ($allIndicators as $indicator) {
            if (!in_array($indicator, $compositeIndicators) && $nilaiCollection->has($indicator)) {
                $nilai = $nilaiCollection[$indicator];
                $bobot = $nilai->bobot ?? 1;
                $nonCompositeTotal += $nilai->hasil_nilai * $bobot;
            }
        }

        $total = $compositeTotal + $nonCompositeTotal;
        return [
            'total'       => $total,
            'prodiPrefix' => $prodiPrefix,
        ];
    }

    public function reportRtm(Request $request, $periode, $prodi)
    {
        list($startYear, $endYear) = explode('/', $periode);
        $previousStartYear = $startYear - 1;
        $previousEndYear = $endYear - 1;
        $previousPeriode = "{$previousStartYear}/{$previousEndYear}";

        $nextStartYear = $startYear + 1;
        $nextEndYear = $endYear + 1;
        $nextPeriode = "{$nextStartYear}/{$nextEndYear}";

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $prodi, $matches);
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
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarTargetsRelation' => 'standarTargetsLamdikD3',
                'standarCapaiansRelation' => 'standarCapaiansLamdikD3',
                'standarNilaisRelation' => 'standarNilaisLamdikD3',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS1',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS1',
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
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
            ->get();
            
            $previous_data_standar['data_standar_k' . ($index + 1)] = $modelClass::with([
                $standarTargetsRelation => function ($query) use ($key) {
                    $query->where('jenjang', $key);
                },
                $standarCapaiansRelation => function ($query) use ($prodi) {
                    $query->where('prodi', $prodi);
                },
                $standarNilaisRelation => function ($query) use ($previousPeriode, $prodi) {
                    $query->where('periode', $previousPeriode)->where('prodi', $prodi);
                },
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('elemen_nama', 'like', '%' . $request->q . '%');
            })
            ->where('standar_nama', $name)
            ->latest()
            ->get();
        }

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with(['auditorAmi.user', 'penempatanUser'])
            ->first();

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        $total = StandarNilai::calculateTotal($periode, $prodi);

        Carbon::setLocale('id');
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $tanggal_audit = $transaksi_ami->updated_at->isoFormat('dddd, D MMMM Y');

        $html = view('pages.report.report-rtm', [
            'nama_data_standar' => $standarNames,
            'data_standar' => $data_standar,
            'standarTargetsRelation' => $standarTargetsRelation,
            'standarCapaiansRelation' => $standarCapaiansRelation,
            'standarNilaisRelation' => $standarNilaisRelation,
            'previous_data_standar' => $previous_data_standar,
            'periode' => $request->periode,
            'previousPeriode' => $previousPeriode,
            'nextPeriode' => $nextPeriode,
            'prodi' => $prodi,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
            'tanggal' => $tanggal,
            'tanggal_audit' => $tanggal_audit,
            'total' => $total,
            'prodiPrefix' => $prodiPrefix,
        ])->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('rekap_nilai.pdf', 'I');
    }

}
