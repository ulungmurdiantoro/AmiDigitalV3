<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StandarAkreditasiSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Nama-nama ini dipakai aplikasi untuk mencocokkan akreditasi
        // (StandarAkreditasi::where('nama', ...)) dan harus konsisten dengan
        // tabel standards.standar_akreditasi_id.
        $items = [
            'BAN-PT',
            'LAMDIK',
            'LAMINFOKOM',
            'LAMEMBA',
            'LAMSAMA',
            'LAMPTKES',
            'LAMTEKNIK',
        ];

        // Bereskan baris lama bernama 'INFOKOM' agar konsisten dengan kode
        // aplikasi (calculateForecast(), $newLams, dsb. memakai 'LAMINFOKOM').
        DB::table('standar_akreditasis')->where('nama', 'INFOKOM')->update(['nama' => 'LAMINFOKOM']);

        foreach ($items as $nama) {
            DB::table('standar_akreditasis')->updateOrInsert(
                ['nama' => $nama],
                [
                    'standar_akreditasis_kode' => 'AKRE-' . strtoupper(str_replace([' ', '-'], '', $nama)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
