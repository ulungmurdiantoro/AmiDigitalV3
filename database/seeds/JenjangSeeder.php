<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenjangSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Nama jenjang dipakai untuk mencocokkan Standard::where('jenjang_id', ...)
        // dan diturunkan dari prodi (mis. "S1 - Manajemen" -> "S1").
        $items = [
            'D1', 'D2', 'D3',
            'S1', 'S2', 'S3',
            'S1 Terapan', 'S2 Terapan', 'S3 Terapan',
            'S1 PJJ', 'S2 PJJ', 'S3 PJJ',
            'PPG',
        ];

        foreach ($items as $nama) {
            DB::table('jenjangs')->updateOrInsert(
                ['nama' => $nama],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
