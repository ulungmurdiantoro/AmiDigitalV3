<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;

/**
 * Kriteria akreditasi LAMDIK (Buku 4 IAPSK 3.0).
 * Data hasil ekstraksi PDF (best-effort) disimpan di database/data/lamdik.json
 * yang dibangun oleh database/data/_lamdik_build_json.php.
 *
 * Struktur: jenjang -> kriteria -> [ {kode, bobot, indikator} ]
 * Mapping: kriteria -> standards, tiap indikator -> 1 element + 1 indikator.
 */
class KriteriaLamdikSeeder extends Seeder
{
    public function run(): void
    {
        $akreditasi = StandarAkreditasi::where('nama', 'LAMDIK')->first();
        if (!$akreditasi) {
            $this->command?->warn('StandarAkreditasi "LAMDIK" belum ada. Jalankan StandarAkreditasiSeeder dulu. Dilewati.');
            return;
        }

        $path = database_path('data/lamdik.json');
        if (!is_file($path)) {
            $this->command?->warn('database/data/lamdik.json tidak ada. Jalankan _lamdik_build_json.php dulu. Dilewati.');
            return;
        }

        $data = json_decode(file_get_contents($path), true) ?: [];

        foreach ($data as $jenjangNama => $kriteriaList) {
            $jenjang = Jenjang::firstOrCreate(['nama' => $jenjangNama]);
            $cStd = $cEl = $cInd = 0;

            foreach ($kriteriaList as $kriteria => $indikators) {
                $standard = Standard::firstOrCreate([
                    'standar_akreditasi_id' => $akreditasi->id,
                    'jenjang_id'            => $jenjang->id,
                    'nama'                  => $kriteria,
                ]);
                if ($standard->wasRecentlyCreated) $cStd++;

                foreach ($indikators as $it) {
                    $teks = trim($it['indikator'] ?? '');
                    if ($teks === '') continue;

                    // Nama elemen asli (dari Excel) bila ada; fallback ke teks indikator (sumber PDF).
                    $elemenNama = trim($it['elemen'] ?? '');
                    if ($elemenNama === '') $elemenNama = $teks;

                    // 1 elemen per indikator (instrumen LAMDIK: elemen 1:1 dengan indikator).
                    $element = Element::firstOrCreate([
                        'standard_id' => $standard->id,
                        'nama'        => $elemenNama,
                    ]);
                    if ($element->wasRecentlyCreated) $cEl++;

                    $info = trim($it['info'] ?? '');
                    $ind = Indikator::updateOrCreate(
                        ['elemen_id' => $element->id, 'nama_indikator' => $teks],
                        [
                            'indikator_kode' => $it['kode'] ?? null,
                            'bobot'          => $it['bobot'] ?? null,
                            'kategori'       => $kriteria,
                            'info'           => $info !== '' ? $info : null,
                        ]
                    );
                    if ($ind->wasRecentlyCreated) $cInd++;
                }
            }

            $this->command?->info("  LAMDIK {$jenjangNama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator");
        }
    }
}
