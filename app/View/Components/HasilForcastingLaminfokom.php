<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class HasilForcastingLaminfokom extends Component
{
    public Collection $standards;
    public $transkasis;
    public $periodes;
    public $prodis;

    /** Computed per-kriteria stats */
    public array $perKriteria = [];

    /** Nilai Akhir total */
    public float $naTotal = 0.0;

    /** Status akreditasi */
    public string $statusAkreditasi  = 'Tidak Terakreditasi';
    public string $durasiAkreditasi  = '-';
    public string $warnaStatus       = 'red';

    /** Syarat Unggul flags per kriteria */
    public bool $syaratRerataOk = false;
    public bool $syaratMinOk    = false;

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
        $naTotal    = 0.0;
        $allMin     = [];
        $allRerata  = [];

        foreach ($this->standards as $standard) {
            $naKriteria     = 0.0;
            $bobotKriteria  = 0.0;
            $skorList       = [];

            foreach ($standard->elements as $element) {
                foreach ($element->indicators as $indicator) {
                    $nilai = $indicator->dokumen_nilais;
                    if ($nilai === null) continue;

                    $skor  = (float) ($nilai->hasil_nilai ?? 0);
                    $bobot = (float) ($nilai->bobot ?? $indicator->bobot ?? 0);

                    $naKriteria    += $skor * ($bobot / 4.0);
                    $bobotKriteria += $bobot;
                    $skorList[]     = $skor;
                }
            }

            $jumlahInd = count($skorList);
            $rerata    = $jumlahInd > 0 ? array_sum($skorList) / $jumlahInd : 0.0;
            $minSkor   = $jumlahInd > 0 ? min($skorList) : 0.0;

            $this->perKriteria[] = [
                'nama'          => $standard->nama,
                'bobot_total'   => round($bobotKriteria, 1),
                'na_kriteria'   => round($naKriteria, 2),
                'rerata'        => round($rerata, 2),
                'min_skor'      => round($minSkor, 2),
                'jumlah_butir'  => $jumlahInd,
                'rerata_ok'     => $rerata >= 3.20,
                'min_ok'        => $minSkor >= 3.00,
            ];

            $naTotal    += $naKriteria;
            $allMin[]    = $minSkor;
            $allRerata[] = $rerata;
        }

        $this->naTotal = round($naTotal, 2);

        // Semua kriteria harus rerata ≥ 3.20 dan min ≥ 3.00 untuk syarat Unggul
        $this->syaratRerataOk = count($allRerata) > 0 && min($allRerata) >= 3.20;
        $this->syaratMinOk    = count($allMin)    > 0 && min($allMin)    >= 3.00;

        $this->resolveStatus();
    }

    protected function resolveStatus(): void
    {
        $na         = $this->naTotal;
        $rerataOk   = $this->syaratRerataOk;
        $minOk      = $this->syaratMinOk;

        if ($na < 200) {
            $this->statusAkreditasi = 'Tidak Terakreditasi';
            $this->durasiAkreditasi = '-';
            $this->warnaStatus      = 'red';
        } elseif ($na < 321) {
            $this->statusAkreditasi = 'Terakreditasi';
            $this->durasiAkreditasi = '-';
            $this->warnaStatus      = 'orange';
        } elseif ($na < 361) {
            if ($rerataOk && $minOk) {
                $this->statusAkreditasi = 'Unggul';
                $this->durasiAkreditasi = '3 Tahun';
                $this->warnaStatus      = 'green';
            } else {
                $this->statusAkreditasi = 'Terakreditasi';
                $this->durasiAkreditasi = '-';
                $this->warnaStatus      = 'orange';
            }
        } else {
            if ($rerataOk && $minOk) {
                $this->statusAkreditasi = 'Unggul';
                $this->durasiAkreditasi = '5 Tahun';
                $this->warnaStatus      = 'green';
            } else {
                $this->statusAkreditasi = 'Terakreditasi';
                $this->durasiAkreditasi = '-';
                $this->warnaStatus      = 'orange';
            }
        }
    }

    public function render()
    {
        return view('components.hasil-forcasting-laminfokom');
    }
}
