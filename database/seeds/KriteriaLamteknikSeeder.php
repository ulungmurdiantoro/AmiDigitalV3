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
 * Struktur baris:
 *   - Baris judul/header : dilewati (sebelum section I.)
 *   - Section (Standard) : Col A = "I. Diferensiasi Misi …", "II. Akuntabilitas …", dst.
 *   - Sub-section (Elem) : Col A = "2.1 Tata Pamong", "3.1. Pendidikan", dst.
 *   - Label IKU          : Col A berisi "(Indikator Kinerja Utama)" — dipakai sebagai
 *                          elemen bila seksi tidak punya sub-section bernomor.
 *   - Indikator          : Col A = angka (1, 2 …), Col B = nama singkat.
 *                          Bila Col B kosong, nama diambil dari Col D.
 *   - Lanjutan           : Col A kosong, Col B = lanjutan teks indikator sebelumnya.
 *   - Header "No|Kriteria": dilewati
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

    /**
     * Parse satu file Excel LAMTEKNIK.
     * Kembalikan array: [ ['standard'=>…, 'element'=>…, 'kode'=>…, 'text'=>…], … ]
     */
    protected function parseSheet(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $wb    = $reader->load($path);
        $sheet = $wb->getSheet(0);

        $indicators      = [];
        $started         = false;   // mulai proses setelah section I. pertama
        $currentStandard = null;
        $currentElement  = null;    // sub-section (2.1, 3.1, …) atau label IKU
        $hasSubSection   = false;   // apakah seksi aktif punya sub-section bernomor
        $current         = null;    // indikator sedang dibangun

        $highestRow = $sheet->getHighestDataRow();

        for ($r = 1; $r <= $highestRow; $r++) {
            $a = $this->clean($sheet->getCell("A{$r}")->getValue());
            $b = $this->clean($sheet->getCell("B{$r}")->getValue());
            $d = $this->clean($sheet->getCell("D{$r}")->getValue());

            if ($a === '' && $b === '') continue;

            // Lewati baris header "No | Kriteria"
            if ($a === 'No' || $a === 'NO') continue;

            // Deteksi section Roman numeral (I. II. III. … VII.) atau "B. PROGRAM"
            if (preg_match('/^(?:I{1,3}V?|VI{0,3}|VII|B)\.\s*/iu', $a) && !is_numeric($a)) {
                // "A. KRITERIA" dan "B." hanya untuk group label → skip "A."
                if (preg_match('/^A\.\s+KRITERIA/iu', $a)) continue;

                $this->saveIndicator($current, $indicators);
                $current         = null;
                $currentStandard = $a;
                $currentElement  = null;
                $hasSubSection   = false;
                $started         = true;
                continue;
            }

            if (!$started) continue;

            // Sub-section bernomor: "2.1 …", "3.1. …", "4.2 …"
            if (preg_match('/^\d+\.\d+/u', $a)) {
                $this->saveIndicator($current, $indicators);
                $current        = null;
                $currentElement = $a;
                $hasSubSection  = true;
                continue;
            }

            // Label IKU: dipakai sebagai elemen bila belum ada sub-section di seksi ini
            if (str_contains($a, 'Indikator Kinerja Utama') || str_contains($a, 'Indikator Kinerja')) {
                if (!$hasSubSection) {
                    $currentElement = $a;
                }
                continue;
            }

            // Baris indikator baru (Col A = angka)
            if (is_numeric($a) && $a !== '') {
                $this->saveIndicator($current, $indicators);

                // Nama: Col B (bersih) → fallback Col D (baris pertama)
                $namaRaw = $b !== '' ? $b : $d;
                $nama    = $this->cleanIndicatorName($namaRaw);

                $current = [
                    'standard' => $currentStandard,
                    'element'  => $currentElement ?? $currentStandard,
                    'kode'     => $a,
                    'text'     => $nama,
                ];
                continue;
            }

            // Baris lanjutan (Col A kosong, Col B berisi teks)
            if ($a === '' && $b !== '' && $current !== null) {
                $current['text'] .= ' ' . $this->cleanIndicatorName($b);
            }
        }

        $this->saveIndicator($current, $indicators);

        return $indicators;
    }

    protected function saveIndicator(?array &$current, array &$indicators): void
    {
        if ($current === null) return;
        $teks = trim($current['text'] ?? '');
        if ($teks !== '') {
            $current['text'] = $teks;
            $indicators[]    = $current;
        }
        $current = null;
    }

    protected function insertIndicators(array $indicators, int $akreditasiId, int $jenjangId, string $jenjangNama): void
    {
        $cStd = $cEl = $cInd = 0;
        $stdCache = [];
        $elCache  = [];

        foreach ($indicators as $ind) {
            $standardNama = $ind['standard'] ?? 'Umum';
            $elementNama  = $ind['element']  ?? $standardNama;
            $teks         = $ind['text'];
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

            $record = Indikator::updateOrCreate(
                ['elemen_id' => $element->id, 'nama_indikator' => $teks],
                [
                    'indikator_kode' => $ind['kode'] ?? null,
                    'kategori'       => $elementNama,
                ]
            );
            if ($record->wasRecentlyCreated) $cInd++;
        }

        $this->command?->info("  LAMTEKNIK {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
    }

    /** Hapus formula skor dan referensi tabel dari nama indikator. */
    protected function cleanIndicatorName(string $text): string
    {
        // Hapus "Skor = ..." (termasuk yang di baris baru)
        $text = preg_replace('/[\r\n\s]*Skor\s*=\s*[^\r\n]+/iu', '', $text);
        // Hapus referensi tabel "Tabel x.x. LKPS."
        $text = preg_replace('/[\r\n\s]*Tabel\s+[\d\w\.]+\s+LKPS\.?/iu', '', $text);
        return $this->clean($text);
    }

    /** Normalkan whitespace (spasi ganda, newline dalam sel). */
    protected function clean(mixed $value): string
    {
        $s = trim((string) $value);
        return preg_replace('/\s+/u', ' ', $s);
    }
}
