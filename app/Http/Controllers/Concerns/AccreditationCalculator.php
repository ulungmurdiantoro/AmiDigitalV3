<?php

namespace App\Http\Controllers\Concerns;

use App\Models\StandarAkreditasi;
use App\Models\StandarNilai;
use App\Models\Jenjang;
use App\Models\Standard;

/**
 * Reusable trait: NA calculation + accreditation forecasting.
 *
 * Scoring mechanisms per LAM (from official Pedoman Penilaian documents):
 *   BAN-PT    IAPS 5.1        : Σ(bobot×skor), NA ≥ 80% → Terakreditasi
 *   LAMDIK    IAPS/IAPSK 3.0  : Σ(bobot×skor), NA ≥ 200 → Terakreditasi; NA ≥ 361 → Unggul
 *   LAMSAMA   IAPS 3.1        : no formula, per-butir minimum ≥ CUKUP (2)
 *   LAMEMBA   IAU             : % pemenuhan ≥ 90% (29 ind) → Terakreditasi
 *   LAMTEKNIK IAPS AVP 2025   : Σ(bobot×skor), NA ≥ 200; Unggul 3yr ≥ 331, 5yr ≥ 361 + rerata
 *   LAMINFOKOM IAPS 2.1       : Σ(bobot×skor)/4, NA ≥ 200; Unggul 3yr ≥ 321, 5yr ≥ 361 + rerata ≥ 3.20
 *   LAMPTKES  Kualitatif       : per-bidang, rerata skor ≥ 2 → Terakreditasi
 */
trait AccreditationCalculator
{
    // =========================================================================
    // 1. NA CALCULATION
    // =========================================================================

    /**
     * Existing composite-aware total for BAN-PT and LAMDIK.
     * Returns ['total' => float, 'prodiPrefix' => string]
     */
    public function calculateTotal($periode, $prodi, $accreditationKey): array
    {
        $compositeIndicatorsConfig = [
            'BAN-PT D3' => [],
            'BAN-PT S1' => [
                'S1-6'  => ['components' => [['kode' => 'S1-6A',  'weight' => 1], ['kode' => 'S1-6B',  'weight' => 2]], 'divisor' => 3, 'multiplier' => 0.34],
                'S1-7'  => ['components' => [['kode' => 'S1-7A',  'weight' => 1], ['kode' => 'S1-7B',  'weight' => 2]], 'divisor' => 3, 'multiplier' => 0.34],
                'S1-9'  => ['components' => [['kode' => 'S1-9A',  'weight' => 2], ['kode' => 'S1-9B',  'weight' => 1]], 'divisor' => 3, 'multiplier' => 0.34],
                'S1-15' => ['components' => [['kode' => 'S1-15A', 'weight' => 2], ['kode' => 'S1-15B', 'weight' => 1]], 'divisor' => 3, 'multiplier' => 3.07],
                'S1-16' => ['components' => [['kode' => 'S1-16A', 'weight' => 1], ['kode' => 'S1-16B', 'weight' => 2]], 'divisor' => 3, 'multiplier' => 1.53],
                'S1-31' => ['components' => [['kode' => 'S1-31A', 'weight' => 1], ['kode' => 'S1-31B', 'weight' => 1]], 'divisor' => 2, 'multiplier' => 1.12],
                'S1-38' => ['components' => [['kode' => 'S1-38A', 'weight' => 1], ['kode' => 'S1-38B', 'weight' => 2], ['kode' => 'S1-38C', 'weight' => 2]], 'divisor' => 5, 'multiplier' => 2.51],
                'S1-40' => ['components' => [['kode' => 'S1-40A', 'weight' => 1], ['kode' => 'S1-40B', 'weight' => 2]], 'divisor' => 3, 'multiplier' => 1.67],
                'S1-41' => ['components' => [['kode' => 'S1-41A', 'weight' => 1], ['kode' => 'S1-41B', 'weight' => 2], ['kode' => 'S1-41C', 'weight' => 2], ['kode' => 'S1-41D', 'weight' => 2], ['kode' => 'S1-41E', 'weight' => 2]], 'divisor' => 9, 'multiplier' => 1.12],
                'S1-44' => ['components' => [['kode' => 'S1-44A', 'weight' => 1], ['kode' => 'S1-44B', 'weight' => 2], ['kode' => 'S1-44C', 'weight' => 2]], 'divisor' => 5, 'multiplier' => 1.67],
                'S1-47' => ['components' => [['kode' => 'S1-47A', 'weight' => 1], ['kode' => 'S1-47B', 'weight' => 2]], 'divisor' => 3, 'multiplier' => 3.35],
            ],
            'LAMDIK S1'  => [],
            'LAMDIK PPG' => [],
            'LAMDIK S2'  => [],
        ];

        $nilaiCollection = StandarNilai::where('periode', $periode)
            ->where('prodi', $prodi)
            ->get()
            ->keyBy('indikator_id');

        $compositeTotal    = 0;
        $nonCompositeTotal = 0;
        $prodiParts        = explode(' - ', $prodi);
        $prodiPrefix       = trim($prodiParts[0] ?? $prodi);

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
            return ['total' => 0, 'prodiPrefix' => $prodiPrefix];
        }

        $compositeConfigKey  = $accreditationKey;
        $compositeIndicators = array_keys($compositeIndicatorsConfig[$compositeConfigKey] ?? []);

        $allIndicators = [];
        for ($i = 1; $i <= $indicatorRange; $i++) {
            $allIndicators[] = $indicatorPrefix . '-' . $i;
        }

        foreach ($compositeIndicators as $indicator) {
            $config        = $compositeIndicatorsConfig[$compositeConfigKey][$indicator];
            $totalComponent = 0;
            foreach ($config['components'] as $component) {
                if ($nilaiCollection->has($component['kode'])) {
                    $totalComponent += $nilaiCollection[$component['kode']]->hasil_nilai * $component['weight'];
                }
            }
            $compositeTotal += ($totalComponent / $config['divisor']) * $config['multiplier'];
        }

        foreach ($allIndicators as $indicator) {
            if (!in_array($indicator, $compositeIndicators) && $nilaiCollection->has($indicator)) {
                $nilai           = $nilaiCollection[$indicator];
                $bobot           = $nilai->bobot ?? 1;
                $nonCompositeTotal += $nilai->hasil_nilai * $bobot;
            }
        }

        return [
            'total'       => round($compositeTotal + $nonCompositeTotal, 2),
            'prodiPrefix' => $prodiPrefix,
        ];
    }

    /**
     * LAMEMBA percentage-based total.
     * Returns ['total' => float (0–100), 'prodiPrefix' => string]
     */
    public function calculateTotalLamemeba($periode, $prodi, $accreditationKey): array
    {
        $parts           = explode(' ', $accreditationKey, 2);
        $akreditasi_kode = trim($parts[0] ?? 'LAMEMBA');
        $jenjang_nama    = trim($parts[1] ?? 'S1');

        $akreditasi = StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        $jenjang    = Jenjang::where('nama', $jenjang_nama)->firstOrFail();

        $standards = Standard::with([
            'elements.indicators.dokumen_nilais' => function ($q) use ($periode, $prodi) {
                $q->where('periode', $periode)->where('prodi', $prodi);
            },
        ])->where('standar_akreditasi_id', $akreditasi->id)
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

        $nilaiCollection = StandarNilai::where('periode', $periode)
            ->where('prodi', $prodi)
            ->whereIn('indikator_id', $indikatorIds)
            ->get();

        $totalNilai      = $nilaiCollection->sum('hasil_nilai');
        $jumlahIndikator = $indikatorIds->count();
        $persentase      = $jumlahIndikator > 0 ? ($totalNilai / $jumlahIndikator) * 100 : 0;

        $prodiParts  = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        return [
            'total'       => round($persentase, 2),
            'prodiPrefix' => $prodiPrefix,
        ];
    }

    /**
     * Compute NA from a pre-loaded $standards collection (for LAMTEKNIK, LAMINFOKOM, LAMSAMA, LAMPTKES).
     * Returns float NA value.
     *
     * For LAMINFOKOM: Σbobot = 400, formula = Σ(skor × bobot/4), NA_max = 400
     * For others (LAMTEKNIK, LAMSAMA, LAMPTKES): Σ(skor × bobot), Σbobot = 100, NA_max = 400
     */
    public function computeNaFromStandards($standards, string $akreditasiKode): float
    {
        $total = 0.0;
        foreach ($standards as $standard) {
            foreach ($standard->elements as $element) {
                foreach ($element->indicators as $indicator) {
                    $nilai = $indicator->dokumen_nilais;
                    if ($nilai === null) continue;
                    $skor  = (float) ($nilai->hasil_nilai ?? 0);
                    $bobot = (float) ($nilai->bobot ?? $indicator->bobot ?? 1);
                    // LAMINFOKOM: bobot_total=400, normalize by dividing by 4 so NA_max=400
                    $divisor = ($akreditasiKode === 'LAMINFOKOM') ? 4.0 : 1.0;
                    $total  += $skor * ($bobot / $divisor);
                }
            }
        }
        return round($total, 2);
    }

    // =========================================================================
    // 2. SCORE EXTRACTION HELPER
    // =========================================================================

    /**
     * Extract flat list of scores from eager-loaded $standards collection.
     * Returns array of ['standard' => string, 'skor' => float, 'bobot' => float]
     */
    protected function extractScores($standards): array
    {
        $scores = [];
        foreach ($standards as $standard) {
            foreach ($standard->elements as $element) {
                foreach ($element->indicators as $indicator) {
                    $nilai = $indicator->dokumen_nilais;
                    if ($nilai === null) continue;
                    $scores[] = [
                        'standard' => $standard->nama,
                        'element'  => $element->nama,
                        'skor'     => (float) ($nilai->hasil_nilai ?? 0),
                        'bobot'    => (float) ($nilai->bobot ?? $indicator->bobot ?? 1),
                    ];
                }
            }
        }
        return $scores;
    }

    /** Returns ['standard_name' => avg_skor, ...] */
    protected function avgByStandard(array $scores): array
    {
        $grouped = [];
        foreach ($scores as $s) {
            $grouped[$s['standard']][] = $s['skor'];
        }
        return array_map(fn($v) => round(array_sum($v) / count($v), 3), $grouped);
    }

    // =========================================================================
    // 3. MASTER FORECAST DISPATCHER
    // =========================================================================

    /**
     * Returns forecast array:
     * [
     *   'status'           => string,
     *   'durasi'           => string,
     *   'warna'            => 'green'|'blue'|'orange'|'red'|'gray',
     *   'detail'           => string,
     *   'na_label'         => string,
     *   'na_display'       => string,
     *   'threshold_label'  => string,
     * ]
     */
    public function calculateForecast(string $akreditasiKode, float $totalNilai, $standards): array
    {
        $scores = $this->extractScores($standards);

        return match ($akreditasiKode) {
            'LAMDIK'     => $this->forecastLamdik($totalNilai),
            'LAMSAMA'    => $this->forecastLamsama($scores),
            'LAMEMBA'    => $this->forecastLamemba($totalNilai),
            'LAMTEKNIK'  => $this->forecastLamteknik($totalNilai, $scores),
            'LAMINFOKOM' => $this->forecastLaminfokom($totalNilai, $scores),
            'LAMPTKES'   => $this->forecastLamptkes($scores),
            default      => $this->forecastBanpt($totalNilai, $scores),
        };
    }

    // =========================================================================
    // 4. PER-LAM FORECAST METHODS
    // =========================================================================

    /**
     * BAN-PT IAPS 5.1 — uses average skor (not raw NA) since calculateTotal returns weighted sum on unknown scale.
     * Syarat perlu terpenuhi + rata-rata skor ≥ 2.00 → Terakreditasi; ≥ 3.50 → Terakreditasi Unggul.
     */
    private function forecastBanpt(float $totalNilai, array $scores): array
    {
        $naFmt = number_format($totalNilai, 2);
        $avg   = count($scores) ? round(array_sum(array_column($scores, 'skor')) / count($scores), 2) : 0;

        if (empty($scores)) {
            return $this->buildForecast(
                'Data Tidak Cukup', '-', 'gray',
                'Belum ada data penilaian.',
                'Nilai Akreditasi (NA)', $naFmt,
                'Syarat perlu terpenuhi + rata-rata skor ≥ 2,00 = Terakreditasi | Rata-rata ≥ 3,50 = Terakreditasi Unggul'
            );
        }

        if ($avg < 2.0) {
            return $this->buildForecast(
                'Tidak Terakreditasi', '-', 'red',
                "Rata-rata skor {$avg} < 2,00. Masih di bawah ambang syarat perlu BAN-PT IAPS 5.1.",
                'Nilai Akreditasi (NA)', $naFmt,
                'Rata-rata skor ≥ 2,00 + syarat perlu terpenuhi = Terakreditasi | ≥ 3,50 = Terakreditasi Unggul | < 2,00 = Tidak Terakreditasi'
            );
        }

        if ($avg >= 3.5) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '5 Tahun', 'green',
                "Rata-rata skor {$avg} ≥ 3,50 → melampaui SN Dikti. Indikasi kuat Terakreditasi Unggul.",
                'Nilai Akreditasi (NA)', $naFmt,
                'Rata-rata skor ≥ 3,50 = Terakreditasi Unggul | ≥ 2,00 = Terakreditasi | < 2,00 = Tidak Terakreditasi'
            );
        }

        return $this->buildForecast(
            'Terakreditasi', '5 Tahun', 'blue',
            "Rata-rata skor {$avg} (2,00–3,49). Memenuhi syarat. Untuk Unggul perlu rata-rata ≥ 3,50.",
            'Nilai Akreditasi (NA)', $naFmt,
            'Rata-rata skor ≥ 2,00 = Terakreditasi | ≥ 3,50 = Terakreditasi Unggul | < 2,00 = Tidak Terakreditasi'
        );
    }

    /** LAMDIK IAPS/IAPSK 3.0 — NA ≥ 200 Terakreditasi; NA ≥ 361 Unggul */
    private function forecastLamdik(float $na): array
    {
        $naFmt = number_format($na, 2);

        if ($na < 200) {
            return $this->buildForecast(
                'Tidak Terakreditasi', '-', 'red',
                "NA = {$naFmt} < 200 (maks 400). Perlu peningkatan besar.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                'NA ≥ 200 = Terakreditasi (5 thn) | NA ≥ 361 + syarat perlu = Terakreditasi Unggul | NA < 200 = Tidak Terakreditasi'
            );
        }

        if ($na >= 361) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '5 Tahun', 'green',
                "NA = {$naFmt} ≥ 361. Memenuhi syarat nilai untuk Terakreditasi Unggul.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                'NA ≥ 361 + syarat perlu terpenuhi = Terakreditasi Unggul | NA ≥ 200 = Terakreditasi'
            );
        }

        $sisa = number_format(361 - $na, 2);
        return $this->buildForecast(
            'Terakreditasi', '5 Tahun', 'blue',
            "NA = {$naFmt} (200–360). Perlu tambahan {$sisa} poin untuk mencapai ambang Unggul (NA ≥ 361).",
            'Nilai Akreditasi (NA)', "{$naFmt} / 400",
            'NA ≥ 200 = Terakreditasi | NA ≥ 361 + syarat perlu = Terakreditasi Unggul | NA < 200 = Tidak Terakreditasi'
        );
    }

    /**
     * LAMSAMA IAPS 3.1 — tidak ada formula NA; per-butir minimum.
     * Terakreditasi: semua butir ≥ CUKUP (2).
     * Terakreditasi Unggul: semua butir ≥ BAIK SEKALI (4) → estimasi dari Matriks Unggul.
     */
    private function forecastLamsama(array $scores): array
    {
        if (empty($scores)) {
            return $this->buildForecast(
                'Data Tidak Cukup', '-', 'gray',
                'Belum ada data penilaian yang tersimpan.',
                'Analisis Per Butir', '—',
                'Semua butir ≥ CUKUP (2) = Terakreditasi | Mayoritas BAIK SEKALI (4) = Terakreditasi Unggul'
            );
        }

        $total   = count($scores);
        $kurang  = array_filter($scores, fn($s) => $s['skor'] < 2);
        $cukup   = array_filter($scores, fn($s) => $s['skor'] >= 2);
        $baik    = array_filter($scores, fn($s) => $s['skor'] >= 3);
        $unggul  = array_filter($scores, fn($s) => $s['skor'] >= 4);
        $nKurang = count($kurang);
        $nUnggul = count($unggul);

        $display = count($unggul) . " Baik Sekali | " . count($baik) . " Baik | " . count($cukup) . " Cukup | {$nKurang} Kurang dari {$total} butir";

        if ($nKurang > 0) {
            return $this->buildForecast(
                'Tidak Terakreditasi', '-', 'red',
                "{$nKurang} dari {$total} butir bernilai KURANG (<2). Semua butir harus minimal CUKUP (2) untuk Terakreditasi.",
                'Analisis Per Butir', $display,
                'Semua butir ≥ CUKUP (2) = Terakreditasi | Ada KURANG (1) = Tidak Terakreditasi'
            );
        }

        // Estimasi Unggul: perlu instrument terpisah (35 butir), tapi sebagai indikasi awal:
        if ($nUnggul === $total) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '5 Tahun', 'green',
                "Semua {$total} butir = BAIK SEKALI (4). Indikasi kuat siap untuk instrumen Terakreditasi Unggul (35 butir).",
                'Analisis Per Butir', $display,
                'Semua butir BAIK SEKALI (4) = indikasi Unggul | Semua ≥ CUKUP (2) = Terakreditasi'
            );
        }

        $sisaUnggul = $total - $nUnggul;
        return $this->buildForecast(
            'Terakreditasi', '5 Tahun', 'blue',
            "Semua {$total} butir ≥ CUKUP. Tidak ada yang KURANG. "
            . "Untuk Unggul perlu semua butir BAIK SEKALI — masih ada {$sisaUnggul} butir yang belum mencapai skor 4.",
            'Analisis Per Butir', $display,
            'Semua butir ≥ CUKUP (2) = Terakreditasi | Semua BAIK SEKALI (4) = indikasi Terakreditasi Unggul'
        );
    }

    /**
     * LAMEMBA — persentase pemenuhan.
     * Instrumen Terakreditasi (29 ind): ≥ 90% → Terakreditasi.
     * Instrumen Unggul (58 ind, berbeda): diaudit terpisah.
     */
    private function forecastLamemba(float $persentase): array
    {
        $pFmt = number_format($persentase, 2);

        if ($persentase >= 90) {
            return $this->buildForecast(
                'Terakreditasi', '5 Tahun', 'blue',
                "Pemenuhan {$pFmt}% ≥ 90% (26/29 indikator). Memenuhi syarat Terakreditasi. "
                . "Untuk Unggul diperlukan instrumen berbeda (7 kriteria, 58 indikator).",
                'Pemenuhan Indikator', "{$pFmt}% dari 100%",
                '≥ 90% (26/29 ind) + 15 syarat perlu = Terakreditasi | Untuk Unggul: instrumen IAU (58 ind, 7 kriteria)'
            );
        }

        $sisa = number_format(90 - $persentase, 2);
        return $this->buildForecast(
            'Tidak Terakreditasi', '-', 'red',
            "Pemenuhan {$pFmt}% < 90%. Masih perlu tambahan {$sisa}% untuk mencapai syarat minimal (≥ 90% atau 26 dari 29 indikator).",
            'Pemenuhan Indikator', "{$pFmt}% dari 100%",
            '≥ 90% (26/29 ind) + 15 syarat perlu + kualifikasi dosen = Terakreditasi | < 90% = Tidak Terakreditasi'
        );
    }

    /**
     * LAMTEKNIK IAPS AVP 2025.
     * NA ≥ 200 = Terakreditasi | 331–360 + rerata/kriteria ≥ 3.00 = Unggul 3 thn | NA ≥ 361 + rerata ≥ 3.50 = Unggul 5 thn
     */
    private function forecastLamteknik(float $na, array $scores): array
    {
        $naFmt = number_format($na, 2);
        $avgs  = $this->avgByStandard($scores);
        $minAvg = !empty($avgs) ? round(min($avgs), 2) : 0;

        if ($na < 200) {
            return $this->buildForecast(
                'Tidak Terakreditasi', '-', 'red',
                "NA = {$naFmt} < 200 (maks 400). Perlu peningkatan signifikan.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                'NA ≥ 200 = Terakreditasi | 331 ≤ NA < 361 + rerata tiap kriteria ≥ 3,00 = Unggul 3 Thn | NA ≥ 361 + rerata ≥ 3,50 = Unggul 5 Thn'
            );
        }

        if ($na >= 361 && $minAvg >= 3.5) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '5 Tahun', 'green',
                "NA = {$naFmt} ≥ 361 dan rerata terendah per kriteria {$minAvg} ≥ 3,50. Memenuhi syarat Unggul 5 tahun.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                'NA ≥ 361 + rerata tiap kriteria ≥ 3,50 = Terakreditasi Unggul 5 Thn'
            );
        }

        if (($na >= 331 && $minAvg >= 3.0) || ($na >= 361 && $minAvg >= 3.0)) {
            $durasi = $na >= 361 ? '3 Tahun*' : '3 Tahun';
            return $this->buildForecast(
                'Terakreditasi Unggul', $durasi, 'orange',
                "NA = {$naFmt} dan rerata terendah per kriteria {$minAvg} ≥ 3,00. "
                . "Memenuhi syarat Unggul 3 tahun. Untuk 5 tahun perlu NA ≥ 361 dan rerata ≥ 3,50.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                '331 ≤ NA < 361 + rerata tiap kriteria ≥ 3,00 = Unggul 3 Thn | NA ≥ 361 + rerata ≥ 3,50 = Unggul 5 Thn'
            );
        }

        $detail = "NA = {$naFmt} (≥ 200). Terakreditasi.";
        if ($na >= 331) {
            $detail .= " NA sudah ≥ 331 tetapi rerata terendah per kriteria {$minAvg} < 3,00 — perlu ditingkatkan untuk Unggul.";
        } else {
            $sisa = number_format(331 - $na, 2);
            $detail .= " Perlu tambahan {$sisa} poin untuk mencapai ambang Unggul (NA ≥ 331 + rerata per kriteria ≥ 3,00).";
        }

        return $this->buildForecast(
            'Terakreditasi', '5 Tahun', 'blue', $detail,
            'Nilai Akreditasi (NA)', "{$naFmt} / 400",
            'NA ≥ 200 = Terakreditasi | 331 ≤ NA < 361 + rerata ≥ 3,00 = Unggul 3 Thn | NA ≥ 361 + rerata ≥ 3,50 = Unggul 5 Thn'
        );
    }

    /**
     * LAMINFOKOM IAPS 2.1.
     * NA = Σ(skor × bobot/4), bobot_total = 400, NA_max = 400.
     * Syarat Unggul: rerata tiap kriteria ≥ 3,20 DAN setiap butir ≥ 3,00.
     */
    private function forecastLaminfokom(float $na, array $scores): array
    {
        $naFmt  = number_format($na, 2);
        $avgs   = $this->avgByStandard($scores);
        $minAvg = !empty($avgs) ? round(min($avgs), 3) : 0;
        $minSkor = !empty($scores) ? min(array_column($scores, 'skor')) : 0;

        $syaratUnggul = ($minAvg >= 3.2 && $minSkor >= 3.0);

        if ($na < 200) {
            return $this->buildForecast(
                'Tidak Terakreditasi', '-', 'red',
                "NA = {$naFmt} < 200 (maks 400).",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                'NA ≥ 200 = Terakreditasi | 321 ≤ NA < 361 + rerata kriteria ≥ 3,20 + butir ≥ 3,00 = Unggul 3 Thn | NA ≥ 361 + syarat = Unggul 5 Thn'
            );
        }

        if ($na >= 361 && $syaratUnggul) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '5 Tahun', 'green',
                "NA = {$naFmt} ≥ 361, rerata terendah per kriteria {$minAvg} ≥ 3,20, dan semua butir ≥ 3,00.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                'NA ≥ 361 + rerata kriteria ≥ 3,20 + semua butir ≥ 3,00 = Terakreditasi Unggul 5 Thn'
            );
        }

        if ($na >= 321 && $syaratUnggul) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '3 Tahun', 'orange',
                "NA = {$naFmt} (321–360), rerata terendah per kriteria {$minAvg} ≥ 3,20, dan semua butir ≥ 3,00. "
                . "Untuk 5 tahun perlu NA ≥ 361.",
                'Nilai Akreditasi (NA)', "{$naFmt} / 400",
                '321 ≤ NA < 361 + rerata ≥ 3,20 + butir ≥ 3,00 = Unggul 3 Thn | NA ≥ 361 + syarat = Unggul 5 Thn'
            );
        }

        // Terakreditasi — analisis gap untuk Unggul
        $detail = "NA = {$naFmt} (≥ 200). Terakreditasi.";
        if ($na >= 321) {
            $gap = [];
            if ($minAvg < 3.2) $gap[] = "rerata kriteria terendah {$minAvg} < 3,20";
            if ($minSkor < 3.0) $gap[] = "ada butir dengan skor " . number_format($minSkor, 0) . " < 3,00";
            $detail .= " NA sudah ≥ 321 tetapi belum memenuhi syarat Unggul: " . implode(' dan ', $gap) . ".";
        } else {
            $sisa = number_format(321 - $na, 2);
            $detail .= " Perlu tambahan {$sisa} poin untuk mencapai ambang Unggul (NA ≥ 321).";
        }

        return $this->buildForecast(
            'Terakreditasi', '5 Tahun', 'blue', $detail,
            'Nilai Akreditasi (NA)', "{$naFmt} / 400",
            'NA ≥ 200 = Terakreditasi | 321 ≤ NA + rerata ≥ 3,20 + butir ≥ 3,00 = Unggul 3 Thn | NA ≥ 361 = Unggul 5 Thn'
        );
    }

    /** LAM-PTKes — kualitatif per bidang, rerata skor sebagai estimasi */
    private function forecastLamptkes(array $scores): array
    {
        if (empty($scores)) {
            return $this->buildForecast(
                'Data Tidak Cukup', '-', 'gray',
                'Belum ada data penilaian.',
                'Rata-rata Skor', '—',
                'Skor rata-rata ≥ 2,00 = Terakreditasi | ≥ 3,50 = Terakreditasi Unggul (estimasi per bidang)'
            );
        }

        $avg  = round(array_sum(array_column($scores, 'skor')) / count($scores), 2);
        $avgs = $this->avgByStandard($scores);

        if ($avg < 2.0) {
            return $this->buildForecast(
                'Tidak Terakreditasi', '-', 'red',
                "Rata-rata skor {$avg} < 2,00. Masih di bawah standar minimal LAM-PTKes.",
                'Rata-rata Skor', "{$avg} / 4",
                'Rata-rata ≥ 2,00 = Terakreditasi | ≥ 3,50 = Terakreditasi Unggul'
            );
        }

        if ($avg >= 3.5) {
            return $this->buildForecast(
                'Terakreditasi Unggul', '5 Tahun', 'green',
                "Rata-rata skor {$avg} ≥ 3,50. Melampaui standar klinis LAM-PTKes.",
                'Rata-rata Skor', "{$avg} / 4",
                'Rata-rata ≥ 3,50 = Terakreditasi Unggul | ≥ 2,00 = Terakreditasi'
            );
        }

        return $this->buildForecast(
            'Terakreditasi', '5 Tahun', 'blue',
            "Rata-rata skor {$avg} (2,00–3,49). Memenuhi standar LAM-PTKes. Untuk Unggul perlu rata-rata ≥ 3,50.",
            'Rata-rata Skor', "{$avg} / 4",
            'Rata-rata ≥ 2,00 = Terakreditasi | ≥ 3,50 = Terakreditasi Unggul | < 2,00 = Tidak Terakreditasi'
        );
    }

    // =========================================================================
    // 5. BUILD HELPER
    // =========================================================================

    private function buildForecast(
        string $status,
        string $durasi,
        string $warna,
        string $detail,
        string $naLabel,
        string $naDisplay,
        string $thresholdLabel
    ): array {
        return compact('status', 'durasi', 'warna', 'detail', 'naLabel', 'naDisplay', 'thresholdLabel');
    }
}
