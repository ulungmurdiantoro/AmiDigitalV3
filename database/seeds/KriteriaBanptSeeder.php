<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KriteriaBanptSeeder extends Seeder
{
    /**
     * File Excel (di database/data/) => nama jenjang.
     * Semua file ini instrumen BAN-PT IAPTS 5.1 (PerBAN-PT 36/2025).
     */
    protected array $files = [
        'Lampiran 3a PerBAN-PT 36 2025 IAPTS 5.1 - Diploma 1.xlsx' => 'D1',
        'Lampiran 3b PerBAN-PT 36 2025 IAPTS 5.1 - Diploma 2.xlsx' => 'D2',
        'Lampiran 3c PerBAN-PT 36 2025 IAPTS 5.1 - Diploma 3.xlsx' => 'D3',
        'Lampiran 3d PerBAN-PT 36 2025 IAPTS 5.1 - STr.xlsx'       => 'S1 Terapan',
        'Lampiran 3e PerBAN-PT 36 2025 IAPTS 5.1 - MTr.xlsx'       => 'S2 Terapan',
        'Lampiran 3f PerBAN-PT 36 2025 IAPTS 5.1 - DTr.xlsx'       => 'S3 Terapan',
        'Lampiran 3g PerBAN-PT 36 2025 IAPTS 5.1 - Sarjana.xlsx'   => 'S1',
    ];

    public function run(): void
    {
        $akreditasi = StandarAkreditasi::where('nama', 'BAN-PT')->first();
        if (!$akreditasi) {
            $this->command?->warn('StandarAkreditasi "BAN-PT" belum ada. Jalankan StandarAkreditasiSeeder dulu. Dilewati.');
            return;
        }

        $dir = database_path('data');

        foreach ($this->files as $file => $jenjangNama) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($path)) {
                $this->command?->warn("Lewati (file tidak ada): {$file}");
                continue;
            }

            $jenjang = Jenjang::firstOrCreate(['nama' => $jenjangNama]);
            $this->importFile($path, (int) $akreditasi->id, (int) $jenjang->id, $jenjangNama);
        }
    }

    protected function importFile(string $path, int $akreditasiId, int $jenjangId, string $jenjangNama): void
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($path)->getSheet(0); // sheet "TERAKREDITASI" (daftar indikator sama dgn sheet UNGGUL)

        $highestRow = $sheet->getHighestDataRow();

        // Cari baris header (kolom A == "Kriteria").
        $headerRow = null;
        for ($r = 1; $r <= min(40, $highestRow); $r++) {
            if (strcasecmp(trim((string) $sheet->getCell("A{$r}")->getValue()), 'Kriteria') === 0) {
                $headerRow = $r;
                break;
            }
        }
        if (!$headerRow) {
            $this->command?->warn("Header 'Kriteria' tidak ditemukan di {$jenjangNama}, dilewati.");
            return;
        }

        $kriteria = $sasaran = $butir = null;
        $stdCache = [];
        $elCache = [];
        $cStd = $cEl = $cInd = 0;

        for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
            $a = trim((string) $sheet->getCell("A{$r}")->getValue());
            $b = trim((string) $sheet->getCell("B{$r}")->getValue());
            $d = trim((string) $sheet->getCell("D{$r}")->getValue());
            $e = trim((string) $sheet->getCell("E{$r}")->getValue());
            $f = trim((string) $sheet->getCell("F{$r}")->getValue());

            // Isi ke bawah (sel merged hanya terisi di baris pertama blok).
            if ($a !== '') $kriteria = $a;
            if ($b !== '') $sasaran = $b;
            if ($e !== '') $butir = $e;

            if ($d === '' || $kriteria === null) {
                continue; // bukan baris indikator
            }

            $sasaranNama = ($sasaran !== null && $sasaran !== '') ? $sasaran : 'Umum';

            $stdKey = $kriteria;
            if (!isset($stdCache[$stdKey])) {
                $std = Standard::firstOrCreate([
                    'standar_akreditasi_id' => $akreditasiId,
                    'jenjang_id'            => $jenjangId,
                    'nama'                  => $kriteria,
                ]);
                if ($std->wasRecentlyCreated) $cStd++;
                $stdCache[$stdKey] = $std;
            }
            $standard = $stdCache[$stdKey];

            $elKey = $standard->id . '|' . $sasaranNama;
            if (!isset($elCache[$elKey])) {
                $el = Element::firstOrCreate([
                    'standard_id' => $standard->id,
                    'nama'        => $sasaranNama,
                ]);
                if ($el->wasRecentlyCreated) $cEl++;
                $elCache[$elKey] = $el;
            }
            $element = $elCache[$elKey];

            // Kode indikator = nomor butir (+ huruf sub-aspek bila D diawali "A.", "B." dst).
            $kode = (string) ($butir ?? '');
            if (preg_match('/^([A-Z])\.\s/u', $d, $m)) {
                $kode = trim(($butir ?? '') . $m[1]);
            }

            $ind = Indikator::firstOrCreate(
                ['elemen_id' => $element->id, 'nama_indikator' => $d],
                [
                    'indikator_kode' => $kode !== '' ? $kode : null,
                    'kategori'       => $sasaranNama,
                    'info'           => $f !== '' ? $f : null,
                ]
            );
            if ($ind->wasRecentlyCreated) $cInd++;
        }

        $this->command?->info("  {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
    }
}
