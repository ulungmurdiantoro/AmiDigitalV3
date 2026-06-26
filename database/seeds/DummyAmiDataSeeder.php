<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\StandarAkreditasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seed data dummy lengkap untuk simulasi AMI yang sudah selesai.
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=DummyAmiDataSeeder
 *
 * Prasyarat: DatabaseSeeder sudah dijalankan terlebih dahulu
 * (StandarAkreditasiSeeder, JenjangSeeder, ProgramStudiSeeder,
 *  UserSeeder, serta semua KriteriaXxxSeeder sudah ada di DB).
 *
 * Yang dibuat seeder ini:
 *  1. ProgramStudi untuk LAMSAMA dan LAMPTKES (yang belum ada)
 *  2. User (kaprodi) untuk setiap prodi yang belum punya
 *  3. Isi kolom standar_akreditasi pada semua prodi
 *  4. Per prodi: AuditorAmi + PenjadwalanAmi + TransaksiAmi (Selesai)
 *     + StandarCapaian (penanda dokumen) + StandarNilai (nilai maksimal)
 *
 * Catatan max nilai per akreditasi:
 *  - LAMEMBA  : mandiri_nilai = 1  (binary: memenuhi/tidak per dimensi)
 *  - Lainnya  : mandiri_nilai = 4  (skala 0–4 à la BAN-PT)
 */
class DummyAmiDataSeeder extends Seeder
{
    const PERIODE = '2025/2026';

    // max mandiri_nilai per jenis akreditasi
    // BAN-PT  : 0=Tidak Terakreditasi, 1=Terakreditasi, 2=Terakreditasi Unggul
    // LAMEMBA : 0=tidak memenuhi, 1=memenuhi (binary per dimensi)
    // Lainnya : skala 0–4
    private array $maxNilaiMap = [
        'BAN-PT'    => 2,
        'LAMDIK'    => 4,
        'INFOKOM'   => 4,
        'LAMEMBA'   => 1,
        'LAMSAMA'   => 4,
        'LAMPTKES'  => 4,
        'LAMTEKNIK' => 4,
    ];

    public function run(): void
    {
        $now    = now();
        $periode = self::PERIODE;

        // ─────────────────────────────────────────────────────────────────
        // 1. PROGRAM STUDI — pastikan LAMSAMA & LAMPTKES punya ≥1 prodi
        // ─────────────────────────────────────────────────────────────────
        $newProdis = [
            [
                'program_studis_code' => 'PS-S1-ILK',
                'prodi_nama'          => 'Ilmu Komunikasi',
                'prodi_jenjang'       => 'S1',
                'prodi_jurusan'       => 'Ilmu Komunikasi',
                'prodi_fakultas'      => 'Fakultas Ilmu Sosial dan Politik',
                'prodi_akreditasi'    => 'LAMSAMA',
                'standar_akreditasi'  => 'LAMSAMA',
            ],
            [
                'program_studis_code' => 'PS-S1-KPW',
                'prodi_nama'          => 'Keperawatan',
                'prodi_jenjang'       => 'S1',
                'prodi_jurusan'       => 'Keperawatan',
                'prodi_fakultas'      => 'Fakultas Ilmu Kesehatan',
                'prodi_akreditasi'    => 'LAMPTKES',
                'standar_akreditasi'  => 'LAMPTKES',
            ],
        ];

        foreach ($newProdis as $pd) {
            DB::table('program_studis')->updateOrInsert(
                ['program_studis_code' => $pd['program_studis_code']],
                array_merge($pd, [
                    'akreditasi_kadaluarsa' => now()->addYears(5)->toDateString(),
                    'akreditasi_bukti'      => '-',
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ])
            );
        }

        // Isi kolom standar_akreditasi pada prodi lama yang belum terisi
        // (ProgramStudiSeeder tidak mengisi kolom ini, tapi login controller
        //  butuh nilainya untuk menentukan user_akses saat login)
        DB::table('program_studis')
            ->where(function ($q) {
                $q->whereNull('standar_akreditasi')->orWhere('standar_akreditasi', '');
            })
            ->update([
                'standar_akreditasi' => DB::raw('prodi_akreditasi'),
                'updated_at'         => $now,
            ]);

        // ─────────────────────────────────────────────────────────────────
        // 2. USERS — satu kaprodi per prodi yang belum punya akun
        // ─────────────────────────────────────────────────────────────────
        // USR-0001..0007 sudah dipakai UserSeeder; mulai dari 0010 agar aman
        $newUsers = [
            [
                'users_code'      => 'USR-0010',
                'username'        => 'akuntansi',
                'user_nama'       => 'Kaprodi S1 Akuntansi',
                'user_penempatan' => 'S1 - Akuntansi',
                'user_fakultas'   => 'Fakultas Ekonomi dan Bisnis',
                'user_akses'      => 'LAMEMBA',
            ],
            [
                'users_code'      => 'USR-0011',
                'username'        => 'sipil',
                'user_nama'       => 'Kaprodi S1 Teknik Sipil',
                'user_penempatan' => 'S1 - Teknik Sipil',
                'user_fakultas'   => 'Fakultas Teknik',
                'user_akses'      => 'LAMTEKNIK',
            ],
            [
                'users_code'      => 'USR-0012',
                'username'        => 'komunikasi',
                'user_nama'       => 'Kaprodi S1 Ilmu Komunikasi',
                'user_penempatan' => 'S1 - Ilmu Komunikasi',
                'user_fakultas'   => 'Fakultas Ilmu Sosial dan Politik',
                'user_akses'      => 'LAMSAMA',
            ],
            [
                'users_code'      => 'USR-0013',
                'username'        => 'keperawatan',
                'user_nama'       => 'Kaprodi S1 Keperawatan',
                'user_penempatan' => 'S1 - Keperawatan',
                'user_fakultas'   => 'Fakultas Ilmu Kesehatan',
                'user_akses'      => 'LAMPTKES',
            ],
        ];

        foreach ($newUsers as $u) {
            DB::table('users')->updateOrInsert(
                ['username' => $u['username']],
                [
                    'users_code'      => $u['users_code'],
                    'user_id'         => substr($u['users_code'], -4),
                    'user_nama'       => $u['user_nama'],
                    'user_jabatan'    => 'Ketua Program Studi',
                    'user_penempatan' => $u['user_penempatan'],
                    'user_fakultas'   => $u['user_fakultas'],
                    'user_akses'      => $u['user_akses'],
                    'user_pelatihan'  => null,
                    'user_sertfikat'  => null,
                    'user_sk'         => null,
                    'password'        => Hash::make('password'),
                    'user_level'      => 'user',
                    'user_status'     => 'Aktif',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]
            );
        }

        // ─────────────────────────────────────────────────────────────────
        // 3. DATA AMI — satu siklus selesai per prodi
        // ─────────────────────────────────────────────────────────────────
        // Setiap prodi digilir ke auditor1 / auditor2 secara bergantian
        // agar keduanya terlihat memiliki data di dashboard auditor.
        $targetProdis = [
            //  penempatan (= user_penempatan)              fakultas                                      akreditasi    auditor
            ['S1 - Manajemen',            'Fakultas Ekonomi dan Bisnis',              'LAMEMBA',    1],
            ['S1 - Akuntansi',            'Fakultas Ekonomi dan Bisnis',              'LAMEMBA',    2],
            ['S1 - Teknik Informatika',   'Fakultas Teknik',                          'INFOKOM',    1],
            ['S1 - Teknik Sipil',         'Fakultas Teknik',                          'LAMTEKNIK',  2],
            ['S1 - Pendidikan Matematika','Fakultas Keguruan dan Ilmu Pendidikan',    'LAMDIK',     1],
            ['S1 - Ilmu Hukum',           'Fakultas Hukum',                           'BAN-PT',     2],
            ['S1 - Ilmu Komunikasi',      'Fakultas Ilmu Sosial dan Politik',         'LAMSAMA',    1],
            ['S1 - Keperawatan',          'Fakultas Ilmu Kesehatan',                  'LAMPTKES',   2],
        ];

        // Ambil users_code auditor1 dan auditor2 dari tabel users
        $auditorCode = [
            1 => DB::table('users')->where('username', 'auditor1')->value('users_code') ?? 'USR-0006',
            2 => DB::table('users')->where('username', 'auditor2')->value('users_code') ?? 'USR-0007',
        ];

        foreach ($targetProdis as $seq => [$penempatan, $fakultas, $akreditasi, $auditorIdx]) {
            $n           = str_pad((string) ($seq + 1), 3, '0', STR_PAD_LEFT);
            $auditorKode = "AUD-DUMMY-{$n}";
            $amiKode     = "AMI-DUMMY-{$n}";
            $jadwalKode  = "SCH-DUMMY-{$n}";
            $auditorUsers = $auditorCode[$auditorIdx];

            // Jenjang diambil dari bagian pertama penempatan ("S1 - Manajemen" → "S1")
            $jenjangNama = trim(explode(' - ', $penempatan, 2)[0]);

            // ── AuditorAmi ────────────────────────────────────────────────
            DB::table('auditor_amis')->updateOrInsert(
                ['auditor_kode' => $auditorKode],
                [
                    'auditor_kode' => $auditorKode,
                    'users_kode'   => $auditorUsers,
                    'tim_ami'      => "Tim AMI {$penempatan}",
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]
            );

            // ── PenjadwalanAmi ─────────────────────────────────────────────
            DB::table('penjadwalan_amis')->updateOrInsert(
                ['jadwal_kode' => $jadwalKode],
                [
                    'jadwal_kode'        => $jadwalKode,
                    'auditor_kode'       => $auditorKode,
                    'informasi_tambahan' => "Jadwal AMI {$periode} – {$penempatan}",
                    'prodi'              => $penempatan,
                    'fakultas'           => $fakultas,
                    'standar_akreditasi' => $akreditasi,
                    'periode'            => $periode,
                    // Semua tahapan sudah selesai (tanggal lampau)
                    'opening_ami'         => '2025-08-01 to 2025-08-07',
                    'pengisian_dokumen'   => '2025-08-08 to 2025-08-31',
                    'deskevaluasion'      => '2025-09-01 to 2025-09-14',
                    'assessment'          => '2025-09-15 to 2025-09-30',
                    'tindakan_koreksi'    => '2025-10-01 to 2025-10-31',
                    'laporan_ami'         => '2025-11-01 to 2025-11-15',
                    'rtm'                 => '2025-11-30',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]
            );

            // ── TransaksiAmi (status Selesai) ─────────────────────────────
            DB::table('transaksi_amis')->updateOrInsert(
                ['ami_kode' => $amiKode],
                [
                    'ami_kode'           => $amiKode,
                    'auditor_kode'       => $auditorKode,
                    'informasi_tambahan' => null,
                    'prodi'              => $penempatan,
                    'fakultas'           => $fakultas,
                    'standar_akreditasi' => $akreditasi,
                    'periode'            => $periode,
                    'status'             => 'Selesai',
                    'alasan'             => null,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]
            );

            // ── Resolve standar dari DB ────────────────────────────────────
            $akreditasiModel = StandarAkreditasi::where('nama', $akreditasi)->first();
            $jenjangModel    = Jenjang::where('nama', $jenjangNama)->first();

            if (! $akreditasiModel || ! $jenjangModel) {
                $this->command->warn("  ⚠ [{$penempatan}] akreditasi/jenjang tidak ditemukan di DB — lewati StandarNilai.");
                continue;
            }

            $standards = Standard::with('elements.indicators')
                ->where('standar_akreditasi_id', $akreditasiModel->id)
                ->where('jenjang_id', $jenjangModel->id)
                ->get();

            if ($standards->isEmpty()) {
                $this->command->warn("  ⚠ [{$penempatan}] belum ada standar untuk {$akreditasi}/{$jenjangNama} — lewati StandarNilai.");
                continue;
            }

            $maxNilai = $this->maxNilaiMap[$akreditasi] ?? 4;

            // ── StandarCapaian — minimal 1 record agar periode muncul di index ──
            $firstIndicator = $standards->first()?->elements->first()?->indicators->first();
            if ($firstIndicator) {
                $capaianKode = 'CAP-' . strtoupper(preg_replace('/[^A-Z0-9]/i', '', $penempatan)) . '-' . $firstIndicator->id;
                DB::table('standar_capaians')->updateOrInsert(
                    ['capaian_kode' => $capaianKode],
                    [
                        'capaian_kode'       => $capaianKode,
                        'bukti_standar_id'   => null,
                        'indikator_id'       => $firstIndicator->id,
                        'dokumen_nama'       => "Bukti Pemenuhan – {$penempatan}",
                        'pertanyaan_nama'    => $firstIndicator->nama_indikator,
                        'dokumen_tipe'       => 'pdf',
                        'dokumen_keterangan' => "Data dummy AMI {$periode}",
                        'dokumen_file'       => '-',
                        'dokumen_kadaluarsa' => now()->addYears(3)->toDateString(),
                        'informasi'          => null,
                        'periode'            => $periode,
                        'prodi'              => $penempatan,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ]
                );
            }

            // ── StandarNilai — satu record per Indikator, nilai maksimal ───
            $nilaiCount = 0;
            foreach ($standards as $standard) {
                foreach ($standard->elements as $element) {
                    foreach ($element->indicators as $indikator) {
                        DB::table('standar_nilais')->updateOrInsert(
                            [
                                'indikator_id' => $indikator->id,
                                'periode'      => $periode,
                                'prodi'        => $penempatan,
                            ],
                            [
                                'ami_kode'                   => $amiKode,
                                'indikator_id'               => $indikator->id,
                                'mandiri_nilai'              => $maxNilai,
                                'hasil_nilai'                => 1,
                                'bobot'                      => $indikator->bobot ?? 1,
                                'jenis_temuan'               => 'Sesuai',
                                'hasil_kriteria'             => 'Memenuhi standar penjaminan mutu internal',
                                'hasil_deskripsi'            => "Indikator terpenuhi sesuai standar {$akreditasi}.",
                                'hasil_akibat'               => '-',
                                'hasil_masalah'              => '-',
                                'hasil_rekomendasi'          => 'Pertahankan capaian dan tingkatkan secara berkelanjutan.',
                                'hasil_rencana_perbaikan'    => '-',
                                'hasil_jadwal_perbaikan'     => '-',
                                'hasil_perbaikan_penanggung' => '-',
                                'hasil_rencana_pencegahan'   => '-',
                                'hasil_jadwal_pencegahan'    => '-',
                                'hasil_rencana_penanggung'   => '-',
                                'status_akhir'               => 'Sesuai',
                                'prodi'                      => $penempatan,
                                'periode'                    => $periode,
                                'created_at'                 => $now,
                                'updated_at'                 => $now,
                            ]
                        );
                        $nilaiCount++;
                    }
                }
            }

            $this->command->info("  ✓ {$penempatan} ({$akreditasi}) — {$nilaiCount} indikator dinilai, max={$maxNilai}");
        }

        $this->command->info('');
        $this->command->info('DummyAmiDataSeeder selesai. Password semua akun baru: password');
    }
}
