<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class HasilForcastingLamteknik extends Component
{
    public Collection $standards;
    public $transkasis;
    public $periodes;
    public $prodis;

    public float  $naTotal    = 0.0;
    public array  $perStandar = [];

    // Computed from AMI scores
    public float $rerataAkuntabilitas  = 0.0; // maps to Tata Pamong syarat
    public float $rerataRelevansi      = 0.0; // maps to Kurikulum syarat
    public float $rerataSpmi           = 0.0; // maps to SPMI syarat

    // Syarat Terakreditasi (rerata ≥ 2.00 for Tata Pamong, Kurikulum, SPMI)
    public bool $syaratTerAkuntabilitas = false;
    public bool $syaratTerRelevansi     = false;
    public bool $syaratTerSpmi          = false;
    public bool $syaratTerakreditasi    = false;

    // Syarat Unggul ** (3 tahun: rerata ≥ 3.00)
    public bool $syaratUnggul3Akuntabilitas = false;
    public bool $syaratUnggul3Relevansi     = false;
    public bool $syaratUnggul3Spmi          = false;
    public bool $syaratUnggul3Terpenuhi     = false;

    // Syarat Unggul *** (5 tahun: rerata ≥ 3.50)
    public bool $syaratUnggul5Akuntabilitas = false;
    public bool $syaratUnggul5Relevansi     = false;
    public bool $syaratUnggul5Spmi          = false;
    public bool $syaratUnggul5Terpenuhi     = false;

    public string $statusAkreditasi = 'Data Tidak Cukup';
    public string $durasiAkreditasi = '-';
    public string $warnaStatus      = 'gray';

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
        $naTotal = 0.0;

        foreach ($this->standards as $standard) {
            $skorList = [];

            foreach ($standard->elements as $element) {
                foreach ($element->indicators as $indicator) {
                    $nilai = $indicator->dokumen_nilais;
                    if ($nilai === null) continue;

                    $skor  = (float) ($nilai->hasil_nilai ?? 0);
                    $bobot = (float) ($nilai->bobot ?? $indicator->bobot ?? 1);
                    $naTotal += $skor * $bobot;
                    $skorList[] = $skor;
                }
            }

            $rerata = count($skorList) > 0
                ? round(array_sum($skorList) / count($skorList), 2)
                : 0.0;

            $this->perStandar[] = [
                'nama'         => $standard->nama,
                'jumlah_butir' => count($skorList),
                'rerata'       => $rerata,
            ];

            // Map standard to syarat category by keyword
            $nama = strtolower($standard->nama);
            if (str_contains($nama, 'akuntabilitas')) {
                $this->rerataAkuntabilitas = $rerata;
            } elseif (str_contains($nama, 'relevansi')) {
                $this->rerataRelevansi = $rerata;
            } elseif (str_contains($nama, 'penjaminan mutu')) {
                $this->rerataSpmi = $rerata;
            }
        }

        $this->naTotal = round($naTotal, 2);
        $this->resolveFlags();
    }

    protected function resolveFlags(): void
    {
        // Syarat Terakreditasi (rerata ≥ 2.00)
        $this->syaratTerAkuntabilitas = $this->rerataAkuntabilitas >= 2.0;
        $this->syaratTerRelevansi     = $this->rerataRelevansi     >= 2.0;
        $this->syaratTerSpmi          = $this->rerataSpmi          >= 2.0;
        $this->syaratTerakreditasi    = $this->syaratTerAkuntabilitas
                                     && $this->syaratTerRelevansi
                                     && $this->syaratTerSpmi;

        // Syarat Unggul ** (rerata ≥ 3.00)
        $this->syaratUnggul3Akuntabilitas = $this->rerataAkuntabilitas >= 3.0;
        $this->syaratUnggul3Relevansi     = $this->rerataRelevansi     >= 3.0;
        $this->syaratUnggul3Spmi          = $this->rerataSpmi          >= 3.0;
        $this->syaratUnggul3Terpenuhi     = $this->syaratUnggul3Akuntabilitas
                                          && $this->syaratUnggul3Relevansi
                                          && $this->syaratUnggul3Spmi;

        // Syarat Unggul *** (rerata ≥ 3.50)
        $this->syaratUnggul5Akuntabilitas = $this->rerataAkuntabilitas >= 3.5;
        $this->syaratUnggul5Relevansi     = $this->rerataRelevansi     >= 3.5;
        $this->syaratUnggul5Spmi          = $this->rerataSpmi          >= 3.5;
        $this->syaratUnggul5Terpenuhi     = $this->syaratUnggul5Akuntabilitas
                                          && $this->syaratUnggul5Relevansi
                                          && $this->syaratUnggul5Spmi;

        $this->resolveStatus();
    }

    protected function resolveStatus(): void
    {
        $na = $this->naTotal;

        if ($na < 200 || !$this->syaratTerakreditasi) {
            $this->statusAkreditasi = 'Tidak Terakreditasi';
            $this->durasiAkreditasi = '-';
            $this->warnaStatus      = 'red';
            return;
        }

        if ($na >= 361 && $this->syaratUnggul3Terpenuhi && $this->syaratUnggul5Terpenuhi) {
            $this->statusAkreditasi = 'Terakreditasi Unggul';
            $this->durasiAkreditasi = '5 Tahun';
            $this->warnaStatus      = 'green';
            return;
        }

        if ($na >= 361 && $this->syaratUnggul3Terpenuhi) {
            $this->statusAkreditasi = 'Terakreditasi Unggul';
            $this->durasiAkreditasi = '3 Tahun';
            $this->warnaStatus      = 'green';
            return;
        }

        if ($na >= 331 && $this->syaratUnggul3Terpenuhi) {
            $this->statusAkreditasi = 'Terakreditasi Unggul';
            $this->durasiAkreditasi = '3 Tahun';
            $this->warnaStatus      = 'green';
            return;
        }

        $this->statusAkreditasi = 'Terakreditasi';
        $this->durasiAkreditasi = '5 Tahun';
        $this->warnaStatus      = 'blue';
    }

    public function render()
    {
        return view('components.hasil-forcasting-lamteknik');
    }
}
