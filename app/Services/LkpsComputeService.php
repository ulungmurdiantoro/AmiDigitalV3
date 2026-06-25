<?php

namespace App\Services;

use App\Models\FeederDosen;
use App\Models\FeederKelulusan;
use App\Models\FeederMahasiswa;
use App\Models\ProgramStudi;

class LkpsComputeService
{
    private string $prodiKode;

    public function __construct(string $prodiKode)
    {
        $this->prodiKode = $prodiKode;
    }

    public static function forProdi(string $prodi): ?self
    {
        $model = ProgramStudi::whereRaw("CONCAT(prodi_jenjang, ' - ', prodi_nama) = ?", [$prodi])
            ->whereNotNull('feeder_kode_prodi')
            ->first();

        return $model ? new self($model->feeder_kode_prodi) : null;
    }

    public function hasData(): bool
    {
        return FeederMahasiswa::where('prodi_kode', $this->prodiKode)->exists();
    }

    public function compute(): array
    {
        return [
            'tabel_4a'   => $this->tabel4a(),   // Profil DTPS per dosen
            'tabel_6a'   => $this->tabel6a(),   // Data mahasiswa per angkatan
            'tabel_6b'   => $this->tabel6b(),   // IPK lulusan
            'tabel_6d'   => $this->tabel6d(),   // Masa studi & kelulusan tepat waktu
            // summary aggregates used in display cards
            'ringkasan'  => $this->ringkasan(),
            'computed_at' => now()->toDateTimeString(),
        ];
    }

    // ── Tabel 4.a. LKPS — Profil DTPS (per dosen) ───────────────────────────

    private function tabel4a(): array
    {
        $dtps = FeederDosen::where('prodi_kode', $this->prodiKode)
            ->where('status_ketenagaan', 'Tetap')
            ->orderBy('nama')
            ->get(['nidn', 'nama', 'jabatan_akademik', 'pendidikan_terakhir', 'bidang_keahlian'])
            ->map(fn($d) => [
                'nidn'          => $d->nidn,
                'nama'          => $d->nama,
                'jabatan'       => $d->jabatan_akademik,
                'pendidikan'    => $d->pendidikan_terakhir,
                'bidang_keahlian' => $d->bidang_keahlian,
            ])
            ->toArray();

        $dtt = FeederDosen::where('prodi_kode', $this->prodiKode)
            ->where('status_ketenagaan', 'Tidak Tetap')
            ->orderBy('nama')
            ->get(['nidn', 'nama', 'jabatan_akademik', 'pendidikan_terakhir', 'bidang_keahlian'])
            ->map(fn($d) => [
                'nidn'          => $d->nidn,
                'nama'          => $d->nama,
                'jabatan'       => $d->jabatan_akademik,
                'pendidikan'    => $d->pendidikan_terakhir,
                'bidang_keahlian' => $d->bidang_keahlian,
            ])
            ->toArray();

        return ['dtps' => $dtps, 'dtt' => $dtt];
    }

    // ── Tabel 6.a. LKPS — Data Mahasiswa per Angkatan ────────────────────────

    private function tabel6a(): array
    {
        $base = FeederMahasiswa::where('prodi_kode', $this->prodiKode);

        $angkatanList = (clone $base)
            ->selectRaw('angkatan')
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->pluck('angkatan')
            ->toArray();

        $rows = [];
        foreach ($angkatanList as $tahun) {
            $diterima = (clone $base)->where('angkatan', $tahun)->count();
            $aktif    = (clone $base)->where('angkatan', $tahun)->where('status', 'Aktif')->count();
            $lulus    = (clone $base)->where('angkatan', $tahun)->where('status', 'Lulus')->count();
            $keluar   = (clone $base)->where('angkatan', $tahun)->where('status', 'Keluar')->count();

            $rows[] = [
                'angkatan' => $tahun,
                'diterima' => $diterima,
                'aktif'    => $aktif,
                'lulus'    => $lulus,
                'keluar'   => $keluar,
            ];
        }

        $totalAktif = (clone $base)->where('status', 'Aktif')->count();

        return ['rows' => $rows, 'total_aktif' => $totalAktif];
    }

    // ── Tabel 6.b. LKPS — IPK Lulusan ────────────────────────────────────────

    private function tabel6b(): array
    {
        return FeederKelulusan::where('prodi_kode', $this->prodiKode)
            ->selectRaw('tahun_lulus,
                count(*) as jumlah_lulusan,
                round(min(ipk_lulus),2) as ipk_min,
                round(max(ipk_lulus),2) as ipk_max,
                round(avg(ipk_lulus),2) as ipk_rata')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get()
            ->map(fn($r) => [
                'tahun_lulus'    => $r->tahun_lulus,
                'jumlah_lulusan' => $r->jumlah_lulusan,
                'ipk_min'        => (float) $r->ipk_min,
                'ipk_max'        => (float) $r->ipk_max,
                'ipk_rata'       => (float) $r->ipk_rata,
            ])
            ->toArray();
    }

    // ── Tabel 6.d. LKPS — Masa Studi & Kelulusan Tepat Waktu ─────────────────

    private function tabel6d(): array
    {
        return FeederKelulusan::where('prodi_kode', $this->prodiKode)
            ->selectRaw('tahun_lulus,
                count(*) as jumlah,
                round(avg(semester_ke)/2, 2) as rata_masa_studi,
                sum(case when semester_ke <= 8 then 1 else 0 end) as tepat_waktu')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get()
            ->map(fn($r) => [
                'tahun_lulus'    => $r->tahun_lulus,
                'jumlah'         => $r->jumlah,
                'rata_masa_studi'=> (float) $r->rata_masa_studi,
                'tepat_waktu'    => $r->tepat_waktu,
                'pct_tepat'      => $r->jumlah > 0
                    ? round($r->tepat_waktu / $r->jumlah * 100, 1)
                    : 0,
            ])
            ->toArray();
    }

    // ── Ringkasan (summary cards) ─────────────────────────────────────────────

    private function ringkasan(): array
    {
        $dtps = FeederDosen::where('prodi_kode', $this->prodiKode)
            ->where('status_ketenagaan', 'Tetap');

        $totalDtps  = (clone $dtps)->count();
        $totalDtt   = FeederDosen::where('prodi_kode', $this->prodiKode)
            ->where('status_ketenagaan', 'Tidak Tetap')->count();
        $mhsAktif   = FeederMahasiswa::where('prodi_kode', $this->prodiKode)
            ->where('status', 'Aktif')->count();

        $pendidikan = (clone $dtps)
            ->selectRaw('pendidikan_terakhir, count(*) as total')
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir')
            ->toArray();

        $jabatan = (clone $dtps)
            ->selectRaw('jabatan_akademik, count(*) as total')
            ->groupBy('jabatan_akademik')
            ->pluck('total', 'jabatan_akademik')
            ->toArray();

        $doktor  = $pendidikan['S3'] ?? ($pendidikan['Doktor'] ?? 0);
        $gb      = $jabatan['Guru Besar']    ?? 0;
        $lk      = $jabatan['Lektor Kepala'] ?? 0;
        $l       = $jabatan['Lektor']        ?? 0;

        return [
            'mhs_aktif'   => $mhsAktif,
            'dtps'        => $totalDtps,
            'dtt'         => $totalDtt,
            'rasio'       => $totalDtps > 0 ? round($mhsAktif / $totalDtps, 2) : 0,
            'pct_doktor'  => $totalDtps > 0 ? round($doktor / $totalDtps * 100, 1) : 0,
            'pct_gblkl'   => $totalDtps > 0 ? round(($gb + $lk + $l) / $totalDtps * 100, 1) : 0,
            'jabatan'     => $jabatan,
            'pendidikan'  => $pendidikan,
        ];
    }
}
