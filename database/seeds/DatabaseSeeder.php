<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Master / referensi
            StandarAkreditasiSeeder::class,
            JenjangSeeder::class,
            FakultasSeeder::class,
            JurusanSeeder::class,
            ProgramStudiSeeder::class,

            // Kriteria akreditasi dari file Excel (database/data/*.xlsx)
            KriteriaBanptSeeder::class,
            // Kriteria LAMEMBA Terakreditasi (5 kriteria, 12 dimensi, 29 indikator)
            KriteriaLamembaSeeder::class,
            // Kriteria LAMEMBA Terakreditasi Unggul (7 kriteria, 21 dimensi, 58 indikator)
            KriteriaLamembaUnggulSeeder::class,
            // Kriteria LAMDIK (dari database/data/lamdik.json hasil ekstraksi PDF)
            KriteriaLamdikSeeder::class,
            // Kriteria LAMSAMA (dari database/data/lamsama.json hasil ekstraksi PDF)
            KriteriaLamsamaSeeder::class,
            // Kriteria LAMTEKNIK (dari database/data/lamteknik.json hasil ekstraksi PDF)
            KriteriaLamteknikSeeder::class,
            // Kriteria LAMINFOKOM (dari database/data/LAMINFOKOM/*.xlsx Matriks Penilaian)
            KriteriaLaminfokomSeeder::class,

            // Target dokumen BAN-PT semua jenjang
            KriteriaBanptTargetSeeder::class,
            // Target dokumen LAMEMBA (1 jenjang untuk semua)
            KriteriaLamembaTargetSeeder::class,
            // Target dokumen LAMDIK, LAMINFOKOM, LAMTEKNIK
            KriteriaOtherTargetSeeder::class,

            // Akun login
            UserSeeder::class,
        ]);
    }
}
