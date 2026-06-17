<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Password default semua akun seeder: "password"
        // Login memakai kolom: username + password (UserController@login).
        // user_penempatan = "{jenjang} - {prodi}" (dipakai untuk resolve jenjang/prodi).
        // user_akses = nama akreditasi (BAN-PT / LAMEMBA / LAMDIK / INFOKOM / ...).
        $users = [
            [
                'username'        => 'admin',
                'user_nama'       => 'Administrator',
                'user_jabatan'    => 'Admin Penjaminan Mutu',
                'user_level'      => 'admin',
                'user_penempatan' => '-',
                'user_fakultas'   => null,
                'user_akses'      => null,
            ],
            [
                'username'        => 'manajemen',
                'user_nama'       => 'Kaprodi S1 Manajemen',
                'user_jabatan'    => 'Ketua Program Studi',
                'user_level'      => 'user',
                'user_penempatan' => 'S1 - Manajemen',
                'user_fakultas'   => 'Fakultas Ekonomi dan Bisnis',
                'user_akses'      => 'LAMEMBA',
            ],
            [
                'username'        => 'informatika',
                'user_nama'       => 'Kaprodi S1 Teknik Informatika',
                'user_jabatan'    => 'Ketua Program Studi',
                'user_level'      => 'user',
                'user_penempatan' => 'S1 - Teknik Informatika',
                'user_fakultas'   => 'Fakultas Teknik',
                'user_akses'      => 'INFOKOM',
            ],
            [
                'username'        => 'pmat',
                'user_nama'       => 'Kaprodi S1 Pendidikan Matematika',
                'user_jabatan'    => 'Ketua Program Studi',
                'user_level'      => 'user',
                'user_penempatan' => 'S1 - Pendidikan Matematika',
                'user_fakultas'   => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'user_akses'      => 'LAMDIK',
            ],
            [
                'username'        => 'hukum',
                'user_nama'       => 'Kaprodi S1 Ilmu Hukum',
                'user_jabatan'    => 'Ketua Program Studi',
                'user_level'      => 'user',
                'user_penempatan' => 'S1 - Ilmu Hukum',
                'user_fakultas'   => 'Fakultas Hukum',
                'user_akses'      => 'BAN-PT',
            ],
            [
                'username'        => 'auditor1',
                'user_nama'       => 'Auditor Mutu 1',
                'user_jabatan'    => 'Auditor Internal',
                'user_level'      => 'auditor',
                'user_penempatan' => '-',
                'user_fakultas'   => null,
                'user_akses'      => null,
            ],
            [
                'username'        => 'auditor2',
                'user_nama'       => 'Auditor Mutu 2',
                'user_jabatan'    => 'Auditor Internal',
                'user_level'      => 'auditor',
                'user_penempatan' => '-',
                'user_fakultas'   => null,
                'user_akses'      => null,
            ],
        ];

        foreach ($users as $i => $u) {
            DB::table('users')->updateOrInsert(
                ['username' => $u['username']],
                [
                    'users_code'      => 'USR-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'user_id'         => '1000' . ($i + 1),
                    'user_nama'       => $u['user_nama'],
                    'user_jabatan'    => $u['user_jabatan'],
                    'user_penempatan' => $u['user_penempatan'],
                    'user_fakultas'   => $u['user_fakultas'],
                    'user_akses'      => $u['user_akses'],
                    'user_pelatihan'  => null,
                    'user_sertfikat'  => null,
                    'user_sk'         => null,
                    'password'        => Hash::make('password'),
                    'user_level'      => $u['user_level'],
                    'user_status'     => 'Aktif',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]
            );
        }
    }
}
