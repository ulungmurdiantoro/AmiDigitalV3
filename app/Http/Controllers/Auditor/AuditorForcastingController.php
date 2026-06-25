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
        $transaksiAmi = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akses       = $transaksiAmi->standar_akreditasi ?? 'BAN-PT';
        $jenjang_raw = $prodi;

        // LAMSAMA / LAMEMBA / LAMINFOKOM — pass standards collection directly
        if ($akses === 'LAMSAMA') {
            $jenjang_nama = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';
            if (!in_array($jenjang_nama, Jenjang::pluck('nama')->toArray(), true)) $jenjang_nama = 'S1';

            $akreditasi = Cache::remember("akreditasi_{$akses}", 3600, fn() => StandarAkreditasi::where('nama', $akses)->firstOrFail());
            $jenjang    = Cache::remember("jenjang_{$jenjang_nama}", 3600, fn() => Jenjang::where('nama', $jenjang_nama)->firstOrFail());

            $standards = Standard::query()
                ->with(['elements.indicators.dokumen_nilais' => fn($q) => $q->where('periode', $periode)->where('prodi', $prodi)])
                ->where('standar_akreditasi_id', $akreditasi->id)
                ->where('jenjang_id', $jenjang->id)
                ->get();

            return view('pages.auditor.forcasting.hasil-forcasting.index', [
                'key'           => 'LAMSAMA',
                'periode'       => $periode,
                'prodi'         => $jenjang_raw,
                'standards'     => $standards,
                'transaksi_ami' => $transaksiAmi,
            ]);
        }

        if ($akses === 'LAMINFOKOM') {
            $jenjang_nama = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';
            if (!in_array($jenjang_nama, Jenjang::pluck('nama')->toArray(), true)) $jenjang_nama = 'S1';

            $akreditasi = Cache::remember("akreditasi_{$akses}", 3600, fn() => StandarAkreditasi::where('nama', $akses)->firstOrFail());
            $jenjang    = Cache::remember("jenjang_{$jenjang_nama}", 3600, fn() => Jenjang::where('nama', $jenjang_nama)->firstOrFail());

            $standards = Standard::query()
                ->with(['elements.indicators.dokumen_nilais' => fn($q) => $q->where('periode', $periode)->where('prodi', $prodi)])
                ->where('standar_akreditasi_id', $akreditasi->id)
                ->where('jenjang_id', $jenjang->id)
                ->get();

            return view('pages.auditor.forcasting.hasil-forcasting.index', [
                'key'          => 'LAMINFOKOM',
                'periode'      => $periode,
                'prodi'        => $jenjang_raw,
                'standards'    => $standards,
                'transaksi_ami' => $transaksiAmi,
            ]);
        }

        if ($akses === 'LAMTEKNIK') {
            $jenjang_nama = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';
            if (!in_array($jenjang_nama, Jenjang::pluck('nama')->toArray(), true)) $jenjang_nama = 'S1';

            $akreditasi = Cache::remember("akreditasi_{$akses}", 3600, fn() => StandarAkreditasi::where('nama', $akses)->firstOrFail());
            $jenjang    = Cache::remember("jenjang_{$jenjang_nama}", 3600, fn() => Jenjang::where('nama', $jenjang_nama)->firstOrFail());

            $standards = Standard::query()
                ->with(['elements.indicators.dokumen_nilais' => fn($q) => $q->where('periode', $periode)->where('prodi', $prodi)])
                ->where('standar_akreditasi_id', $akreditasi->id)
                ->where('jenjang_id', $jenjang->id)
                ->get();

            return view('pages.auditor.forcasting.hasil-forcasting.index', [
                'key'           => 'LAMTEKNIK',
                'periode'       => $periode,
                'prodi'         => $jenjang_raw,
                'standards'     => $standards,
                'transaksi_ami' => $transaksiAmi,
            ]);
        }

        // LAMEMBA — pass standards collection directly (no weighted-score formula)
        if ($akses === 'LAMEMBA') {
            $jenjang_nama = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';
            if (!in_array($jenjang_nama, Jenjang::pluck('nama')->toArray(), true)) $jenjang_nama = 'S1';

            $akreditasi = Cache::remember("akreditasi_{$akses}", 3600, fn() => StandarAkreditasi::where('nama', $akses)->firstOrFail());
            $jenjang    = Cache::remember("jenjang_{$jenjang_nama}", 3600, fn() => Jenjang::where('nama', $jenjang_nama)->firstOrFail());

            $standards = Standard::query()
                ->with(['elements.indicators.dokumen_nilais' => fn($q) => $q->where('periode', $periode)->where('prodi', $prodi)])
                ->where('standar_akreditasi_id', $akreditasi->id)
                ->where('jenjang_id', $jenjang->id)
                ->get();

            return view('pages.auditor.forcasting.hasil-forcasting.index', [
                'key'          => 'LAMEMBA',
                'periode'      => $periode,
                'prodi'        => $jenjang_raw,
                'standards'    => $standards,
                'transaksi_ami' => $transaksiAmi,
            ]);
        }

        // BAN-PT, LAMDIK — full criteria + weighted-score calculation
        preg_match('/\b(S[0-9]+|D[0-9]+|PPG|S1 Terapan)\b/', $prodi, $matches);
        $degree = isset($matches[0]) && in_array($matches[0], ['S1', 'S2', 'S3', 'PPG', 'S1 Terapan'])
            ? $matches[0]
            : 'S1';

        $accreditationKey = trim("{$akses} {$degree}");

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        if (!in_array($akses, $validAkreditasi, true)) {
            $akses            = 'BAN-PT';
            $accreditationKey = "BAN-PT {$degree}";
        }

        $criteriaStatus = $this->evaluateCriteria($periode, $prodi);
        $totalData      = $this->calculateTotal($periode, $prodi, $accreditationKey);
        $total          = $totalData['total'];
        $hStatus        = $this->calculateHStatus($criteriaStatus, $total, $accreditationKey);

        $mappings = [
            'BAN-PT S1' => [
                'tableTerakreditasi' => [
                    ['syarat' => 'Skor butir penilaian Penjaminan Mutu (keterlaksanaan Sistem Penjaminan Mutu Internal, akademik dan non akademik) ≥ 2,0. <br>Skor S1-12', 'status' => $criteriaStatus['banpt_a1']],
                    ['syarat' => 'Skor butir penilaian Kecukupan Jumlah DTPS ≥ 2,0. <br>Skor S1-17', 'status' => $criteriaStatus['banpt_a2']],
                    ['syarat' => 'Skor butir penilaian Kurikulum (keterlibatan pemangku kepentingan dalam proses evaluasi dan pemutakhiran kurikulum, kesesuaian capaian <br> pembelajaran dengan profil lulusan dan jenjang KKNI/SKKNI, ketepatan struktur kurikulum dalam pembentukan capaian pembelajaran) ≥ 2,0. <br>Skor S1-38', 'status' => $criteriaStatus['banpt_a3']],
                ],
                'tablePeringkatUnggul' => [
                    ['syarat' => 'Skor butir penilaian Kualifikasi Akademik DTPS ≥ 3,5. <br>Skor S1-18', 'status' => $criteriaStatus['banpt_b1']],
                    ['syarat' => 'Skor butir penilaian Jabatan Akademik DTPS ≥ 3,5. <br>Skor S1-19', 'status' => $criteriaStatus['banpt_b2']],
                    ['syarat' => 'Skor butir penilaian Waktu Tunggu ≥ 3,5. <br>Skor S1-60', 'status' => $criteriaStatus['banpt_b3']],
                    ['syarat' => 'Skor butir penilaian Kesesuaian Bidang Kerja ≥ 3,5. <br>Skor S1-61', 'status' => $criteriaStatus['banpt_b4']],
                ],
                'tableBaikSekali' => [
                    ['syarat' => 'Skor butir penilaian Kualifikasi Akademik DTPS ≥ 3,0. <br>Skor S1-18', 'status' => $criteriaStatus['banpt_c1']],
                    ['syarat' => 'Skor butir penilaian Jabatan Akademik DTPS ≥ 3,0. <br>Skor S1-19', 'status' => $criteriaStatus['banpt_c2']],
                    ['syarat' => 'Skor butir penilaian Waktu Tunggu ≥ 3,0. <br>Skor S1-60', 'status' => $criteriaStatus['banpt_c3']],
                    ['syarat' => 'Skor butir penilaian Kesesuaian Bidang Kerja ≥ 3,0. <br>Skor S1-61', 'status' => $criteriaStatus['banpt_c4']],
                ],
                'h2' => $hStatus['h2'], 'h3' => $hStatus['h3'], 'h4' => $hStatus['h4'],
                'h5' => $hStatus['h5'], 'h6' => $hStatus['h6'],
            ],
            'LAMDIK PPG' => [
                'tablePeringkatUnggul' => [
                    ['elemen' => 'Kualitas Dosen<br>(INPUT)', 'indikator' => 'DTPS memiliki kualifikasi akademik doktor (S3) dan jabatan akademik/fungsional.', 'kriteria' => 'a. ≥ 50% DTPS memiliki kualifikasi akademik doktor.<br>b. ≥ 3 DTPS memiliki jabatan akademik/fungsional minimal lektor kepala.', 'status' => $criteriaStatus['lamdik_ppg_1']],
                    ['elemen' => 'Kurikulum<br>(INPUT)', 'indikator' => 'PS melakukan asesmen pencapaian CPL berdasarkan capaian hasil belajar mahasiswa.', 'kriteria' => 'PS melakukan asesmen, evaluasi, dan tindak lanjut pencapaian CPL, didukung bukti sahih.', 'status' => $criteriaStatus['lamdik_ppg_2']],
                    ['elemen' => 'Pembelajaran Mikro (PROSES)', 'indikator' => 'PS merancang dan melaksanakan perkuliahan micro-teaching.', 'kriteria' => 'Microteaching di laboratorium, frekuensi ≥ 4 kali, melatihkan 8 keterampilan mengajar.', 'status' => $criteriaStatus['lamdik_ppg_3']],
                    ['elemen' => 'SPMI PPEPP (PROSES)', 'indikator' => 'PS memiliki dan melaksanakan SPMI dengan siklus PPEPP.', 'kriteria' => 'SPMI efektif: kebijakan, perangkat lengkap, melaksanakan, evaluasi berkala, pengendalian, peningkatan.', 'status' => $criteriaStatus['lamdik_ppg_4']],
                    ['elemen' => 'Produktivitas Publikasi Dosen (LUARAN)', 'indikator' => 'DTPS memiliki publikasi jurnal nasional/internasional.', 'kriteria' => '≥40% DTPS memiliki publikasi Sinta 2 dan/atau jurnal internasional bereputasi.', 'status' => $criteriaStatus['lamdik_ppg_5']],
                ],
                'h2' => $hStatus['h2'], 'h3' => $hStatus['h3'], 'h4' => $hStatus['h4'],
                'h5' => $hStatus['h5'], 'h6' => $hStatus['h6'],
            ],
            'LAMDIK S1' => [
                'tablePeringkatUnggul' => [
                    ['elemen' => 'Kualitas Dosen<br>(INPUT)', 'indikator' => 'DTPS memiliki kualifikasi akademik doktor (S3) dan jabatan akademik/fungsional.', 'kriteria' => 'a. ≥ 20% DTPS memiliki kualifikasi akademik doktor.<br>b. ≥ 2 DTPS memiliki jabatan akademik/fungsional minimal lektor kepala.', 'status' => $criteriaStatus['lamdik_s1_1']],
                    ['elemen' => 'Kurikulum<br>(INPUT)', 'indikator' => 'PS melakukan asesmen pencapaian CPL berdasarkan capaian hasil belajar mahasiswa.', 'kriteria' => 'PS melakukan asesmen, evaluasi, dan tindak lanjut pencapaian CPL, didukung bukti sahih.', 'status' => $criteriaStatus['lamdik_s1_2']],
                    ['elemen' => 'Pembelajaran Mikro (PROSES)', 'indikator' => 'PS merancang dan melaksanakan perkuliahan micro-teaching.', 'kriteria' => 'Microteaching di laboratorium, frekuensi ≥ 4 kali, melatihkan 8 keterampilan mengajar.', 'status' => $criteriaStatus['lamdik_s1_3']],
                    ['elemen' => 'SPMI PPEPP (PROSES)', 'indikator' => 'PS memiliki dan melaksanakan SPMI dengan siklus PPEPP.', 'kriteria' => 'SPMI efektif: kebijakan, perangkat lengkap, melaksanakan, evaluasi berkala, pengendalian, peningkatan.', 'status' => $criteriaStatus['lamdik_s1_4']],
                    ['elemen' => 'Produktivitas Karya Mahasiswa (LUARAN)', 'indikator' => '% mahasiswa menghasilkan karya inovatif dan/atau publikasi ilmiah.', 'kriteria' => '≥25% mahasiswa dalam 5 tahun terakhir memiliki karya inovatif atau publikasi Sinta 4.', 'status' => $criteriaStatus['lamdik_s1_5']],
                    ['elemen' => 'Produktivitas Publikasi Dosen (LUARAN)', 'indikator' => 'DTPS memiliki publikasi jurnal nasional/internasional.', 'kriteria' => '≥20% DTPS memiliki publikasi Sinta 2 dan/atau jurnal internasional bereputasi.', 'status' => $criteriaStatus['lamdik_s1_6']],
                ],
                'h2' => $hStatus['h2'], 'h3' => $hStatus['h3'], 'h4' => $hStatus['h4'],
                'h5' => $hStatus['h5'], 'h6' => $hStatus['h6'],
            ],
            'LAMDIK S2' => [
                'tablePeringkatUnggul' => [
                    ['elemen' => 'Kualitas Dosen<br>(INPUT)', 'indikator' => 'DTPS memiliki kualifikasi akademik doktor (S3) dan jabatan guru besar.', 'kriteria' => 'a. ≥ 100% DTPS memiliki kualifikasi akademik doktor.<br>b. ≥ 1 DTPS memiliki jabatan guru besar.', 'status' => $criteriaStatus['lamdik_s2_1']],
                    ['elemen' => 'Kurikulum<br>(INPUT)', 'indikator' => 'PS melakukan asesmen pencapaian CPL berdasarkan capaian hasil belajar mahasiswa.', 'kriteria' => 'PS melakukan asesmen, evaluasi, dan tindak lanjut pencapaian CPL, didukung bukti sahih.', 'status' => $criteriaStatus['lamdik_s2_2']],
                    ['elemen' => 'SPMI PPEPP (PROSES)', 'indikator' => 'PS memiliki dan melaksanakan SPMI dengan siklus PPEPP.', 'kriteria' => 'SPMI efektif: kebijakan, perangkat lengkap, melaksanakan, evaluasi berkala, pengendalian, peningkatan.', 'status' => $criteriaStatus['lamdik_s2_3']],
                    ['elemen' => 'Produktivitas Karya Mahasiswa (LUARAN)', 'indikator' => '% mahasiswa menghasilkan karya inovatif dan/atau publikasi ilmiah.', 'kriteria' => '≥25% mahasiswa dalam 3 tahun terakhir memiliki publikasi pada jurnal nasional terakreditasi minimal Sinta 3.', 'status' => $criteriaStatus['lamdik_s2_4']],
                    ['elemen' => 'Produktivitas Publikasi Dosen (LUARAN)', 'indikator' => 'DTPS memiliki publikasi jurnal nasional/internasional.', 'kriteria' => '≥60% DTPS memiliki publikasi Sinta 2 dan/atau jurnal internasional bereputasi.', 'status' => $criteriaStatus['lamdik_s2_5']],
                ],
                'h2' => $hStatus['h2'], 'h3' => $hStatus['h3'], 'h4' => $hStatus['h4'],
                'h5' => $hStatus['h5'], 'h6' => $hStatus['h6'],
            ],
        ];

        $mapping = $mappings[$accreditationKey] ?? [];

        return view('pages.auditor.forcasting.hasil-forcasting.index', array_merge([
            'periode' => $periode,
            'prodi'   => $jenjang_raw,
            'total'   => $total,
            'key'     => $accreditationKey,
        ], $mapping));
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
