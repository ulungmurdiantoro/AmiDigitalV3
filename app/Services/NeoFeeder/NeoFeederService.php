<?php

namespace App\Services\NeoFeeder;

use App\Models\FeederDosen;
use App\Models\FeederKelulusan;
use App\Models\FeederMahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NeoFeederService
{
    private NeoFeederDriverInterface $driver;
    private ?string $kodeProdi = null;

    public function __construct()
    {
        $this->driver = config('feeder.mode') === 'real'
            ? new RealNeoFeederDriver()
            : new FakeNeoFeederDriver();
    }

    /** Filter semua query ke prodi tertentu. */
    public function forProdi(ProgramStudi|string $prodi): static
    {
        $this->kodeProdi = $prodi instanceof ProgramStudi
            ? $prodi->feeder_kode_prodi
            : $prodi;
        return $this;
    }

    public function isFakeMode(): bool
    {
        return config('feeder.mode') !== 'real';
    }

    public function testConnection(): array
    {
        return $this->driver->testConnection();
    }

    // ── Sync ──────────────────────────────────────────────────────────────────

    public function syncAll(): array
    {
        return [
            'mahasiswa' => $this->syncMahasiswa(),
            'dosen'     => $this->syncDosen(),
            'kelulusan' => $this->syncKelulusan(),
        ];
    }

    public function syncMahasiswa(): array
    {
        try {
            $rows = $this->driver->fetchMahasiswa();
            $now  = Carbon::now();

            DB::table('feeder_mahasiswas')->truncate();
            foreach (array_chunk($rows, 200) as $chunk) {
                FeederMahasiswa::insert(array_map(
                    fn($r) => array_merge($r, ['synced_at' => $now, 'created_at' => $now, 'updated_at' => $now]),
                    $chunk
                ));
            }
            return ['status' => 'ok', 'total' => count($rows)];
        } catch (\Throwable $e) {
            Log::error('feeder:sync mahasiswa — ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function syncDosen(): array
    {
        try {
            $rows = $this->driver->fetchDosen();
            $now  = Carbon::now();

            DB::table('feeder_dosens')->truncate();
            foreach (array_chunk($rows, 100) as $chunk) {
                FeederDosen::insert(array_map(
                    fn($r) => array_merge($r, ['synced_at' => $now, 'created_at' => $now, 'updated_at' => $now]),
                    $chunk
                ));
            }
            return ['status' => 'ok', 'total' => count($rows)];
        } catch (\Throwable $e) {
            Log::error('feeder:sync dosen — ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function syncKelulusan(): array
    {
        try {
            $rows = $this->driver->fetchKelulusan();
            $now  = Carbon::now();

            DB::table('feeder_kelulusans')->truncate();
            foreach (array_chunk($rows, 200) as $chunk) {
                FeederKelulusan::insert(array_map(
                    fn($r) => array_merge($r, ['synced_at' => $now, 'created_at' => $now, 'updated_at' => $now]),
                    $chunk
                ));
            }
            return ['status' => 'ok', 'total' => count($rows)];
        } catch (\Throwable $e) {
            Log::error('feeder:sync kelulusan — ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── Query builder helpers (scope by prodi if set) ─────────────────────────

    private function mhsQuery()
    {
        $q = FeederMahasiswa::query();
        if ($this->kodeProdi) $q->where('prodi_kode', $this->kodeProdi);
        return $q;
    }

    private function dosenQuery()
    {
        $q = FeederDosen::query();
        if ($this->kodeProdi) $q->where('prodi_kode', $this->kodeProdi);
        return $q;
    }

    private function kelulusanQuery()
    {
        $q = FeederKelulusan::query();
        if ($this->kodeProdi) $q->where('prodi_kode', $this->kodeProdi);
        return $q;
    }

    // ── Statistik Mahasiswa ───────────────────────────────────────────────────

    public function jumlahMahasiswaAktif(): int
    {
        return $this->mhsQuery()->where('status', 'Aktif')->count();
    }

    public function mahasiswaPerAngkatan(): array
    {
        return $this->mhsQuery()
            ->where('status', 'Aktif')
            ->selectRaw('angkatan, count(*) as total')
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->pluck('total', 'angkatan')
            ->toArray();
    }

    public function mahasiswaBaruPerTahun(): array
    {
        return $this->mhsQuery()
            ->selectRaw('angkatan, count(*) as total')
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->pluck('total', 'angkatan')
            ->toArray();
    }

    // ── Statistik Dosen ───────────────────────────────────────────────────────

    public function jumlahDpr(): int
    {
        return $this->dosenQuery()->where('status_ketenagaan', 'Tetap')->count();
    }

    public function jumlahDtt(): int
    {
        return $this->dosenQuery()->where('status_ketenagaan', 'Tidak Tetap')->count();
    }

    public function rasioMahasiswaDosen(): float
    {
        $dpr = $this->jumlahDpr();
        return $dpr > 0 ? round($this->jumlahMahasiswaAktif() / $dpr, 2) : 0;
    }

    public function sebaranJabatanDpr(): array
    {
        return $this->dosenQuery()
            ->where('status_ketenagaan', 'Tetap')
            ->selectRaw('jabatan_akademik, count(*) as total')
            ->groupBy('jabatan_akademik')
            ->pluck('total', 'jabatan_akademik')
            ->toArray();
    }

    public function sebaranPendidikanDpr(): array
    {
        return $this->dosenQuery()
            ->where('status_ketenagaan', 'Tetap')
            ->selectRaw('pendidikan_terakhir, count(*) as total')
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir')
            ->toArray();
    }

    public function daftarDpr(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->dosenQuery()
            ->where('status_ketenagaan', 'Tetap')
            ->orderBy('nama')
            ->get();
    }

    // ── Statistik Kelulusan ───────────────────────────────────────────────────

    public function ipkRataRataLulusan(): array
    {
        return $this->kelulusanQuery()
            ->selectRaw('tahun_lulus, round(avg(ipk_lulus),2) as rata_rata, count(*) as total')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get()
            ->keyBy('tahun_lulus')
            ->toArray();
    }

    public function kelulusanTepetWaktuPersen(int $maksimalSemester = 8): array
    {
        $result = [];
        $rows   = $this->kelulusanQuery()
            ->selectRaw("tahun_lulus, count(*) as total,
                sum(case when semester_ke <= {$maksimalSemester} then 1 else 0 end) as tepat")
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get();

        foreach ($rows as $row) {
            $result[$row->tahun_lulus] = [
                'total'  => $row->total,
                'tepat'  => $row->tepat,
                'persen' => $row->total > 0 ? round($row->tepat / $row->total * 100, 1) : 0,
            ];
        }

        return $result;
    }

    // ── Ringkasan lengkap untuk satu prodi ───────────────────────────────────

    public function ringkasanProdi(): array
    {
        return [
            'mahasiswa_aktif'  => $this->jumlahMahasiswaAktif(),
            'dpr'              => $this->jumlahDpr(),
            'dtt'              => $this->jumlahDtt(),
            'rasio'            => $this->rasioMahasiswaDosen(),
            'jabatan_dpr'      => $this->sebaranJabatanDpr(),
            'pendidikan_dpr'   => $this->sebaranPendidikanDpr(),
            'ipk_lulusan'      => $this->ipkRataRataLulusan(),
            'kelulusan_tepat'  => $this->kelulusanTepetWaktuPersen(),
            'mhs_per_angkatan' => $this->mahasiswaBaruPerTahun(),
        ];
    }
}
