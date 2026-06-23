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
 * Satu file per jenjang, satu sheet "Table 1".
 * Struktur baris:
 *   - Baris judul      : dilewati (Col A berisi nama lembaga)
 *   - Baris header     : dilewati (Col A = "NO")
 *   - Baris seksi      : Col A = "A. TATA KELOLA …", "B. …", dst.
 *   - Baris indikator  : Col A = angka (1, 2 …), Col B = teks indikator
 *   - Baris lanjutan   : Col A kosong, Col B = lanjutan teks indikator sebelumnya
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

            $jenjang = Jenjang::firstOrCreate(['nama' => $jenjangNama]);
            $indicators = $this->parseSheet($path);
            $this->insertIndicators($indicators, (int) $akreditasi->id, (int) $jenjang->id, $jenjangNama);
        }
    }

    /**
     * Parse satu file Excel dan kembalikan array indikator:
     *   [ ['section' => string, 'kode' => string, 'text' => string], ... ]
     */
    protected function parseSheet(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $wb     = $reader->load($path);
        $sheet  = $wb->getSheet(0);

        $indicators     = [];
        $currentSection = null;
        $current        = null;

        $highestRow = $sheet->getHighestDataRow();

        for ($r = 1; $r <= $highestRow; $r++) {
            $a = $this->clean($sheet->getCell("A{$r}")->getValue());
            $b = $this->clean($sheet->getCell("B{$r}")->getValue());

            // Baris kosong
            if ($a === '' && $b === '') continue;

            // Baris header (NO | INDIKATOR) dan baris judul lembaga
            if ($a === 'NO' || str_starts_with($a, 'LAM') || str_contains($a, 'LEMBAGA')) continue;

            // Baris seksi: A. / B. / C. / … / F. (spasi setelah titik bersifat opsional)
            if (preg_match('/^[A-F]\./i', $a) && !is_numeric($a)) {
                $currentSection = $a;
                continue;
            }

            // Baris indikator baru
            if (is_numeric($a) && $a !== '') {
                if ($current !== null) {
                    $indicators[] = $current;
                }
                $current = ['section' => $currentSection, 'kode' => $a, 'text' => $b];
                continue;
            }

            // Baris lanjutan teks
            if ($a === '' && $b !== '' && $current !== null) {
                $current['text'] .= ' ' . $b;
            }
        }

        if ($current !== null) {
            $indicators[] = $current;
        }

        return $indicators;
    }

    protected function insertIndicators(array $indicators, int $akreditasiId, int $jenjangId, string $jenjangNama): void
    {
        $cStd = $cEl = $cInd = 0;
        $stdCache = [];

        foreach ($indicators as $ind) {
            $sectionNama = $ind['section'] ?? 'Umum';
            $teks        = $this->clean($ind['text']);
            if ($teks === '') continue;

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

            $element = Element::firstOrCreate([
                'standard_id' => $standard->id,
                'nama'        => $teks,
            ]);
            if ($element->wasRecentlyCreated) $cEl++;

            $record = Indikator::updateOrCreate(
                ['elemen_id' => $element->id, 'nama_indikator' => $teks],
                [
                    'indikator_kode' => $ind['kode'] ?? null,
                    'kategori'       => $sectionNama,
                ]
            );
            if ($record->wasRecentlyCreated) $cInd++;
        }

        $this->command?->info("  LAMSAMA {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
    }

    /** Normalkan whitespace (termasuk newline dalam sel dan spasi ganda dari konversi PDF). */
    protected function clean(mixed $value): string
    {
        $s = trim((string) $value);
        return preg_replace('/\s+/u', ' ', $s);
    }
}
