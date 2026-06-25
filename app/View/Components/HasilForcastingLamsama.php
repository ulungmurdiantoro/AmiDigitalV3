<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class HasilForcastingLamsama extends Component
{
    public Collection $standards;
    public $transkasis;
    public $periodes;
    public $prodis;

    /** Per-standar stats */
    public array $perStandar = [];

    /** Flat list of all scored indicators */
    public array $semuaButir = [];

    /** Butir that are KURANG (skor < 2) */
    public array $butirKurang = [];

    public int $totalButir     = 0;
    public int $totalDinilai   = 0;
    public int $jumlahKurang   = 0;
    public int $jumlahCukup    = 0;
    public int $jumlahBaik     = 0;
    public int $jumlahBaikSekali = 0;

    public string $statusAkreditasi = 'Data Tidak Cukup';
    public string $warnaStatus      = 'gray';
    public string $keteranganStatus = '';
    public string $indikasiunggul   = '';

    public function __construct(Collection $standards, $transkasis, $periodes, $prodis)
    {
        $this->standards  = $standards;
        $this->transkasis = $transkasis;
        $this->periodes   = $periodes;
        $this->prodis     = $prodis;

        $this->compute();
    }

    protected function compute(): void
    {
        foreach ($this->standards as $standard) {
            $standarButir = [];

            foreach ($standard->elements as $element) {
                foreach ($element->indicators as $indicator) {
                    $nilai = $indicator->dokumen_nilais;
                    $skor  = $nilai !== null ? (float) ($nilai->hasil_nilai ?? 0) : null;
                    $kode  = $indicator->indikator_kode ?? '-';

                    $standarButir[] = [
                        'kode'           => $kode,
                        'nama_indikator' => $indicator->nama_indikator,
                        'skor'           => $skor,
                        'dinilai'        => $nilai !== null,
                        'level'          => $skor === null ? 'Belum Dinilai' : $this->skorLevel((float) $skor),
                        'kurang'         => $skor !== null && $skor < 2,
                    ];

                    if ($nilai !== null) {
                        $this->semuaButir[] = [
                            'standar' => $standard->nama,
                            'kode'    => $kode,
                            'nama'    => $indicator->nama_indikator,
                            'skor'    => (float) $skor,
                        ];
                    }
                }
            }

            $dinilai    = array_filter($standarButir, fn($b) => $b['dinilai']);
            $kurang     = array_filter($standarButir, fn($b) => $b['kurang']);
            $cukupPlus  = array_filter($dinilai, fn($b) => $b['skor'] >= 2);
            $baikPlus   = array_filter($dinilai, fn($b) => $b['skor'] >= 3);
            $baikSekali = array_filter($dinilai, fn($b) => $b['skor'] >= 4);

            $this->perStandar[] = [
                'nama'            => $standard->nama,
                'total_butir'     => count($standarButir),
                'dinilai'         => count($dinilai),
                'kurang'          => count($kurang),
                'cukup'           => count($cukupPlus) - count($baikPlus),
                'baik'            => count($baikPlus)  - count($baikSekali),
                'baik_sekali'     => count($baikSekali),
                'ada_kurang'      => count($kurang) > 0,
                'butir'           => $standarButir,
            ];

            foreach (array_filter($standarButir, fn($b) => $b['kurang']) as $b) {
                $this->butirKurang[] = array_merge($b, ['standar' => $standard->nama]);
            }
        }

        // Totals
        $this->totalButir   = array_sum(array_column($this->perStandar, 'total_butir'));
        $this->totalDinilai = array_sum(array_column($this->perStandar, 'dinilai'));
        $this->jumlahKurang     = array_sum(array_column($this->perStandar, 'kurang'));
        $this->jumlahCukup      = array_sum(array_column($this->perStandar, 'cukup'));
        $this->jumlahBaik       = array_sum(array_column($this->perStandar, 'baik'));
        $this->jumlahBaikSekali = array_sum(array_column($this->perStandar, 'baik_sekali'));

        $this->resolveStatus();
    }

    protected function resolveStatus(): void
    {
        if ($this->totalDinilai === 0) {
            $this->statusAkreditasi = 'Data Tidak Cukup';
            $this->warnaStatus      = 'gray';
            $this->keteranganStatus = 'Belum ada butir penilaian yang diisi.';
            return;
        }

        if ($this->jumlahKurang > 0) {
            $this->statusAkreditasi = 'Tidak Terakreditasi';
            $this->warnaStatus      = 'red';
            $this->keteranganStatus = "{$this->jumlahKurang} dari {$this->totalDinilai} butir bernilai KURANG (<2). "
                . 'Semua butir harus minimal CUKUP (≥2) untuk memperoleh status Terakreditasi.';
            return;
        }

        if ($this->totalDinilai < $this->totalButir) {
            $belum = $this->totalButir - $this->totalDinilai;
            $this->statusAkreditasi = 'Terakreditasi (Belum Lengkap)';
            $this->warnaStatus      = 'orange';
            $this->keteranganStatus = "Semua {$this->totalDinilai} butir yang sudah dinilai memenuhi minimal CUKUP (≥2). "
                . "Namun masih ada {$belum} butir yang belum dinilai.";
        } elseif ($this->jumlahBaikSekali === $this->totalDinilai) {
            $this->statusAkreditasi = 'Terakreditasi';
            $this->warnaStatus      = 'green';
            $this->keteranganStatus = "Semua {$this->totalDinilai} butir bernilai BAIK SEKALI (4). "
                . 'Indikasi kuat memenuhi syarat Terakreditasi Unggul (memerlukan instrument 35 butir terpisah).';
            $this->indikasiunggul   = 'Semua butir BAIK SEKALI — sangat berpotensi untuk Terakreditasi Unggul';
        } else {
            $sisaUnggul = $this->totalDinilai - $this->jumlahBaikSekali;
            $this->statusAkreditasi = 'Terakreditasi';
            $this->warnaStatus      = 'blue';
            $this->keteranganStatus = "Semua {$this->totalDinilai} butir ≥ CUKUP (≥2). Tidak ada yang KURANG. "
                . "Untuk status Unggul perlu instrumen terpisah (35 butir) — masih {$sisaUnggul} butir belum mencapai BAIK SEKALI (4).";
        }
    }

    protected function skorLevel(float $skor): string
    {
        return match(true) {
            $skor >= 4 => 'Baik Sekali',
            $skor >= 3 => 'Baik',
            $skor >= 2 => 'Cukup',
            default    => 'Kurang',
        };
    }

    public function render()
    {
        return view('components.hasil-forcasting-lamsama');
    }
}
