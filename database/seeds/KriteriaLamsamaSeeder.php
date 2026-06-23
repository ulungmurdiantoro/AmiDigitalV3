<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Baca file Excel LAMSAMA IAPS 3.1 (format asli dari lembaga).
 *
 * Struktur baris:
 *   - Baris judul      : dilewati (Col A berisi nama lembaga)
 *   - Baris header     : dilewati (Col A = "NO")
 *   - Baris seksi      : Col A = "A. TATA KELOLA …", "B. …", dst.
 *   - Baris indikator  : Col A = angka, Col B = teks indikator,
 *                        Col C = BAIK SEKALI, Col D = BAIK,
 *                        Col E = CUKUP,       Col F = KURANG
 *   - Baris lanjutan   : Col A kosong — lanjutan semua kolom indikator sebelumnya
 *
 * Tidak ada level "elemen" di LAMSAMA; satu Element per seksi dibuat
 * dengan nama sama dengan seksinya sebagai jembatan teknis ke Indikator.
 *
 * Info penilaian disimpan di kolom `info`:
 *   Skor 4 (BAIK SEKALI): …
 *   Skor 3 (BAIK): …
 *   Skor 2 (CUKUP): …
 *   Skor 1 (KURANG): …
 */
class KriteriaLamsamaSeeder extends Seeder
{
    protected array $files = [
        'S1-Matriks-Penilaian-Terakreditasi-IAPS-LAMSAMA-3.1.xlsx'  => 'S1',
        'D3-Matriks-Penilaian-Terakreditasi-IAPS-LAMSAMA-3.1.xlsx'  => 'D3',
        'M-Matriks-Penilaian-Terakreditasi-IAPS-LAMSAMA-3.1.xlsx'   => 'S2',
        'D-Matriks-Penilaian-Terakreditasi-IAPS-LAMSAMA-3.1.xlsx'   => 'S3',
        'STr-Matriks-Penilaian-Terakreditasi-IAPS-LAMSAMA-3.1.xlsx' => 'S1 Terapan',
    ];

    public function run(): void
    {
        $akreditasi = StandarAkreditasi::where('nama', 'LAMSAMA')->first();
        if (!$akreditasi) {
            $this->command?->warn('StandarAkreditasi "LAMSAMA" belum ada. Jalankan StandarAkreditasiSeeder dulu.');
            return;
        }

        $dir = database_path('data');

        foreach ($this->files as $filename => $jenjangNama) {
            $path = $dir . DIRECTORY_SEPARATOR . $filename;
            if (!is_file($path)) {
                $this->command?->warn("Lewati (file tidak ada): {$filename}");
                continue;
            }

            $jenjang    = Jenjang::firstOrCreate(['nama' => $jenjangNama]);
            $indicators = $this->parseSheet($path);
            $this->insertIndicators($indicators, (int) $akreditasi->id, (int) $jenjang->id, $jenjangNama);
        }
    }

    protected function parseSheet(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $sheet  = $reader->load($path)->getSheet(0);

        $indicators     = [];
        $currentSection = null;
        $current        = null;

        for ($r = 1; $r <= $sheet->getHighestDataRow(); $r++) {
            $a = $this->clean($sheet->getCell("A{$r}")->getValue());
            $b = $this->clean($sheet->getCell("B{$r}")->getValue());

            if ($a === '' && $b === '') continue;

            // Header berulang dan baris judul
            if ($a === 'NO' || $a === 'NO' || str_starts_with($a, 'LAM') || str_contains($a, 'LEMBAGA')) continue;

            // Baris seksi: A. / B. / … / F. (spasi setelah titik opsional)
            if (preg_match('/^[A-F]\./i', $a) && !is_numeric($a)) {
                $currentSection = $a;
                if ($current !== null) { $indicators[] = $current; $current = null; }
                continue;
            }

            // Baris indikator baru
            if (is_numeric($a) && $a !== '') {
                if ($current !== null) $indicators[] = $current;
                $current = [
                    'section' => $currentSection,
                    'kode'    => $a,
                    'b'       => $b,
                    'c'       => $this->clean($sheet->getCell("C{$r}")->getValue()),
                    'd'       => $this->clean($sheet->getCell("D{$r}")->getValue()),
                    'e'       => $this->clean($sheet->getCell("E{$r}")->getValue()),
                    'f'       => $this->clean($sheet->getCell("F{$r}")->getValue()),
                ];
                continue;
            }

            // Baris lanjutan (Col A kosong)
            if ($a === '' && $current !== null) {
                if ($b !== '') $current['b'] .= ' ' . $b;
                $c = $this->clean($sheet->getCell("C{$r}")->getValue());
                $d = $this->clean($sheet->getCell("D{$r}")->getValue());
                $e = $this->clean($sheet->getCell("E{$r}")->getValue());
                $f = $this->clean($sheet->getCell("F{$r}")->getValue());
                if ($c !== '') $current['c'] .= ' ' . $c;
                if ($d !== '') $current['d'] .= ' ' . $d;
                if ($e !== '') $current['e'] .= ' ' . $e;
                if ($f !== '') $current['f'] .= ' ' . $f;
            }
        }

        if ($current !== null) $indicators[] = $current;

        return $indicators;
    }

    protected function insertIndicators(array $indicators, int $akreditasiId, int $jenjangId, string $jenjangNama): void
    {
        $cStd = $cEl = $cInd = 0;
        $stdCache = [];
        $elCache  = [];

        foreach ($indicators as $ind) {
            $sectionNama = $ind['section'] ?? 'Umum';
            $teks        = trim($ind['b']);
            if ($teks === '') continue;

            // Standard
            if (!isset($stdCache[$sectionNama])) {
                $std = Standard::firstOrCreate([
                    'standar_akreditasi_id' => $akreditasiId,
                    'jenjang_id'            => $jenjangId,
                    'nama'                  => $sectionNama,
                ]);
                if ($std->wasRecentlyCreated) $cStd++;
                $stdCache[$sectionNama] = $std;
            }
            $standard = $stdCache[$sectionNama];

            // Satu Element per seksi (nama = nama seksi)
            if (!isset($elCache[$sectionNama])) {
                $el = Element::firstOrCreate([
                    'standard_id' => $standard->id,
                    'nama'        => $sectionNama,
                ]);
                if ($el->wasRecentlyCreated) $cEl++;
                $elCache[$sectionNama] = $el;
            }
            $element = $elCache[$sectionNama];

            // Info penilaian dari kolom BAIK SEKALI / BAIK / CUKUP / KURANG
            $info = $this->buildInfo($ind['c'] ?? '', $ind['d'] ?? '', $ind['e'] ?? '', $ind['f'] ?? '');

            Indikator::updateOrCreate(
                ['elemen_id' => $element->id, 'nama_indikator' => $teks],
                [
                    'indikator_kode' => $ind['kode'] ?? null,
                    'kategori'       => $sectionNama,
                    'info'           => $info ?: null,
                ]
            );
            $cInd++;
        }

        $this->command?->info("  LAMSAMA {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
    }

    protected function buildInfo(string $c, string $d, string $e, string $f): string
    {
        $parts = [];
        if ($c !== '') $parts[] = "Skor 4 (BAIK SEKALI): {$c}";
        if ($d !== '') $parts[] = "Skor 3 (BAIK): {$d}";
        if ($e !== '') $parts[] = "Skor 2 (CUKUP): {$e}";
        if ($f !== '') $parts[] = "Skor 1 (KURANG): {$f}";
        return implode("\n", $parts);
    }

    protected function clean(mixed $value): string
    {
        return preg_replace('/\s+/u', ' ', trim((string) $value));
    }
}
