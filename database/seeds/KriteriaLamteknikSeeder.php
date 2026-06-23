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
 * Baca database/data/lamteknik.xlsx
 *
 * Format Excel:
 *   - Setiap sheet = satu jenjang (nama sheet = nama jenjang, mis. S1, D3, S2).
 *   - Baris pertama = header (dilewati).
 *   - Kolom A : Seksi / Kriteria  (mis. "I. Diferensiasi Misi") — boleh merged/diisi berulang
 *   - Kolom B : Kode              (mis. 1, 2, 3 …)
 *   - Kolom C : Teks Indikator
 */
class KriteriaLamteknikSeeder extends Seeder
{
    protected string $file = 'lamteknik.xlsx';

    public function run(): void
    {
        $akreditasi = StandarAkreditasi::where('nama', 'LAMTEKNIK')->first();
        if (!$akreditasi) {
            $this->command?->warn('StandarAkreditasi "LAMTEKNIK" belum ada. Jalankan StandarAkreditasiSeeder dulu.');
            return;
        }

        $path = database_path('data/' . $this->file);
        if (!is_file($path)) {
            $this->command?->warn("File tidak ditemukan: database/data/{$this->file}");
            return;
        }

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $wb = $reader->load($path);

        foreach ($wb->getAllSheets() as $sheet) {
            $jenjangNama = trim($sheet->getTitle());
            if ($jenjangNama === '') continue;

            $jenjang = Jenjang::firstOrCreate(['nama' => $jenjangNama]);
            $cStd = $cEl = $cInd = 0;
            $seksi = null;

            $highestRow = $sheet->getHighestDataRow();

            for ($r = 2; $r <= $highestRow; $r++) {
                $colA = trim((string) $sheet->getCell("A{$r}")->getValue());
                $colB = trim((string) $sheet->getCell("B{$r}")->getValue());
                $colC = trim((string) $sheet->getCell("C{$r}")->getValue());

                if ($colA !== '') $seksi = $colA;

                if ($seksi === null || $colC === '') continue;

                $standard = Standard::firstOrCreate([
                    'standar_akreditasi_id' => $akreditasi->id,
                    'jenjang_id'            => $jenjang->id,
                    'nama'                  => $seksi,
                ]);
                if ($standard->wasRecentlyCreated) $cStd++;

                $element = Element::firstOrCreate([
                    'standard_id' => $standard->id,
                    'nama'        => $colB !== '' ? $colB : $seksi,
                ]);
                if ($element->wasRecentlyCreated) $cEl++;

                $ind = Indikator::updateOrCreate(
                    ['elemen_id' => $element->id, 'nama_indikator' => $colC],
                    [
                        'indikator_kode' => $colB !== '' ? $colB : null,
                        'kategori'       => $seksi,
                    ]
                );
                if ($ind->wasRecentlyCreated) $cInd++;
            }

            $this->command?->info("  LAMTEKNIK {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
        }
    }
}
