<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // prodi_akreditasi harus salah satu nama di StandarAkreditasiSeeder.
        $items = [
            ['program_studis_code' => 'PS-S1-MNJ',  'prodi_nama' => 'Manajemen',             'prodi_jenjang' => 'S1', 'prodi_jurusan' => 'Manajemen',             'prodi_fakultas' => 'Fakultas Ekonomi dan Bisnis',                'prodi_akreditasi' => 'LAMEMBA'],
            ['program_studis_code' => 'PS-S2-MNJ',  'prodi_nama' => 'Manajemen',             'prodi_jenjang' => 'S2', 'prodi_jurusan' => 'Manajemen',             'prodi_fakultas' => 'Fakultas Ekonomi dan Bisnis',                'prodi_akreditasi' => 'LAMEMBA'],
            ['program_studis_code' => 'PS-S1-AKT',  'prodi_nama' => 'Akuntansi',             'prodi_jenjang' => 'S1', 'prodi_jurusan' => 'Akuntansi',             'prodi_fakultas' => 'Fakultas Ekonomi dan Bisnis',                'prodi_akreditasi' => 'LAMEMBA'],
            ['program_studis_code' => 'PS-S1-TIF',  'prodi_nama' => 'Teknik Informatika',    'prodi_jenjang' => 'S1', 'prodi_jurusan' => 'Teknik Informatika',    'prodi_fakultas' => 'Fakultas Teknik',                            'prodi_akreditasi' => 'INFOKOM'],
            ['program_studis_code' => 'PS-S1-TSP',  'prodi_nama' => 'Teknik Sipil',          'prodi_jenjang' => 'S1', 'prodi_jurusan' => 'Teknik Sipil',          'prodi_fakultas' => 'Fakultas Teknik',                            'prodi_akreditasi' => 'LAMTEKNIK'],
            ['program_studis_code' => 'PS-S1-PMT',  'prodi_nama' => 'Pendidikan Matematika', 'prodi_jenjang' => 'S1', 'prodi_jurusan' => 'Pendidikan Matematika', 'prodi_fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',      'prodi_akreditasi' => 'LAMDIK'],
            ['program_studis_code' => 'PS-PPG-PMT', 'prodi_nama' => 'Pendidikan Profesi Guru','prodi_jenjang' => 'PPG','prodi_jurusan' => 'Pendidikan Matematika', 'prodi_fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',      'prodi_akreditasi' => 'LAMDIK'],
            ['program_studis_code' => 'PS-S1-ILH',  'prodi_nama' => 'Ilmu Hukum',            'prodi_jenjang' => 'S1', 'prodi_jurusan' => 'Ilmu Hukum',            'prodi_fakultas' => 'Fakultas Hukum',                             'prodi_akreditasi' => 'BAN-PT'],
        ];

        foreach ($items as $item) {
            DB::table('program_studis')->updateOrInsert(
                ['program_studis_code' => $item['program_studis_code']],
                array_merge($item, [
                    'akreditasi_kadaluarsa' => now()->addYears(5)->toDateString(),
                    'akreditasi_bukti'      => '-',
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ])
            );
        }
    }
}
