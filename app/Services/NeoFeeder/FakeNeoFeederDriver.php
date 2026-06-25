<?php

namespace App\Services\NeoFeeder;

use App\Models\ProgramStudi;

class FakeNeoFeederDriver implements NeoFeederDriverInterface
{
    private array $namaL = [
        'Ahmad Fauzi', 'Budi Santoso', 'Cahyo Nugroho', 'Dedi Kurniawan', 'Eko Prasetyo',
        'Fajar Ramadhan', 'Gilang Saputra', 'Hendra Wijaya', 'Ivan Setiawan', 'Joko Susanto',
        'Kevin Maulana', 'Lukman Hakim', 'Muhammad Rizki', 'Nanda Pratama', 'Oscar Firmansyah',
        'Pandu Wicaksono', 'Reza Aditya', 'Surya Darma', 'Taufik Hidayat', 'Umar Siddiq',
        'Vino Anggara', 'Wahyu Nugroho', 'Xander Putra', 'Yoga Permana', 'Zaki Firdaus',
        'Alif Hidayatullah', 'Bagas Pradipta', 'Candra Putra', 'Danu Setyawan', 'Faisal Rahman',
    ];

    private array $namaP = [
        'Ayu Rahmawati', 'Bella Safitri', 'Citra Dewi', 'Dina Anggraeni', 'Eva Kusuma',
        'Fitri Rahayu', 'Gita Puspita', 'Hana Permata', 'Indah Lestari', 'Jihan Nabila',
        'Kartika Sari', 'Laila Nur', 'Maya Putri', 'Nisa Amalia', 'Olivia Susanti',
        'Putri Handayani', 'Ratna Dewi', 'Sinta Melati', 'Tika Andriani', 'Ulfa Hasanah',
        'Vina Marlina', 'Wulan Dari', 'Yuni Astuti', 'Zahra Fadilah', 'Annisa Rohmah',
        'Bunga Pertiwi', 'Clara Wijayanti', 'Dwi Ratnasari', 'Elsa Fitriani', 'Fatimah Azzahra',
    ];

    // Pool dosen per prodi — diacak berdasarkan index prodi
    private array $poolDosenL = [
        ['nama' => 'Dr. Ahmad Yusuf, M.Kom.',     'nidn_sfx' => '7001', 'pend' => 'S3', 'jabatan' => 'Lektor Kepala', 'keahlian' => 'Sistem Informasi'],
        ['nama' => 'Budi Hartono, M.T.',           'nidn_sfx' => '8002', 'pend' => 'S2', 'jabatan' => 'Lektor',        'keahlian' => 'Teknik Perangkat Lunak'],
        ['nama' => 'Prof. Cahyo Utomo, Ph.D.',     'nidn_sfx' => '6503', 'pend' => 'S3', 'jabatan' => 'Guru Besar',    'keahlian' => 'Kecerdasan Buatan'],
        ['nama' => 'Dedy Irawan, M.Cs.',           'nidn_sfx' => '8504', 'pend' => 'S2', 'jabatan' => 'Asisten Ahli',  'keahlian' => 'Basis Data'],
        ['nama' => 'Dr. Eko Setiawan, M.M.',       'nidn_sfx' => '7005', 'pend' => 'S3', 'jabatan' => 'Lektor Kepala', 'keahlian' => 'Manajemen Informatika'],
        ['nama' => 'Fajar Nugroho, M.Kom.',        'nidn_sfx' => '8506', 'pend' => 'S2', 'jabatan' => 'Lektor',        'keahlian' => 'Jaringan Komputer'],
        ['nama' => 'Gunawan Pratama, M.T.',        'nidn_sfx' => '9007', 'pend' => 'S2', 'jabatan' => 'Asisten Ahli',  'keahlian' => 'Pemrograman Web'],
        ['nama' => 'Dr. Hendra Saputra, M.Kom.',  'nidn_sfx' => '8008', 'pend' => 'S3', 'jabatan' => 'Lektor',        'keahlian' => 'Keamanan Siber'],
        ['nama' => 'Irfan Maulana, M.Cs.',         'nidn_sfx' => '9009', 'pend' => 'S2', 'jabatan' => 'Tenaga Pengajar','keahlian' => 'Algoritma'],
        ['nama' => 'Joko Widodo, M.T.',            'nidn_sfx' => '8510', 'pend' => 'S2', 'jabatan' => 'Lektor',        'keahlian' => 'Komputasi Awan'],
    ];

    private array $poolDosenP = [
        ['nama' => 'Dr. Ani Wijayanti, M.Kom.',   'nidn_sfx' => '7511', 'pend' => 'S3', 'jabatan' => 'Lektor Kepala', 'keahlian' => 'Data Mining'],
        ['nama' => 'Bella Permatasari, M.T.',      'nidn_sfx' => '8012', 'pend' => 'S2', 'jabatan' => 'Asisten Ahli',  'keahlian' => 'Interaksi Manusia Komputer'],
        ['nama' => 'Dr. Citra Dewi, M.Si.',        'nidn_sfx' => '6513', 'pend' => 'S3', 'jabatan' => 'Lektor',        'keahlian' => 'Statistika Komputasi'],
    ];

    private array $poolDosenTidakTetap = [
        ['nama' => 'Rudi Susanto, M.M.',           'nidn_sfx' => '2001', 'pend' => 'S2', 'jabatan' => 'Tenaga Pengajar', 'keahlian' => 'Kewirausahaan'],
        ['nama' => 'Siti Rahayu, M.Pd.',           'nidn_sfx' => '3002', 'pend' => 'S2', 'jabatan' => 'Tenaga Pengajar', 'keahlian' => 'Bahasa Inggris'],
        ['nama' => 'Agus Prasetyo, M.Hum.',        'nidn_sfx' => '4003', 'pend' => 'S2', 'jabatan' => 'Tenaga Pengajar', 'keahlian' => 'Pancasila dan Kewarganegaraan'],
    ];

    /** Ambil daftar kode prodi yang terdaftar di DB, fallback ke kode dummy. */
    private function getProdiKodes(): array
    {
        $kodes = ProgramStudi::whereNotNull('feeder_kode_prodi')
            ->pluck('feeder_kode_prodi')
            ->toArray();

        return count($kodes) > 0 ? $kodes : ['00000'];
    }

    public function testConnection(): array
    {
        return [
            'success' => true,
            'message' => '[FAKE MODE] Koneksi simulasi berhasil. Aktifkan FEEDER_MODE=real untuk koneksi nyata.',
        ];
    }

    public function fetchMahasiswa(): array
    {
        $data     = [];
        $prodiKodes = $this->getProdiKodes();
        $nimSeq   = 1001;

        foreach ($prodiKodes as $idx => $kode) {
            // Angkatan 2021–2024, masing-masing ~40 mahasiswa per prodi
            foreach ([2021, 2022, 2023, 2024] as $angkatan) {
                for ($i = 0; $i < 40; $i++) {
                    $isL  = ($i % 2 === 0);
                    $pool = $isL ? $this->namaL : $this->namaP;
                    $nim  = $kode . $angkatan . str_pad($nimSeq++, 3, '0', STR_PAD_LEFT);

                    if ($angkatan === 2021) {
                        $status = ($i < 32) ? 'Lulus' : (($i < 36) ? 'Keluar' : 'Aktif');
                    } elseif ($angkatan === 2022) {
                        $status = ($i < 3) ? 'Keluar' : 'Aktif';
                    } else {
                        $status = 'Aktif';
                    }

                    $semester = match ($angkatan) {
                        2021 => ($status === 'Lulus') ? 8 : 9,
                        2022 => 6,
                        2023 => 4,
                        2024 => 2,
                    };

                    $data[] = [
                        'nim'            => $nim,
                        'nama'           => $pool[$i % count($pool)],
                        'jenis_kelamin'  => $isL ? 'L' : 'P',
                        'angkatan'       => $angkatan,
                        'semester_aktif' => $semester,
                        'status'         => $status,
                        'ipk'            => ($status !== 'Keluar') ? round(2.50 + mt_rand(0, 150) / 100, 2) : null,
                        'prodi_kode'     => $kode,
                    ];
                }
            }
        }

        return $data;
    }

    public function fetchDosen(): array
    {
        $data       = [];
        $prodiKodes = $this->getProdiKodes();

        foreach ($prodiKodes as $idx => $kode) {
            // Rotasi pool dosen agar tiap prodi dapat dosen berbeda
            $dosenL  = array_slice($this->poolDosenL, ($idx * 3) % count($this->poolDosenL));
            $dosenL  = array_merge($dosenL, array_slice($this->poolDosenL, 0, max(0, 10 - count($dosenL))));
            $dosenP  = $this->poolDosenP;

            foreach (array_merge(array_slice($dosenL, 0, 10), $dosenP) as $i => $d) {
                $data[] = [
                    'nidn'               => $kode . $d['nidn_sfx'],
                    'nama'               => $d['nama'],
                    'jenis_kelamin'      => str_contains($d['nama'], 'Ani|Bella|Citra') ? 'P' : 'L',
                    'pendidikan_terakhir'=> $d['pend'],
                    'jabatan_akademik'   => $d['jabatan'],
                    'status_ketenagaan'  => 'Tetap',
                    'bidang_keahlian'    => $d['keahlian'],
                    'prodi_kode'         => $kode,
                ];
            }

            foreach ($this->poolDosenTidakTetap as $d) {
                $data[] = [
                    'nidn'               => '99' . $kode . $d['nidn_sfx'],
                    'nama'               => $d['nama'],
                    'jenis_kelamin'      => 'L',
                    'pendidikan_terakhir'=> $d['pend'],
                    'jabatan_akademik'   => $d['jabatan'],
                    'status_ketenagaan'  => 'Tidak Tetap',
                    'bidang_keahlian'    => $d['keahlian'],
                    'prodi_kode'         => $kode,
                ];
            }
        }

        return $data;
    }

    public function fetchKelulusan(): array
    {
        $data       = [];
        $prodiKodes = $this->getProdiKodes();
        $nimSeq     = 5001;

        foreach ($prodiKodes as $kode) {
            foreach ([2019 => 2023, 2020 => 2024] as $angkatan => $tahunLulus) {
                for ($i = 0; $i < 35; $i++) {
                    $isL  = ($i % 2 === 0);
                    $pool = $isL ? $this->namaL : $this->namaP;
                    $nim  = $kode . $angkatan . str_pad($nimSeq++, 3, '0', STR_PAD_LEFT);

                    $semesterKe = match (true) {
                        $i < 25 => 8,
                        $i < 29 => 7,
                        $i < 33 => 9,
                        default => 10,
                    };

                    $data[] = [
                        'nim'         => $nim,
                        'nama'        => $pool[$i % count($pool)],
                        'angkatan'    => $angkatan,
                        'tahun_lulus' => $tahunLulus,
                        'semester_ke' => $semesterKe,
                        'ipk_lulus'   => round(2.75 + mt_rand(0, 125) / 100, 2),
                        'prodi_kode'  => $kode,
                    ];
                }
            }
        }

        return $data;
    }
}
