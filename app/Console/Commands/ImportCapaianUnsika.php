<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCapaianUnsika extends Command
{
    protected $signature = 'ami:import-capaian-unsika
                            {--prodi=S1 - Ilmu Hukum : Target prodi di ami_local}
                            {--periode=2026/2027 : Target periode di ami_local}
                            {--akreditasi=BAN-PT : Nama akreditasi target (BAN-PT / LAMEMBA / dll)}
                            {--jenjang=S1 : Nama jenjang target (S1 / D3 / S2)}
                            {--source-prodi= : Filter prodi sumber dari unsika (kosong = semua S1)}
                            {--fresh : Hapus data lama sebelum import ulang}
                            {--dry-run : Tampilkan preview tanpa menyimpan}';

    protected $description = 'Import SEMUA metadata standar_capaian dari unsika ke ami_local (semua dokumen per indikator)';

    public function handle(): int
    {
        $targetProdi   = $this->option('prodi');
        $targetPeriode = $this->option('periode');
        $akreditasi    = $this->option('akreditasi');
        $jenjang       = $this->option('jenjang');
        $sourceProdi   = $this->option('source-prodi');
        $dryRun        = $this->option('dry-run');
        $fresh         = $this->option('fresh');

        $this->info("Target  : prodi={$targetProdi} | periode={$targetPeriode}");
        $this->info("Akreditasi: {$akreditasi} | Jenjang: {$jenjang}");
        $this->info("Sumber  : " . ($sourceProdi ?: 'semua S1 di unsika'));
        if ($dryRun) $this->warn("MODE DRY-RUN — tidak ada yang disimpan");

        // Hapus data lama jika --fresh
        if ($fresh && !$dryRun) {
            $deleted = DB::table('standar_capaians')
                ->where('prodi', $targetProdi)
                ->where('periode', $targetPeriode)
                ->delete();
            $this->warn("Hapus {$deleted} record lama.");
        }

        // Load mapping indikator_kode → id KHUSUS untuk akreditasi + jenjang yang benar
        $amiKodes = DB::table('indikators as i')
            ->join('elements as e', 'i.elemen_id', '=', 'e.id')
            ->join('standards as s', 'e.standard_id', '=', 's.id')
            ->join('standar_akreditasis as sa', 's.standar_akreditasi_id', '=', 'sa.id')
            ->join('jenjangs as j', 's.jenjang_id', '=', 'j.id')
            ->where('sa.nama', $akreditasi)
            ->where('j.nama', $jenjang)
            ->pluck('i.id', 'i.indikator_kode');

        $this->info("Indikator ditemukan untuk {$akreditasi}/{$jenjang}: " . $amiKodes->count());

        // Ambil SEMUA capaian dari unsika
        $query = DB::connection('mysql')
            ->table('unsika.standar_capaian')
            ->where('prodi', 'like', 'S1%')
            ->orderBy('num');

        if ($sourceProdi) {
            $query->where('prodi', $sourceProdi);
        }

        $rows = $query->get();
        $this->info("Ditemukan {$rows->count()} baris dari unsika");

        // Capaian yang sudah ada (hindari duplikat berdasarkan indikator_id + dokumen_nama)
        $existingKeys = DB::table('standar_capaians')
            ->where('prodi', $targetProdi)
            ->where('periode', $targetPeriode)
            ->select('indikator_id', 'dokumen_nama')
            ->get()
            ->map(fn($r) => $r->indikator_id . '|' . $r->dokumen_nama)
            ->flip()
            ->toArray();

        $toInsert     = [];
        $skippedNoMap = 0;
        $skippedExist = 0;

        foreach ($rows as $row) {
            $stripped = preg_replace('/^[A-Z]\d*-/', '', $row->indikator_kode);

            $indikatorId = $amiKodes[$stripped]
                ?? $amiKodes[preg_replace('/[A-Z]+$/', '', $stripped)]
                ?? null;

            if (!$indikatorId) { $skippedNoMap++; continue; }

            // Skip duplikat (indikator_id + nama dokumen sama)
            $key = $indikatorId . '|' . $row->dokumen_nama;
            if (isset($existingKeys[$key])) { $skippedExist++; continue; }
            $existingKeys[$key] = true;

            $toInsert[] = [
                'capaian_kode'       => 'cpn-' . Str::uuid(),
                'indikator_id'       => $indikatorId,
                'bukti_standar_id'   => null,
                'dokumen_nama'       => $row->dokumen_nama,
                'pertanyaan_nama'    => null,
                'dokumen_tipe'       => $row->dokumen_tipe ?? '',
                'dokumen_keterangan' => mb_substr($row->dokumen_keterangan ?? '', 0, 255),
                'dokumen_file'       => '',
                'dokumen_kadaluarsa' => null,
                'informasi'          => $row->informasi ?? null,
                'periode'            => $targetPeriode,
                'prodi'              => $targetProdi,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        $this->info("Siap diimport: " . count($toInsert) . " dokumen");
        $this->info("Skip no-map: {$skippedNoMap} | Skip duplikat: {$skippedExist}");

        if (!$dryRun && !$this->confirm("Lanjutkan import ke ami_local?")) {
            $this->info("Dibatalkan.");
            return self::SUCCESS;
        }

        if (!$dryRun) {
            $bar = $this->output->createProgressBar(count($toInsert));
            $bar->start();
            foreach (array_chunk($toInsert, 100) as $chunk) {
                DB::table('standar_capaians')->insert($chunk);
                $bar->advance(count($chunk));
            }
            $bar->finish();
            $this->newLine();
        }

        $this->info("Selesai!");
        $this->table(['Status', 'Jumlah'], [
            ['Diimport',                   count($toInsert)],
            ['Skip (tidak ada map kode)',   $skippedNoMap],
            ['Skip (duplikat nama+indikator)', $skippedExist],
        ]);

        return self::SUCCESS;
    }
}
