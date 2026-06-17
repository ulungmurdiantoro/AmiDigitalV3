<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['fakultas_kode' => 'FEB',  'fakultas_nama' => 'Fakultas Ekonomi dan Bisnis'],
            ['fakultas_kode' => 'FT',   'fakultas_nama' => 'Fakultas Teknik'],
            ['fakultas_kode' => 'FKIP', 'fakultas_nama' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['fakultas_kode' => 'FH',   'fakultas_nama' => 'Fakultas Hukum'],
            ['fakultas_kode' => 'FMIPA','fakultas_nama' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam'],
            ['fakultas_kode' => 'FISIP','fakultas_nama' => 'Fakultas Ilmu Sosial dan Ilmu Politik'],
        ];

        foreach ($items as $item) {
            DB::table('fakultas')->updateOrInsert(
                ['fakultas_kode' => $item['fakultas_kode']],
                [
                    'fakultas_nama' => $item['fakultas_nama'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
