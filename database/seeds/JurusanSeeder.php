<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['jurusan_kode' => 'MNJ', 'jurusan_nama' => 'Manajemen'],
            ['jurusan_kode' => 'AKT', 'jurusan_nama' => 'Akuntansi'],
            ['jurusan_kode' => 'TIF', 'jurusan_nama' => 'Teknik Informatika'],
            ['jurusan_kode' => 'TSP', 'jurusan_nama' => 'Teknik Sipil'],
            ['jurusan_kode' => 'PMT', 'jurusan_nama' => 'Pendidikan Matematika'],
            ['jurusan_kode' => 'ILH', 'jurusan_nama' => 'Ilmu Hukum'],
        ];

        foreach ($items as $item) {
            DB::table('jurusans')->updateOrInsert(
                ['jurusan_kode' => $item['jurusan_kode']],
                [
                    'jurusan_nama' => $item['jurusan_nama'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
