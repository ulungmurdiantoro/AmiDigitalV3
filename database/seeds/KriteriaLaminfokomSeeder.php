<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Baca file Excel LAMINFOKOM (Matriks Penilaian Kinerja Program Studi).
 *
 * Struktur Excel: tiap data row didahului header row (berulang).
 *   Col A : Jenis (I/P/O) atau header "Jenis" atau "Kriteria N …"
 *   Col D : No. Urut
 *   Col H : No. Butir (1.1 A, 1.1 B, …)
 *   Col K : Bobot dari 400
 *   Col N : Elemen Penilaian LAM  → nama Elemen
 *   Col W/X: Deskriptor            → nama_indikator
 *   Dinamis : Skor 4 / 3 / 2 / 1  → info (dibaca dari header sebelum row)
 *
 * Hierarki:
 *   Standard  = "Kriteria N …" (mis. "Kriteria 1 Budaya Mutu")
 *   Elemen    = Elemen Penilaian LAM (Col N)
 *   Indikator = Deskriptor; info = Skor 4/3/2/1
 */
class KriteriaLaminfokomSeeder extends Seeder
{
    protected array $files = [
        'Diploma I - Matriks Penilaian.xlsx'        => 'D1',
        'Diploma II - Matriks Penilaian.xlsx'       => 'D2',
        'Diploma III - Matriks Penilaian.xlsx'      => 'D3',
        'Doktor - Matriks Penilaian.xlsx'           => 'S3',
        'Magister - Matriks Penilaian.xlsx'         => 'S2',
        'Magister Terapan - Matriks Penilaian.xlsx' => 'S2 Terapan',
        'Sarjana - Matriks Penilaian.xlsx'          => 'S1',
        'Sarjana Terapan - Matriks Penilaian.xlsx'  => 'S1 Terapan',
    ];

    public function run(): void
    {
        // Cari LAMINFOKOM (atau INFOKOM jika sudah ada dengan nama lama)
        $akreditasi = StandarAkreditasi::where('nama', 'LAMINFOKOM')
            ->orWhere('nama', 'INFOKOM')
            ->first()
            ?? StandarAkreditasi::create([
                'standar_akreditasis_kode' => 'AKRE-LAMINFOKOM',
                'nama'                     => 'LAMINFOKOM',
            ]);

        $dir = database_path('data/LAMINFOKOM');

        foreach ($this->files as $filename => $jenjangNama) {
            $path = $dir . DIRECTORY_SEPARATOR . $filename;
            if (!is_file($path)) {
                $this->command?->warn("Lewati (file tidak ada): {$filename}");
                continue;
            }

            $jenjang = Jenjang::firstOrCreate(['nama' => $jenjangNama]);

            $indicators = $this->parseSheet($path);

            $cntStd = $cntEl = $cntInd = 0;

            foreach ($indicators as $ind) {
                [$std, $isNewStd] = $this->upsertStandard($akreditasi, $jenjang, $ind['standard']);
                if ($isNewStd) $cntStd++;

                [$element, $isNewEl] = $this->upsertElement($std, $ind['element']);
                if ($isNewEl) $cntEl++;

                $nama = $this->clean($ind['deskriptor']);
                if ($nama === '') $nama = $this->clean($ind['element']);

                $info = $this->buildInfo(
                    $ind['skor4'] ?? '',
                    $ind['skor3'] ?? '',
                    $ind['skor2'] ?? '',
                    $ind['skor1'] ?? ''
                );

                $existing = Indikator::where('elemen_id', $element->id)->first();
                if ($existing) {
                    $existing->update([
                        'nama_indikator' => $nama,
                        'info'           => $info ?: null,
                    ]);
                } else {
                    Indikator::create([
                        'elemen_id'      => $element->id,
                        'nama_indikator' => $nama,
                        'info'           => $info ?: null,
                    ]);
                    $cntInd++;
                }
            }

            $this->command?->line(
                "  LAMINFOKOM {$jenjangNama}: +{$cntStd} standar, +{$cntEl} elemen, +{$cntInd} indikator"
            );
        }
    }

    // ─── Parse sheet ─────────────────────────────────────────────────────────────
    protected function parseSheet(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $sheet   = $reader->load($path)->getSheet(0);
        $maxRow  = $sheet->getHighestDataRow();
        $maxColL = $sheet->getHighestDataColumn();
        $maxCol  = Coordinate::columnIndexFromString($maxColL);

        // ── Baca semua sel ke array [row][col] ──────────────────────────────────
        $data = [];
        for ($r = 1; $r <= $maxRow; $r++) {
            for ($c = 1; $c <= $maxCol; $c++) {
                $v = trim((string) $sheet->getCell(
                    Coordinate::stringFromColumnIndex($c) . $r
                )->getValue());
                if ($v !== '') $data[$r][$c] = $v;
            }
        }

        // ── Deteksi kolom dari baris header (berulang setiap row) ───────────────
        // Simpan posisi kolom terakhir yang diketahui
        $colNoUrut = 4;    // No. Urut (default D=4, bisa berbeda antar file)
        $colElem   = null; // Elemen Penilaian LAM
        $colDesk   = null; // Deskriptor
        $colSk4    = null; // Skor 4
        $colSk3    = null; // Skor 3
        $colSk2    = null; // Skor 2
        $colSk1    = null; // Skor 1

        $indicators      = [];
        $currentStandard = 'Pendahuluan';
        $current         = null;

        for ($r = 1; $r <= $maxRow; $r++) {
            $row = $data[$r] ?? [];
            if (empty($row)) continue;

            $colA = $row[1] ?? '';

            // ── Footer / halaman ────────────────────────────────────────────────
            if (preg_match('/^Matriks Penilaian/i', $colA)) continue;
            if (preg_match('/^\d{1,3}$/', $colA) && count($row) <= 2) continue;

            // ── Header row (berulang) ────────────────────────────────────────────
            if (stripos($colA, 'Jenis') === 0 || $colA === 'Jenis') {
                // Baca posisi kolom dari baris ini
                foreach ($row as $ci => $v) {
                    $vl = strtolower($v);
                    if (preg_match('/no\.?\s*urut/i', $v))     $colNoUrut = $ci;
                    if (str_contains($vl, 'elemen penilaian')) $colElem   = $ci;
                    if (str_contains($vl, 'deskriptor'))       $colDesk   = $ci;
                    if (str_contains($vl, 'sangat baik'))      $colSk4    = $ci;
                    if (preg_match('/baik\s*=\s*3/i', $v))     $colSk3    = $ci;
                    if (str_contains($vl, 'cukup'))            $colSk2    = $ci;
                    if (str_contains($vl, 'kurang'))           $colSk1    = $ci;
                }
                continue;
            }

            // ── Kriteria section header ──────────────────────────────────────────
            if (preg_match('/^Kriteria\s+\d+/i', $colA) && count($row) <= 3) {
                $this->saveIndicator($current, $indicators);
                $current         = null;
                $currentStandard = trim($colA);
                continue;
            }

            // ── Data row (No. Urut = angka) ──────────────────────────────────────
            $colD = $row[$colNoUrut] ?? '';
            if (is_numeric($colD) || preg_match('/^\d+$/', $colD)) {
                $this->saveIndicator($current, $indicators);

                $current = [
                    'standard'   => $currentStandard,
                    'element'    => $this->cleanText($colElem ? ($row[$colElem] ?? '') : ($row[14] ?? '')),
                    'deskriptor' => $colDesk ? $this->cleanText($row[$colDesk] ?? '') : '',
                    'skor4'      => $colSk4  ? $this->clean($row[$colSk4]  ?? '') : '',
                    'skor3'      => $colSk3  ? $this->clean($row[$colSk3]  ?? '') : '',
                    'skor2'      => $colSk2  ? $this->clean($row[$colSk2]  ?? '') : '',
                    'skor1'      => $colSk1  ? $this->clean($row[$colSk1]  ?? '') : '',
                ];
                continue;
            }

            // ── Continuation row (hanya kolom skor berisi data) ──────────────────
            if ($current !== null && ($row[$colNoUrut] ?? '') === '') {
                if ($colSk4  && isset($row[$colSk4]))  $current['skor4']      .= ' ' . $row[$colSk4];
                if ($colSk3  && isset($row[$colSk3]))  $current['skor3']      .= ' ' . $row[$colSk3];
                if ($colSk2  && isset($row[$colSk2]))  $current['skor2']      .= ' ' . $row[$colSk2];
                if ($colSk1  && isset($row[$colSk1]))  $current['skor1']      .= ' ' . $row[$colSk1];
                if ($colDesk && isset($row[$colDesk])) $current['deskriptor'] .= ' ' . $row[$colDesk];
                if ($colElem && isset($row[$colElem])) $current['element']    .= ' ' . $row[$colElem];
            }
        }
        $this->saveIndicator($current, $indicators);
        return $indicators;
    }

    protected function saveIndicator(?array $current, array &$list): void
    {
        if ($current === null) return;
        if ($this->clean($current['element']) === '') return;
        $list[] = $current;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────
    protected function clean(string $v): string
    {
        return trim(preg_replace('/\s+/', ' ', $v));
    }

    protected function cleanText(string $v): string
    {
        $v = preg_replace('/\s+/', ' ', $v);
        return trim($v);
    }

    protected function buildInfo(string $s4, string $s3, string $s2, string $s1): string
    {
        $parts = [];
        $s4 = $this->clean($s4);
        $s3 = $this->clean($s3);
        $s2 = $this->clean($s2);
        $s1 = $this->clean($s1);
        if ($s4 !== '') $parts[] = "Skor 4 (SANGAT BAIK) :\n" . $this->formatText($s4);
        if ($s3 !== '') $parts[] = "Skor 3 (BAIK) :\n"        . $this->formatText($s3);
        if ($s2 !== '') $parts[] = "Skor 2 (CUKUP) :\n"       . $this->formatText($s2);
        if ($s1 !== '') $parts[] = "Skor 1 (KURANG) :\n"      . $this->formatText($s1);
        return implode("\n", $parts);
    }

    protected function formatText(string $text): string
    {
        $text = preg_replace('/[ \t]+(\([a-z]\))/u',  "\n$1", $text);
        $text = preg_replace('/[ \t]+([a-z]\))/u',    "\n$1", $text);
        $text = preg_replace('/[ \t]+(\(\d+\))/u',    "\n$1", $text);
        $text = preg_replace('/[ \t]+(\d+\.)/u',      "\n$1", $text);
        return trim($text);
    }

    // ─── Upsert helpers ───────────────────────────────────────────────────────────
    protected function upsertStandard(StandarAkreditasi $akr, Jenjang $jenjang, string $nama): array
    {
        $nama = $this->clean($nama);
        $existing = Standard::where('standar_akreditasi_id', $akr->id)
            ->where('jenjang_id', $jenjang->id)
            ->where('nama', $nama)
            ->first();
        if ($existing) return [$existing, false];
        return [Standard::create([
            'standar_akreditasi_id' => $akr->id,
            'jenjang_id'            => $jenjang->id,
            'nama'                  => $nama,
        ]), true];
    }

    protected function upsertElement(Standard $std, string $nama): array
    {
        $nama = $this->clean($nama);
        if ($nama === '') $nama = '(tidak ada)';
        $existing = Element::where('standard_id', $std->id)->where('nama', $nama)->first();
        if ($existing) return [$existing, false];
        return [Element::create(['standard_id' => $std->id, 'nama' => $nama]), true];
    }
}
