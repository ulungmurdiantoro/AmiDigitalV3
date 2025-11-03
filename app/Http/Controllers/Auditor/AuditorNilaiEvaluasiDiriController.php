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
use Mpdf\Mpdf;
use Carbon\Carbon;
class AuditorNilaiEvaluasiDiriController extends Controller
{
    public function index()
    {
        $transaksi_ami = TransaksiAmi::whereHas('auditorAmi', function($query) {
            $query->where('users_kode', session('user_kode'));
        })
        ->where('status', 'Selesai')
        ->latest()
        ->get();

        foreach ($transaksi_ami as $item) {
            Carbon::setLocale('id');
            $item->formatted_created_at = Carbon::parse($item->created_at)->isoFormat('D MMMM Y');
        }

        return view('pages.auditor.nilai-evaluasi-diri.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }
    
    public function rekapNilai(Request $request, $periode, $prodi)
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
        

        return view('pages.auditor.nilai-evaluasi-diri.rekap-nilai.index', [
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
            ->keyBy('indikator_id');

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

    public function calculateTotalLamemeba($periode, $prodi, $accreditationKey)
    {
        // dd($periode, $prodi, $accreditationKey);
        // Ambil jenjang dan akreditasi dari string akreditasiKey
        $parts = explode(' ', $accreditationKey, 2);
        $akreditasi_kode = trim($parts[0] ?? 'LAMEMBA');
        $jenjang_nama    = trim($parts[1] ?? 'S1');

        // Validasi fallback
        $akreditasi = StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        $jenjang    = Jenjang::where('nama', $jenjang_nama)->firstOrFail();

        // Ambil semua standar dan indikator terkait
        $standards = Standard::with(['elements.indicators'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

        $indikatorIds = collect();
        foreach ($standards as $standard) {
            foreach ($standard->elements as $element) {
                foreach ($element->indicators as $indicator) {
                    $indikatorIds->push($indicator->id);
                }
            }
        }

        $indikatorIds = $indikatorIds->unique()->values();

        // Ambil nilai dari indikator tersebut
        $nilaiCollection = StandarNilai::where('periode', $periode)
            ->where('prodi', $prodi)
            ->whereIn('indikator_id', $indikatorIds)
            ->get();

        $totalNilai = $nilaiCollection->sum('hasil_nilai');
        $jumlahIndikator = $indikatorIds->count();

        // Hitung persentase
        $persentase = $jumlahIndikator > 0
            ? ($totalNilai / $jumlahIndikator) * 100
            : 0;

        // Ambil prefix prodi
        $prodiParts  = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        return [
            'total'       => round($persentase, 2),
            'prodiPrefix' => $prodiPrefix,
        ];
    }

    public function reportLha(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi_kode = $transaksi_ami->standar_akreditasi ?? 'BAN-PT';
        $jenjang_raw     = $prodi;
        $jenjang_nama    = trim(explode(' - ', (string)$jenjang_raw, 2)[0]) ?: 'S1';

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

        $standards = Standard::with(['elements.indicators.dokumen_nilais', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

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
        $tanggal = now()->format('d-m-Y');
        $tanggal_audit = optional($transaksi_ami->updated_at)->format('d-m-Y') ?? $tanggal;

        // 🔢 Hitung total nilai
        $totalResult = ($akreditasi_kode === 'LAMEMBA')
            ? $this->calculateTotalLamemeba($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}")
            : $this->calculateTotal($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}");

        $totalNilai  = $totalResult['total'];
        $prodiPrefix = $totalResult['prodiPrefix'];
        // dd($standards);

        // 🧾 Render view ke HTML
        $html = view('pages.report.report-lha', [
            'akreditasi'      => $akreditasi,
            'jenjang'         => $jenjang,
            'standards'       => $standards,
            'periode'         => $periode,
            'prodi'           => $jenjang_raw,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami'   => $transaksi_ami,
            'auditors'        => $auditors,
            'tanggal'         => $tanggal,
            'tanggal_audit'   => $tanggal_audit,
            'totalNilai'      => $totalNilai,
            'prodiPrefix'     => $prodiPrefix,
        ])->render();

        // 📄 Generate PDF
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetTitle('Laporan Hasil Audit');
        $mpdf->SetFooter('{DATE j-m-Y}||Page {PAGENO}');
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan_hasil_audit.pdf"');
    }

    public function reportRtm(Request $request, $periode, $prodi)
    {
        if (strpos($periode, '/') !== false) {
            [$tahunAwal, $tahunAkhir] = explode('/', $periode);
            $periode = (int)$tahunAwal;
        }

        $periodeSebelumnya = $periode - 1;
        $periodeSelanjutnya = $periode + 1;

        $tahunAjaran = $periode . '/' . ($periode + 1);
        $tahunAjaranSebelumnya = $periodeSebelumnya . '/' . ($periode);
        $tahunAjaranSelanjutnya = $periodeSelanjutnya . '/' . ($periodeSelanjutnya + 1);

        // dd($tahunAjaranSebelumnya, $tahunAjaran, $tahunAjaranSelanjutnya);


        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $transaksi_ami_sebelumnya = TransaksiAmi::where('periode', $periodeSebelumnya)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi_kode = $transaksi_ami->standar_akreditasi ?? 'BAN-PT';
        $jenjang_raw     = $prodi;
        $jenjang_nama    = trim(explode(' - ', (string)$jenjang_raw, 2)[0]) ?: 'S1';

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

        $standards = Standard::with(['elements.indicators.dokumen_nilais', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

        $standards_sebelumnya = Standard::with(['elements.indicators.dokumen_nilais', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

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
        $tanggal = now()->format('d-m-Y');
        $tanggal_audit = optional($transaksi_ami->updated_at)->format('d-m-Y') ?? $tanggal;

        // 🔢 Hitung total nilai
        $totalResult = ($akreditasi_kode === 'LAMEMBA')
            ? $this->calculateTotalLamemeba($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}")
            : $this->calculateTotal($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}");

        $totalNilai  = $totalResult['total'];
        $prodiPrefix = $totalResult['prodiPrefix'];

        $html = view('pages.report.report-rtm', [
            'akreditasi'                => $akreditasi,
            'jenjang'                   => $jenjang,
            'standards'                 => $standards,
            'periode'                   => $tahunAjaran,
            'prodi'                     => $jenjang_raw,
            'penjadwalan_ami'           => $penjadwalan_ami,
            'transaksi_ami'             => $transaksi_ami,
            'auditors'                  => $auditors,
            'tanggal'                   => $tanggal,
            'tanggal_audit'             => $tanggal_audit,
            'totalNilai'                => $totalNilai,
            'prodiPrefix'               => $prodiPrefix,
            'standards_sebelumnya'      => $standards_sebelumnya,
            'periodeSebelumnya'         => $tahunAjaranSebelumnya,
            'periodeSelanjutnya'         => $tahunAjaranSelanjutnya,
            'transaksi_ami_sebelumnya'  => $transaksi_ami_sebelumnya,

        ])->render();

        // 📄 Generate PDF
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetTitle('Hasil Rapat Tinjauan Manajemen');
        $mpdf->SetFooter('{DATE j-m-Y}||Page {PAGENO}');
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="hasil_rapat_tinjauan_manajemen.pdf"');

    }
}
