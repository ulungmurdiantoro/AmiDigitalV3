<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class LkpsExport implements WithMultipleSheets
{
    public function __construct(
        private array  $data,
        private string $prodi,
        private string $periode,
    ) {}

    public function sheets(): array
    {
        return [
            new LkpsTabel4aSheet($this->data['tabel_4a'] ?? [], $this->prodi, $this->periode),
            new LkpsTabel6aSheet($this->data['tabel_6a'] ?? [], $this->prodi, $this->periode),
            new LkpsTabel6bSheet($this->data['tabel_6b'] ?? [], $this->prodi, $this->periode),
            new LkpsTabel6dSheet($this->data['tabel_6d'] ?? [], $this->prodi, $this->periode),
        ];
    }
}

// ── Tabel 4.a. — Profil DTPS ─────────────────────────────────────────────────

class LkpsTabel4aSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data, private string $prodi, private string $periode) {}

    public function title(): string { return 'Tabel 4.a. DTPS'; }

    public function headings(): array
    {
        return ['No', 'NIDN', 'Nama Dosen', 'Jabatan Fungsional', 'Pendidikan Terakhir', 'Bidang Keahlian', 'Status'];
    }

    public function collection(): Collection
    {
        $rows = [];
        foreach ($this->data['dtps'] ?? [] as $i => $d) {
            $rows[] = [
                $i + 1,
                $d['nidn']          ?? '-',
                $d['nama'],
                $d['jabatan']       ?? '-',
                $d['pendidikan']    ?? '-',
                $d['bidang_keahlian'] ?? '-',
                'Tetap (DTPS)',
            ];
        }
        foreach ($this->data['dtt'] ?? [] as $i => $d) {
            $rows[] = [
                count($this->data['dtps'] ?? []) + $i + 1,
                $d['nidn']          ?? '-',
                $d['nama'],
                $d['jabatan']       ?? '-',
                $d['pendidikan']    ?? '-',
                $d['bidang_keahlian'] ?? '-',
                'Tidak Tetap (DTT)',
            ];
        }
        return collect($rows);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

// ── Tabel 6.a. — Data Mahasiswa ───────────────────────────────────────────────

class LkpsTabel6aSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data, private string $prodi, private string $periode) {}

    public function title(): string { return 'Tabel 6.a. Mahasiswa'; }

    public function headings(): array
    {
        return ['Tahun Masuk (Angkatan)', 'Jumlah Diterima', 'Aktif', 'Lulus', 'Keluar / Tidak Aktif'];
    }

    public function collection(): Collection
    {
        return collect(array_map(fn($r) => [
            $r['angkatan'],
            $r['diterima'],
            $r['aktif'],
            $r['lulus'],
            $r['keluar'],
        ], $this->data['rows'] ?? []));
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

// ── Tabel 6.b. — IPK Lulusan ─────────────────────────────────────────────────

class LkpsTabel6bSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data, private string $prodi, private string $periode) {}

    public function title(): string { return 'Tabel 6.b. IPK'; }

    public function headings(): array
    {
        return ['Tahun Lulus', 'Jumlah Lulusan', 'IPK Min', 'IPK Maks', 'IPK Rata-rata (RIPK)'];
    }

    public function collection(): Collection
    {
        return collect(array_map(fn($r) => [
            $r['tahun_lulus'],
            $r['jumlah_lulusan'],
            $r['ipk_min'],
            $r['ipk_max'],
            $r['ipk_rata'],
        ], $this->data));
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

// ── Tabel 6.d. — Masa Studi & Kelulusan Tepat Waktu ─────────────────────────

class LkpsTabel6dSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data, private string $prodi, private string $periode) {}

    public function title(): string { return 'Tabel 6.d. Tepat Waktu'; }

    public function headings(): array
    {
        return ['Tahun Lulus', 'Jumlah Lulusan', 'Rata-rata Masa Studi (Thn)', 'Lulus Tepat Waktu (≤8 Sem)', '% Tepat Waktu'];
    }

    public function collection(): Collection
    {
        return collect(array_map(fn($r) => [
            $r['tahun_lulus'],
            $r['jumlah'],
            $r['rata_masa_studi'],
            $r['tepat_waktu'],
            $r['pct_tepat'] . '%',
        ], $this->data));
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
