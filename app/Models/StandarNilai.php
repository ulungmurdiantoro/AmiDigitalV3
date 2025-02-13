<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarNilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_kode',
        'indikator_kode',
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
        return $this->belongsTo(StandarElemenBanptS1::class, 'indikator_kode', 'indikator_kode');
    }

    protected $compositeIndicatorsConfig = [
        'D3' => [

        ],
        'S1' => [
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

    public function calculateTotalWithS1($periode, $prodi)
    {
        $nilaiCollection = self::where('periode', $periode)
            ->where('prodi', $prodi)
            ->get()
            ->keyBy('indikator_kode'); 

        $total = 0;

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        if ($prodiPrefix === 'D3') {
            $indicatorPrefix = 'D3';
            $indicatorRange = 69;
        } elseif ($prodiPrefix === 'S3') {
            $indicatorPrefix = 'S3';
            $indicatorRange = 69; 
        } elseif ($prodiPrefix === 'S2 Terapan') {
            $indicatorPrefix = 'S2 Terapan';
            $indicatorRange = 69; 
        } elseif ($prodiPrefix === 'S2') {
            $indicatorPrefix = 'S2';
            $indicatorRange = 69; 
        } elseif ($prodiPrefix === 'S1 Terapan') {
            $indicatorPrefix = 'S1 Terapan';
            $indicatorRange = 69; 
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

    public function evaluateCriteria($periode, $prodi)
    {
        $data = [];

        $data['a1'] = $this->getHasilNilai('S1-12', $periode, $prodi) >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['a2'] = $this->getHasilNilai('S1-17', $periode, $prodi) >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['a3'] = ($this->getHasilNilai('S1-38A', $periode, $prodi) + (2 * $this->getHasilNilai('S1-38B', $periode, $prodi)) + (2 * $this->getHasilNilai('S1-38C', $periode, $prodi))) / 5 >= 2 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['b1'] = $this->getHasilNilai('S1-18', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['b2'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['b3'] = $this->getHasilNilai('S1-60', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['b4'] = $this->getHasilNilai('S1-61', $periode, $prodi) >= 3.5 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['c1'] = $this->getHasilNilai('S1-18', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['c2'] = $this->getHasilNilai('S1-19', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['c3'] = $this->getHasilNilai('S1-60', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $data['c4'] = $this->getHasilNilai('S1-61', $periode, $prodi) >= 3 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        return $data;
    }

    public function getHasilNilai($indikatorKode, $periode, $prodi)
    {
        return $this->where('indikator_kode', $indikatorKode)
            ->where('periode', $periode)
            ->where('prodi', $prodi)
            ->value('hasil_nilai');
    }

}
