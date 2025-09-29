<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarNilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_kode',
        'indikator_id',
        'mandiri_nilai',
        'hasil_nilai',
        'bobot',
        'hasil_kriteria',
        'hasil_deskripsi',
        'jenis_temuan',
        'hasil_akibat',
        'hasil_masalah',
        'hasil_rekomendasi',
        'hasil_rencana_perbaikan',
        'hasil_jadwal_perbaikan',
        'hasil_perbaikan_penanggung',
        'hasil_rencana_pencegahan',
        'hasil_jadwal_pencegahan',
        'hasil_rencana_penanggung',
        'status_akhir',
        'prodi',
        'periode',
    ];

    public function standarElemenBanptS1()
    {
        return $this->belongsTo(StandarElemenBanptS1::class, 'indikator_id', 'indikator_id');
    }

    public function standarElemenLamdikS1()
    {
        return $this->belongsTo(StandarElemenLamdikS1::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisLamdikS2()
    {
        return $this->belongsTo(StandarElemenLamdikS2::class, 'indikator_id', 'indikator_id');
    }

    protected $compositeIndicatorsConfig = [
        'D3' => [
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
        ]
    ];

    public static function calculateTotal($periode, $prodi)
    {
        return self::where('periode', $periode)
            ->where('prodi', $prodi)
            ->get()
            ->sum(function ($nilai) {
                return $nilai->hasil_nilai * ($nilai->bobot ?? 1);
            });
    }

    public function calculateCompositeIndicator($indicator, $nilaiCollection, $prefix)
    {
        if (!isset($this->compositeIndicatorsConfig[$prefix][$indicator])) {
            return 0;
        }

        $config = $this->compositeIndicatorsConfig[$prefix][$indicator];
        $numerator = 0;

        foreach ($config['components'] as $component) {
            $kode = $component['kode'];
            $weight = $component['weight'];
            $value = $nilaiCollection->has($kode) ? $nilaiCollection[$kode]->hasil_nilai : 0;
            $numerator += $weight * $value;
        }

        $divisor    = $config['divisor'];
        $multiplier = $config['multiplier'];

        return ($numerator / $divisor) * $multiplier;
    }

    public function calculateTotalWithBANPTS1($periode, $prodi)
    {
        $nilaiCollection = self::where('periode', $periode)
            ->where('prodi', $prodi)
            ->get()
            ->keyBy('indikator_id'); 

        $total = 0;

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        if ($prodiPrefix === 'D3') {
            $indicatorPrefix = 'D3';
            $indicatorRange = 67;
        } elseif ($prodiPrefix === 'S1') {
            $indicatorPrefix = 'S1';
            $indicatorRange = 69; 
        } else {
            $indicatorPrefix = 'Unknown';
            $indicatorRange = 0;
        }

        $compositeIndicators = isset($this->compositeIndicatorsConfig[$indicatorPrefix])
            ? array_keys($this->compositeIndicatorsConfig[$indicatorPrefix])
            : [];

        $allIndicators = [];
        for ($i = 1; $i <= $indicatorRange; $i++) {
            $indicator = $indicatorPrefix . '-' . $i;
            $allIndicators[] = $indicator;
        }

        foreach ($compositeIndicators as $indicator) {
            $total += $this->calculateCompositeIndicator($indicator, $nilaiCollection, $indicatorPrefix);
        }

        foreach ($allIndicators as $indicator) {
            if (!in_array($indicator, $compositeIndicators) && $nilaiCollection->has($indicator)) {
                $nilai = $nilaiCollection[$indicator];
                $bobot = $nilai->bobot ?? 1;
                $total += $nilai->hasil_nilai * $bobot;
            }
        }

        return [
            'total' => $total,
            'prodiPrefix' => $prodiPrefix
        ];
    }

    public function calculateTotalWithLAMDIKS1($periode, $prodi)
    {
        $nilaiCollection = self::where('periode', $periode)
            ->where('prodi', $prodi)
            ->get()
            ->keyBy('indikator_id'); 

        $total = 0;

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        if ($prodiPrefix === 'S1') {
            $indicatorPrefix = 'S1';
            $indicatorRange = 64; 
        } else {
            $indicatorPrefix = 'Unknown';
            $indicatorRange = 0;
        }

        $compositeIndicators = isset($this->compositeIndicatorsConfig[$indicatorPrefix])
            ? array_keys($this->compositeIndicatorsConfig[$indicatorPrefix])
            : [];

        $allIndicators = [];
        for ($i = 1; $i <= $indicatorRange; $i++) {
            $indicator = $indicatorPrefix . '-' . $i;
            $allIndicators[] = $indicator;
        }

        foreach ($compositeIndicators as $indicator) {
            $total += $this->calculateCompositeIndicator($indicator, $nilaiCollection, $indicatorPrefix);
        }

        foreach ($allIndicators as $indicator) {
            if (!in_array($indicator, $compositeIndicators) && $nilaiCollection->has($indicator)) {
                $nilai = $nilaiCollection[$indicator];
                $bobot = $nilai->bobot ?? 1;
                $total += $nilai->hasil_nilai * $bobot;
            }
        }

        return [
            'total' => $total,
            'prodiPrefix' => $prodiPrefix
        ];
    }

    public function evaluateCriteria($periode, $prodi)
    {
        $data = [];

        $data['banpt_a1'] = $this->getHasilNilai('S1-12', $periode, $prodi) >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_a2'] = $this->getHasilNilai('S1-17', $periode, $prodi) >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_a3'] = ($this->getHasilNilai('S1-38A', $periode, $prodi) + (2 * $this->getHasilNilai('S1-38B', $periode, $prodi)) + (2 * $this->getHasilNilai('S1-38C', $periode, $prodi))) / 5 >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b1'] = $this->getHasilNilai('S1-18', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b2'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b3'] = $this->getHasilNilai('S1-60', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_b4'] = $this->getHasilNilai('S1-61', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c1'] = $this->getHasilNilai('S1-18', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c2'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c3'] = $this->getHasilNilai('S1-60', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['banpt_c4'] = $this->getHasilNilai('S1-61', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        
        $data['lamdik_a1'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_a2'] = $this->getHasilNilai('S1-49', $periode, $prodi) > 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_a3'] = $this->getHasilNilai('S1-35', $periode, $prodi) >= 3.25 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_a4'] = $this->getHasilNilai('S1-63', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_a5'] = $this->getHasilNilai('S1-15', $periode, $prodi) >= 4 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['lamdik_a6'] = $this->getHasilNilai('S1-55', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        return $data;
    }

    public function getHasilNilai($indikatorKode, $periode, $prodi)
    {
        return $this->where('indikator_id', $indikatorKode)
            ->where('periode', $periode)
            ->where('prodi', $prodi)
            ->value('hasil_nilai');
    }

    private function calculateHStatus(array $criteriaStatus, $total, $accreditationType)
    {
        if ($accreditationType === 'BAN-PT S1') {
            // BAN-PT S1 logic
            $h2 = ($criteriaStatus['a1'] === 'Terpenuhi' &&
                $criteriaStatus['a2'] === 'Terpenuhi' &&
                $criteriaStatus['a3'] === 'Terpenuhi')
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            $h3 = ($criteriaStatus['b1'] === 'Terpenuhi' &&
                $criteriaStatus['b2'] === 'Terpenuhi' &&
                $criteriaStatus['b3'] === 'Terpenuhi' &&
                $criteriaStatus['b4'] === 'Terpenuhi')
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            $h4 = ($criteriaStatus['c1'] === 'Terpenuhi' &&
                $criteriaStatus['c2'] === 'Terpenuhi' &&
                $criteriaStatus['c3'] === 'Terpenuhi' &&
                $criteriaStatus['c4'] === 'Terpenuhi')
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
        } elseif ($accreditationType === 'LAMDIK S1') {
            // LAMDIK S1 logic (adjust thresholds and criteria as needed)
            // Here we use a different set of criteria keys and/or thresholds.
            $h2 = ($criteriaStatus['lamdik_a1'] ?? 'Tidak Terpenuhi') === 'Terpenuhi'
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            $h3 = ($criteriaStatus['lamdik_a2'] ?? 'Tidak Terpenuhi') === 'Terpenuhi'
                ? 'Terpenuhi' : 'Tidak Terpenuhi';

            // You can define h4, h5, and h6 using your specific LAMDIK rules:
            $h4 = ($total >= 350) ? 'Terpenuhi' : 'Tidak Terpenuhi';
            $h5 = ($total >= 350) ? 'Tercapai' : 'Belum Tercapai';
            $h6 = ($total > 360) ? 'Unggul' : (($total > 310) ? 'Baik Sekali' : 'Baik');
        } else {
            // Fallback in case the accreditation type is unrecognized.
            $h2 = $h3 = $h4 = $h5 = $h6 = '-';
        }

        return compact('h2', 'h3', 'h4', 'h5', 'h6');
    }

}
