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
 * Baca file Excel LAMTEKNIK (format asli LAM Teknik 2025).
 *
 * Struktur kolom (header row: No | Kriteria | Indikator | 4 | 3 | 2 | 1 | 0):
 *   Col A : nomor indikator, header section (I., II., …), label IKU, sub-section (2.1…)
 *   Col B : Kriteria → nama Elemen
 *   Col D : Indikator → nama_indikator (full text)
 *   Col F : deskripsi Skor 4
 *   Col G : deskripsi Skor 3
 *   Col H : deskripsi Skor 2
 *   Col I : deskripsi Skor 1
 *   Col J : deskripsi Skor 0
 *
 * Hierarki:
 *   Standard  = baris label IKU, mis. "Visi, Misi Tujuan dan Sasaran (Indikator Kinerja Utama)"
 *   Elemen    = Col B (Kriteria); fallback ke sub-section bila B kosong
 *   Indikator = Col D; info dari F/G/H/I/J
 */
class KriteriaLamteknikSeeder extends Seeder
{
    protected array $files = [
        // Reguler (LED + LKPS)
        'matrik-penilaian-led-dan-lkps-sarjana-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'            => 'S1',
        'matrik-penilaian-led-dan-lkps-sarjana-terapan-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'    => 'S1 Terapan',
        'matrik-penilaian-led-dan-lkps-magister-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'           => 'S2',
        'matrik-penilaian-led-dan-lkps-magister-terapan-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'   => 'S2 Terapan',
        'matrik-penilaian-led-dan-lkps-doktor-aps-akademik-dan-vokasi-teknik-2025.xlsx'                                                       => 'S3',
        'matrik-penilaian-led-dan-lkps-doktor-terapan-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'     => 'S3 Terapan',
        'matrik-penilaian-led-dan-lkps-diploma-i-aps-akademik-dan-vokasi-teknik-2025untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'           => 'D1',
        'matrik-penilaian-led-dan-lkps-diploma-ii-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx'         => 'D2',
        'matrik-penilaian-led-dan-lkps-diploma-iii-aps-akademik-dan-vokasi-teknik-2025.xlsx'                                                  => 'D3',
        'matrik-penilaian-led-dan-lkps-program-profesi-insinyur-aps-akademik-dan-vokasi-teknik-2025-untuk-pasca-akreditasi-pertama-dan-unggul.xlsx' => 'Profesi Insinyur',
        'matrik-penilaian-led-dan-lkps-unggul-internasional-sarjana-aps-akademik-dan-vokasi-2025.xlsx'                                        => 'S1 Unggul Internasional',
        'matrik-penilaian-led-dan-lkps-unggul-internasional-sarjana-terapan-aps-av-lam-teknik-2025.xlsx'                                     => 'S1 Terapan Unggul Internasional',
        // Perpanjangan (LKPS saja)
        'matrik-penilaian-lkps-sarjana-perpanjangan-aps-akademik-dan-vokasi.xlsx'                => 'S1 Perpanjangan',
        'matrik-penilaian-lkps-sarjana-terapan-perpanjangan-aps-akademik-&-vokasi.xlsx'          => 'S1 Terapan Perpanjangan',
        'matrik-penilaian-lkps-magister-perpanjangan-aps-akademik-&-vokasi.xlsx'                 => 'S2 Perpanjangan',
        'matrik-penilaian-lkps-magister-terapan-perpanjangan-aps-akademik-&-vokasi.xlsx'         => 'S2 Terapan Perpanjangan',
        'matrik-penilaian-lkps-doktor-perpanjangan-aps-akademik-&-vokasi.xlsx'                   => 'S3 Perpanjangan',
        'matrik-penilaian-lkps-doktor-terapan-perpanjangan-aps-akademik-&-vokasi.xlsx'           => 'S3 Terapan Perpanjangan',
        'matrik-penilaian-lkps-diploma-satu-perpanjangan-aps-akademik-dan-vokasi.xlsx'           => 'D1 Perpanjangan',
        'matrik-penilaian-lkps-diploma-dua-perpanjangan-aps-akademik-dan-vokasi.xlsx'            => 'D2 Perpanjangan',
        'matrik-penilaian-lkps-diploma-tiga-perpanjangan-aps-akademik-dan-vokasi.xlsx'           => 'D3 Perpanjangan',
        'matrik-penilaian-lkps-profesi-insinyur-perpanjangan-aps-akademik-&-vokasi.xlsx'         => 'Profesi Insinyur Perpanjangan',
    ];

    public function run(): void
    {
        $akreditasi = StandarAkreditasi::where('nama', 'LAMTEKNIK')->first();
        if (!$akreditasi) {
            $this->command?->warn('StandarAkreditasi "LAMTEKNIK" belum ada. Jalankan StandarAkreditasiSeeder dulu.');
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
        $sheet = $reader->load($path)->getSheet(0);

        // Deteksi apakah file punya kolom D "Indikator" (LED+LKPS) atau tidak (perpanjangan).
        $hasIndicatorCol = false;
        $highestRow      = $sheet->getHighestDataRow();
        for ($r = 1; $r <= min(20, $highestRow); $r++) {
            if ($this->clean($sheet->getCell("A{$r}")->getValue()) === 'No') {
                $d = $this->clean($sheet->getCell("D{$r}")->getValue());
                $hasIndicatorCol = stripos($d, 'Indikator') !== false;
                break;
            }
        }

        $indicators      = [];
        $currentStandard = null;
        $currentSubSect  = null;
        $current         = null;

        for ($r = 1; $r <= $highestRow; $r++) {
            $a = $this->clean($sheet->getCell("A{$r}")->getValue());
            $b = $this->clean($sheet->getCell("B{$r}")->getValue());

            if ($a === '' && $b === '') continue;

            // Baris header berulang (No | Kriteria | …)
            if ($a === 'No') continue;

            // Baris label IKU → Standard
            if (str_contains($a, 'Indikator Kinerja')) {
                $this->saveIndicator($current, $indicators);
                $current         = null;
                $currentStandard = $a;
                $currentSubSect  = null;
                continue;
            }

            // Section Roman numeral (I., II., …) atau "A. KRITERIA" → lewati
            if (preg_match('/^(?:[A-C]\.|[IVX]+\.)\s*/u', $a) && !is_numeric($a)) {
                continue;
            }

            // Sub-section bernomor (2.1, 3.1, …)
            if (preg_match('/^\d+\.\d+/u', $a)) {
                $this->saveIndicator($current, $indicators);
                $current        = null;
                $currentSubSect = $a;
                continue;
            }

            if ($currentStandard === null) continue;

            // Baris indikator baru (Col A = angka)
            if (is_numeric($a) && $a !== '') {
                $this->saveIndicator($current, $indicators);

                $elemenRaw = preg_replace('/\s*Skor\s*=\s*[^\n]+/iu', '', $b);
                $elemen    = $this->clean($elemenRaw);

                if ($hasIndicatorCol) {
                    // File LED+LKPS: D = nama_indikator, B = elemen
                    $d = $this->clean($sheet->getCell("D{$r}")->getValue());
                    if ($elemen === '') $elemen = $currentSubSect ?? $currentStandard;
                    $current = [
                        'standard' => $currentStandard,
                        'element'  => $elemen,
                        'kode'     => $a,
                        'd'        => $this->cleanText($d),
                        'f'        => $this->clean($sheet->getCell("F{$r}")->getValue()),
                        'g'        => $this->clean($sheet->getCell("G{$r}")->getValue()),
                        'h'        => $this->clean($sheet->getCell("H{$r}")->getValue()),
                        'i'        => $this->clean($sheet->getCell("I{$r}")->getValue()),
                        'j'        => $this->clean($sheet->getCell("J{$r}")->getValue()),
                    ];
                } else {
                    // File perpanjangan: B = nama_indikator, sub-section/standard = elemen
                    if ($elemen === '') $elemen = $currentSubSect ?? $currentStandard;
                    $namaInd = $elemen; // nama dari Col B sudah diambil ke $elemen
                    // Ambil nama indikator dari B yang sudah dibersihkan
                    $namaInd = $this->clean(preg_replace('/\s*Skor\s*=\s*[^\n]+/iu', '', $b));
                    $current = [
                        'standard' => $currentStandard,
                        'element'  => $currentSubSect ?? $currentStandard,
                        'kode'     => $a,
                        'd'        => $namaInd !== '' ? $namaInd : ($currentSubSect ?? $currentStandard),
                        'f' => '', 'g' => '', 'h' => '', 'i' => '', 'j' => '',
                    ];
                }
                continue;
            }

            // Baris lanjutan (Col A kosong)
            if ($a === '' && $current !== null) {
                if ($hasIndicatorCol) {
                    $d = $this->clean($sheet->getCell("D{$r}")->getValue());
                    $f = $this->clean($sheet->getCell("F{$r}")->getValue());
                    $g = $this->clean($sheet->getCell("G{$r}")->getValue());
                    $h = $this->clean($sheet->getCell("H{$r}")->getValue());
                    $i = $this->clean($sheet->getCell("I{$r}")->getValue());
                    $j = $this->clean($sheet->getCell("J{$r}")->getValue());
                    if ($d !== '') $current['d'] .= ' ' . $this->cleanText($d);
                    if ($f !== '') $current['f'] .= ' ' . $f;
                    if ($g !== '') $current['g'] .= ' ' . $g;
                    if ($h !== '') $current['h'] .= ' ' . $h;
                    if ($i !== '') $current['i'] .= ' ' . $i;
                    if ($j !== '') $current['j'] .= ' ' . $j;
                } else {
                    // Perpanjangan: lanjutan nama indikator di Col B
                    if ($b !== '') $current['d'] .= ' ' . $this->clean(preg_replace('/\s*Skor\s*=\s*[^\n]+/iu', '', $b));
                }
            }
        }

        $this->saveIndicator($current, $indicators);
        return $indicators;
    }

    protected function saveIndicator(?array &$current, array &$indicators): void
    {
        if ($current === null) return;
        $teks = trim($current['d'] ?? '');
        if ($teks !== '') {
            $current['d'] = $teks;
            $indicators[] = $current;
        }
        $current = null;
    }

    protected function insertIndicators(array $indicators, int $akreditasiId, int $jenjangId, string $jenjangNama): void
    {
        $cStd = $cEl = $cInd = 0;
        $stdCache = [];
        $elCache  = [];

        foreach ($indicators as $ind) {
            $standardNama = $ind['standard'];
            $elementNama  = $ind['element'];
            $teks         = $ind['d'];
            if ($teks === '') continue;

            $stdKey = $standardNama;
            if (!isset($stdCache[$stdKey])) {
                $std = Standard::firstOrCreate([
                    'standar_akreditasi_id' => $akreditasiId,
                    'jenjang_id'            => $jenjangId,
                    'nama'                  => $standardNama,
                ]);
                if ($std->wasRecentlyCreated) $cStd++;
                $stdCache[$stdKey] = $std;
            }
            $standard = $stdCache[$stdKey];

            $elKey = $stdKey . '|' . $elementNama;
            if (!isset($elCache[$elKey])) {
                $el = Element::firstOrCreate([
                    'standard_id' => $standard->id,
                    'nama'        => $elementNama,
                ]);
                if ($el->wasRecentlyCreated) $cEl++;
                $elCache[$elKey] = $el;
            }
            $element = $elCache[$elKey];

            $info = $this->buildInfo(
                $ind['f'] ?? '',
                $ind['g'] ?? '',
                $ind['h'] ?? '',
                $ind['i'] ?? '',
                $ind['j'] ?? ''
            );

            $record = Indikator::updateOrCreate(
                ['elemen_id' => $element->id, 'nama_indikator' => $teks],
                [
                    'indikator_kode' => $ind['kode'] ?? null,
                    'kategori'       => $elementNama,
                    'info'           => $info ?: null,
                ]
            );
            if ($record->wasRecentlyCreated) $cInd++;
        }

        $this->command?->info("  LAMTEKNIK {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
    }

    protected function buildInfo(string $f, string $g, string $h, string $i, string $j): string
    {
        $parts = [];
        if ($f !== '') $parts[] = "Skor 4 :\n" . $this->formatText($f);
        if ($g !== '') $parts[] = "Skor 3 :\n" . $this->formatText($g);
        if ($h !== '') $parts[] = "Skor 2 :\n" . $this->formatText($h);
        if ($i !== '') $parts[] = "Skor 1 :\n" . $this->formatText($i);
        if ($j !== '') $parts[] = "Skor 0 :\n" . $this->formatText($j);
        return implode("\n", $parts);
    }

    /** Hapus referensi tabel "Tabel x.x. LKPS." dari teks indikator. */
    protected function cleanText(string $text): string
    {
        $text = preg_replace('/\s*Tabel\s+[\d\w\.]+\s+LKPS\.?/iu', '', $text);
        return $this->clean($text);
    }

    protected function formatText(string $text): string
    {
        $text = preg_replace('/[ \t]+(\([a-z]\))/u', "\n$1", $text); // (a), (b) …
        $text = preg_replace('/[ \t]+([a-z]\))/u',   "\n$1", $text); //  a),  b) …
        $text = preg_replace('/[ \t]+(\(\d+\))/u',   "\n$1", $text); // (1), (2) …
        $text = preg_replace('/[ \t]+(\d+\))/u',     "\n$1", $text); //  1),  2) …
        return trim($text);
    }

    protected function clean(mixed $value): string
    {
        return preg_replace('/\s+/u', ' ', trim((string) $value));
    }
}
