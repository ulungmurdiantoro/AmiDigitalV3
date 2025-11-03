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
use Carbon\Carbon;

class AuditorForcastingController extends Controller
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
        
        return view('pages.auditor.forcasting.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function hasilForcasting(Request $request, $periode, $prodi)
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

        return view('pages.auditor.forcasting.hasil-forcasting.index', [
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

    public function getHasilNilai($indikatorKode, $periode, $prodi)
    {
        return StandarNilai::where('indikator_id', $indikatorKode)
            ->where('periode', $periode)
            ->where('prodi', $prodi)
            ->value('hasil_nilai');
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

    public function evaluateCriteria($periode, $prodi)
    {
        $data = [];

        $data['banpt_a1'] = $this->getHasilNilai('S1-12', $periode, $prodi) >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_a2'] = $this->getHasilNilai('S1-17', $periode, $prodi) >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_a3'] = (
            $this->getHasilNilai('S1-38A', $periode, $prodi) + 
            (2 * $this->getHasilNilai('S1-38B', $periode, $prodi)) + 
            (2 * $this->getHasilNilai('S1-38C', $periode, $prodi))
        ) / 5 >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b1'] = $this->getHasilNilai('S1-18', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b2'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b3'] = $this->getHasilNilai('S1-60', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b4'] = $this->getHasilNilai('S1-61', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c1'] = $this->getHasilNilai('S1-18', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c2'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c3'] = $this->getHasilNilai('S1-60', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c4'] = $this->getHasilNilai('S1-61', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        $data['lamdik_s1_1'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s1_2'] = $this->getHasilNilai('S1-49', $periode, $prodi) > 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s1_3'] = $this->getHasilNilai('S1-35', $periode, $prodi) >= 3.25 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s1_4'] = $this->getHasilNilai('S1-63', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s1_5'] = $this->getHasilNilai('S1-15', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s1_6'] = $this->getHasilNilai('S1-55', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        $data['lamdik_ppg_1'] = $this->getHasilNilai('PPG-17', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_ppg_2'] = $this->getHasilNilai('PPG-46', $periode, $prodi) > 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_ppg_3'] = $this->getHasilNilai('PPG-35', $periode, $prodi) >= 3.25 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_ppg_4'] = $this->getHasilNilai('PPG-59', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_ppg_5'] = $this->getHasilNilai('PPG-52', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        $data['lamdik_s2_1'] = $this->getHasilNilai('S2-18', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s2_2'] = $this->getHasilNilai('S2-44', $periode, $prodi) > 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s2_3'] = $this->getHasilNilai('S2-59', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s2_4'] = $this->getHasilNilai('S2-14', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_s2_5'] = $this->getHasilNilai('S2-51', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        return $data;
    }

    private function calculateHStatus(array $criteriaStatus, $total, $accreditationType)
    {
        // dd($accreditationType);
        if ($accreditationType === 'BAN-PT S1') {
            $h2 = ($criteriaStatus['banpt_a1'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_a2'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_a3'] === 'Terpenuhi')
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            $h3 = ($criteriaStatus['banpt_b1'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_b2'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_b3'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_b4'] === 'Terpenuhi')
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            $h4 = ($criteriaStatus['banpt_c1'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_c2'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_c3'] === 'Terpenuhi' &&
                $criteriaStatus['banpt_c4'] === 'Terpenuhi')
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            $h5 = ($h2 === 'Terpenuhi') ? 'Terakreditasi' : 'Tidak Terakreditasi';

            if ($total >= 361 && $h2 === 'Terpenuhi' && $h3 === 'Terpenuhi' && $h5 === 'Terakreditasi') {
                $h6 = 'Unggul';
            } elseif ($total >= 361 && $h2 === 'Terpenuhi' && $h3 === 'Tidak Terpenuhi') {
                $h6 = 'Baik Sekali';
            } elseif ($total >= 301 && $total < 361 && $h2 === 'Terpenuhi' && $h4 === 'Terpenuhi' && $h5 === 'Terakreditasi') {
                $h6 = 'Baik Sekali';
            } elseif ($total >= 301 && $total < 361 && $h2 === 'Terpenuhi' && $h4 === 'Tidak Terpenuhi' && $h5 === 'Terakreditasi') {
                $h6 = 'Baik';
            } elseif ($total >= 200 && $total < 301 && $h2 === 'Terpenuhi') {
                $h6 = 'Baik';
            } else {
                $h6 = '-';
            }

            return compact('h2', 'h3', 'h4', 'h5', 'h6');
        } elseif ($accreditationType === 'LAMDIK PPG') {
            $lamdikKeys = ['lamdik_c1', 'lamdik_c2', 'lamdik_c3', 'lamdik_c4', 'lamdik_c5', 'lamdik_c6'];
            $allFulfilled = true;
            foreach ($lamdikKeys as $key) {
                if (($criteriaStatus[$key] ?? 'Tidak Terpenuhi') !== 'Terpenuhi') {
                    $allFulfilled = false;
                    break;
                }
            }

            if ($allFulfilled) {
                if ($total >= 361 && $total <= 400) {
                    $status = 'Unggul';
                    $year   = '5 tahun';
                } elseif ($total >= 321 && $total <= 360) {
                    $status = 'Unggul';
                    $year   = '3 tahun';
                } else {
                    $status = 'Tidak Terpenuhi';
                    $year   = '-';
                }
            } else {
                $status = 'Tidak Terpenuhi';
                $year   = '-';
            }

            $h2 = $status;
            $h3 = $year;
            $h4 = $status;
            $h5 = $status;
            $h6 = $status;

            return compact('h2', 'h3', 'h4', 'h5', 'h6');
        } elseif ($accreditationType === 'LAMDIK S1') {
            $lamdikKeys = ['lamdik_a1', 'lamdik_a2', 'lamdik_a3', 'lamdik_a4', 'lamdik_a5', 'lamdik_a6'];
            $allFulfilled = true;
            foreach ($lamdikKeys as $key) {
                if (($criteriaStatus[$key] ?? 'Tidak Terpenuhi') !== 'Terpenuhi') {
                    $allFulfilled = false;
                    break;
                }
            }

            if ($allFulfilled) {
                if ($total >= 361 && $total <= 400) {
                    $status = 'Unggul';
                    $year   = '5 tahun';
                } elseif ($total >= 321 && $total <= 360) {
                    $status = 'Unggul';
                    $year   = '3 tahun';
                } else {
                    $status = 'Tidak Terpenuhi';
                    $year   = '-';
                }
            } else {
                $status = 'Tidak Terpenuhi';
                $year   = '-';
            }

            $h2 = $status;
            $h3 = $year;
            $h4 = $status;
            $h5 = $status;
            $h6 = $status;

            return compact('h2', 'h3', 'h4', 'h5', 'h6');
        } elseif ($accreditationType === 'LAMDIK S2') {
            $lamdikKeys = ['lamdik_b1', 'lamdik_b2', 'lamdik_b3', 'lamdik_b4', 'lamdik_b5'];
            $allFulfilled = true;
            foreach ($lamdikKeys as $key) {
                if (($criteriaStatus[$key] ?? 'Tidak Terpenuhi') !== 'Terpenuhi') {
                    $allFulfilled = false;
                    break;
                }
            }

            if ($allFulfilled) {
                if ($total >= 361 && $total <= 400) {
                    $status = 'Unggul';
                    $year   = '5 tahun';
                } elseif ($total >= 321 && $total <= 360) {
                    $status = 'Unggul';
                    $year   = '3 tahun';
                } else {
                    $status = 'Tidak Terpenuhi';
                    $year   = '-';
                }
            } else {
                $status = 'Tidak Terpenuhi';
                $year   = '-';
            }

            $h2 = $status;
            $h3 = $year;
            $h4 = $status;
            $h5 = $status;
            $h6 = $status;

            return compact('h2', 'h3', 'h4', 'h5', 'h6');
        } else {
            $h2 = $h3 = $h4 = $h5 = $h6 = '-';
            return compact('h2', 'h3', 'h4', 'h5', 'h6');
        }
    }
}
