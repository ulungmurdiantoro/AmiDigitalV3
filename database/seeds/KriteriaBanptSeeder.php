<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KriteriaBanptSeeder extends Seeder
{
    /**
     * File Excel (di database/data/) => nama jenjang.
     * Semua file ini instrumen BAN-PT IAPTS 5.1 (PerBAN-PT 36/2025).
     *
     * Tiap file punya 2 sheet dgn daftar indikator yang sama:
     *   sheet 0 "TERAKREDITASI"          -> kolom H = deskripsi pemenuhan utk skor 1 (Terakreditasi)
     *   sheet 1 "TERAKREDITASI UNGGUL"   -> kolom H = deskripsi pemenuhan utk skor 2 (Terakreditasi Unggul)
     * Skor 0 (Tidak Terakreditasi) = tidak memenuhi syarat skor 1.
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
        $wb = $reader->load($path);

        $sheetT = $wb->getSheet(0);                                  // TERAKREDITASI (skor 1)
        $sheetU = $wb->getSheetCount() > 1 ? $wb->getSheet(1) : null; // TERAKREDITASI UNGGUL (skor 2)

        $headerRow = $this->findHeaderRow($sheetT);
        if (!$headerRow) {
            $this->command?->warn("Header 'Kriteria' tidak ditemukan di {$jenjangNama}, dilewati.");
            return;
        }

        // Peta deskripsi pemenuhan "Unggul" (skor 2): key = butir|indikator -> teks (kolom H sheet UNGGUL).
        $unggulMap = $sheetU ? $this->buildUnggulMap($sheetU) : [];

        $highestRow = $sheetT->getHighestDataRow();
        $kriteria = $sasaran = $butir = null;
        $stdCache = [];
        $elCache = [];
        $cStd = $cEl = $cInd = 0;

        for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
            $a = trim((string) $sheetT->getCell("A{$r}")->getValue());
            $b = trim((string) $sheetT->getCell("B{$r}")->getValue());
            $d = trim((string) $sheetT->getCell("D{$r}")->getValue());
            $e = trim((string) $sheetT->getCell("E{$r}")->getValue());
            $f = trim((string) $sheetT->getCell("F{$r}")->getValue()); // Aspek Penilaian / Deskriptor
            $h = trim((string) $sheetT->getCell("H{$r}")->getValue()); // Deskripsi Pemenuhan (skor 1)

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

            // Deskripsi pemenuhan "Unggul" (skor 2) dari sheet UNGGUL.
            // Kedua sheet cermin baris-per-baris (header & urutan butir sama), jadi ambil H pada
            // baris yang sama; fallback ke peta butir|indikator bila baris itu kebetulan kosong.
            $hUnggul = '';
            if ($sheetU) {
                $hUnggul = trim((string) $sheetU->getCell("H{$r}")->getValue());
                if ($hUnggul === '') {
                    $hUnggul = $unggulMap[($butir ?? '') . '|' . $this->normalize($d)] ?? '';
                }
            }
            $info = $this->composeInfo($f, $h, $hUnggul);

            $ind = Indikator::updateOrCreate(
                ['elemen_id' => $element->id, 'nama_indikator' => $d],
                [
                    'indikator_kode' => $kode !== '' ? $kode : null,
                    'kategori'       => $sasaranNama,
                    'info'           => $info !== '' ? $info : null,
                ]
            );
            if ($ind->wasRecentlyCreated) $cInd++;
        }

        $this->command?->info("  {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, {$cInd} indikator baru (info diperbarui)");
    }

    /** Cari baris header (kolom A == "Kriteria"). */
    protected function findHeaderRow(Worksheet $sheet): ?int
    {
        $max = min(40, $sheet->getHighestDataRow());
        for ($r = 1; $r <= $max; $r++) {
            if (strcasecmp(trim((string) $sheet->getCell("A{$r}")->getValue()), 'Kriteria') === 0) {
                return $r;
            }
        }
        return null;
    }

    /** Peta deskripsi pemenuhan "Unggul" dari sheet UNGGUL: key = "butir|indikator" -> kolom H. */
    protected function buildUnggulMap(Worksheet $sheet): array
    {
        $map = [];
        $headerRow = $this->findHeaderRow($sheet);
        if (!$headerRow) {
            return $map;
        }
        $butir = null;
        $highestRow = $sheet->getHighestDataRow();
        for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
            $e = trim((string) $sheet->getCell("E{$r}")->getValue());
            $d = trim((string) $sheet->getCell("D{$r}")->getValue());
            $h = trim((string) $sheet->getCell("H{$r}")->getValue());
            if ($e !== '') $butir = $e;
            if ($d === '' || $h === '') continue;
            $map[($butir ?? '') . '|' . $this->normalize($d)] = $h;
        }
        return $map;
    }

    /** Normalisasi teks utk pencocokan (rapikan whitespace agar beda sepele tidak menggagalkan match). */
    protected function normalize(string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * Susun isi kolom `info`: Aspek Penilaian + Deskripsi Pemenuhan Indikator per skor (2/1/0).
     */
    protected function composeInfo(string $aspek, string $hTerakreditasi, string $hUnggul): string
    {
        $parts = [];

        if ($aspek !== '') {
            $parts[] = 'Aspek Penilaian Indikator :' . "\n" . $aspek;
        }

        $skor = [];
        if ($hUnggul !== '') {
            $skor[] = "Skor 2 (Terakreditasi Unggul) :\n" . $this->stripSyarat($hUnggul);
        }
        if ($hTerakreditasi !== '') {
            $skor[] = "Skor 1 (Terakreditasi) :\n" . $this->stripSyarat($hTerakreditasi);
        }
        $skor[] = "Skor 0 (Tidak Terakreditasi) :\nTidak memenuhi syarat Terakreditasi.";

        $parts[] = 'Deskripsi Pemenuhan Indikator :' . "\n" . implode("\n", $skor);

        return trim(implode("\n\n", $parts));
    }

    /** Buang baris penutup "(Syarat perlu status ...)" yang redundan dgn label skor. */
    protected function stripSyarat(string $text): string
    {
        // Buang penanda "(Syarat perlu status ...)" / "Syarat perlu status ..." (redundan dgn label "Skor N").
        // Bisa berupa baris tersendiri ATAU menempel di akhir baris deskripsi.
        $out = [];
        foreach (preg_split('/\R/u', $text) ?: [] as $ln) {
            if (preg_match('/^\s*\(?\s*Syarat perlu status/iu', $ln)) {
                continue; // baris penanda mandiri
            }
            $ln = preg_replace('/\s*\(?\s*Syarat perlu status[^)\n]*\)?\s*$/iu', '', $ln); // penanda di akhir baris
            $out[] = $ln;
        }
        $res = preg_replace("/\n{3,}/", "\n\n", implode("\n", $out));
        return trim($res);
    }
}
