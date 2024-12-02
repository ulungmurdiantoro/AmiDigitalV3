<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jenjang;
use App\Models\StandarAkreditasi;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\KategoriDokumen;
use App\Models\DokumenTipe;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create user
        $users = [
            [
                'user_id'      => '3374123004010001',
                'user_nama'      => 'Administrator',
                'user_jabatan'      => 'Administrator',
                'user_penempatan'      => 'Administrator',
                'username'     => 'admin',
                'password'  => bcrypt('password'),
                'user_level'     => 'admin',
                'user_status'     => 'aktif',
            ],
            [
                'user_id'      => '3374123004010002',
                'user_nama'      => 'user',
                'user_jabatan'      => 'user',
                'user_penempatan'      => 'S1 - Pendidikan Matematika',
                'user_fakultas'      => 'Fakultas Pendidikan',
                'user_akses'      => 'BAN-PT',
                'username'     => 'user1',
                'password'  => bcrypt('password'),
                'user_level'     => 'user',
                'user_status'     => 'aktif',
            ],
            [
                'user_id'      => '3374123004010003',
                'user_nama'      => 'auditor',
                'user_jabatan'      => 'auditor',
                'user_penempatan'      => 'Auditor',
                'user_pelatihan'      => '2026',
                'username'     => 'auditor1',
                'password'  => bcrypt('password'),
                'user_level'     => 'auditor',
                'user_status'     => 'aktif',
            ],
        ];

        $jenjangs = [
            ['jenjang_nama' => 'S1',],
            ['jenjang_nama' => 'S2',],
            ['jenjang_nama' => 'S3',],
            ['jenjang_nama' => 'S1 Terapan',],
            ['jenjang_nama' => 'S2 Terapan',],
            ['jenjang_nama' => 'S3 Terapan',],
            ['jenjang_nama' => 'PPG',],
        ];

        $standar_akreditasis = [
            ['standar_akreditasis_nama' => 'BAN-PT'],
            ['standar_akreditasis_nama' => 'LAMDIK'],
            ['standar_akreditasis_nama' => 'INFOKOM'],
            ['standar_akreditasis_nama' => 'LAMEMBA'],
            ['standar_akreditasis_nama' => 'LAMSAMA'],
            ['standar_akreditasis_nama' => 'LAMPTKES'],
            ['standar_akreditasis_nama' => 'LAMTEKNIK'],
        ];

        $jurusans = [
            ['jurusan_nama' => 'Matematika',],
            ['jurusan_nama' => 'Fisika',],
            ['jurusan_nama' => 'Kimia'],
            ['jurusan_nama' => 'Biologi'],
            ['jurusan_nama' => 'Statistika'],
            ['jurusan_nama' => 'Aktuaria'],
            ['jurusan_nama' => 'Geofisika'],
            ['jurusan_nama' => 'Geografi'],
            ['jurusan_nama' => 'Ilmu Komputasi'],
            ['jurusan_nama' => 'Teknik Geologi'],
            ['jurusan_nama' => 'Bioteknologi'],
            ['jurusan_nama' => 'Teknik Sipil'],
            ['jurusan_nama' => 'Teknik Mesin'],
            ['jurusan_nama' => 'Teknik Elektro'],
            ['jurusan_nama' => 'Teknik Informatika'],
            ['jurusan_nama' => 'Sistem Informasi'],
            ['jurusan_nama' => 'Teknik Industri'],
            ['jurusan_nama' => 'Teknik Kimia'],
        ];

        $fakultas = [
            ['fakultas_nama' => 'Fakultas Teknik'],
            ['fakultas_nama' => 'Fakultas Kedokteran'],
            ['fakultas_nama' => 'Fakultas Kesehatan Masyarakat'],
            ['fakultas_nama' => 'Fakultas Ilmu Komputer'],
            ['fakultas_nama' => 'Fakultas Ekonomi dan Bisnis'],
            ['fakultas_nama' => 'Fakultas Hukum'],
            ['fakultas_nama' => 'Fakultas Pertanian'],
            ['fakultas_nama' => 'Fakultas Peternakan'],
            ['fakultas_nama' => 'Fakultas Kehutanan'],
            ['fakultas_nama' => 'Fakultas Ilmu Budaya'],
            ['fakultas_nama' => 'Fakultas Pendidikan'],
            ['fakultas_nama' => 'Fakultas Seni dan Desain'],
        ];

        $KategoriDokumens = [
            ['kategori_dokumen' => 'Dokumen SPMI'],
            ['kategori_dokumen' => 'Dokumen AMI'],
            ['kategori_dokumen' => 'Dokumen PKA'],
            ['kategori_dokumen' => 'Dokumen KKA'],
        ];

        $dokumen_tipes = [
            ['tipe_nama' => 'Dokumen Mutu Manual'],
            ['tipe_nama' => 'Dokumen Mutu Prosedur'],
            ['tipe_nama' => 'Dokumen Mutu Instruksi Kerja'],
            ['tipe_nama' => 'Dokumen Mutu Formulir'],
            ['tipe_nama' => 'Dokumen Sasaran / Target'],
            ['tipe_nama' => 'Regulasi'],
            ['tipe_nama' => 'Surat Keputusan'],
            ['tipe_nama' => 'Formulir Terisi'],
            ['tipe_nama' => 'Sertifikat'],
            ['tipe_nama' => 'Data'],
            ['tipe_nama' => 'Publikasi / Jurnal'],
            ['tipe_nama' => 'Agreement / MoU'],
            ['tipe_nama' => 'LED']
        ];

        foreach ($users as $user) {
            $user['users_code'] = 'usr-' . Str::uuid();
            User::create($user);
        }

        foreach ($jenjangs as $jenjang) {
            $jenjang['jenjang_kode'] = 'jjg-' . Str::uuid();
            Jenjang::create($jenjang);
        }

        foreach ($standar_akreditasis as $standar_akreditasi) {
            $standar_akreditasi['standar_akreditasis_kode'] = 'akre-' . Str::uuid();
            StandarAkreditasi::create($standar_akreditasi);
        }

        foreach ($jurusans as $jurusan) {
            $jurusan['jurusan_kode'] = 'jrsn-' . Str::uuid();
            Jurusan::create($jurusan);
        }

        foreach ($fakultas as $fakultas) {
            $fakultas['fakultas_kode'] = 'fkts-' . Str::uuid();
            Fakultas::create($fakultas);
        }
        foreach ($KategoriDokumens as $KategoriDokumen) {
            KategoriDokumen::create($KategoriDokumen);
        }
        foreach ($dokumen_tipes as $dokumen_tipe) {
            DokumenTipe::create($dokumen_tipe);
        }
    }
}