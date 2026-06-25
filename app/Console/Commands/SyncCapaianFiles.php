<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCapaianFiles extends Command
{
    protected $signature = 'ami:sync-capaian-files
                            {--prodi=S1 - Ilmu Hukum}
                            {--periode=2026/2027}
                            {--backup-dir=H:/My Drive/Ulung/SIPEMUKA UNSIKA/backup/homedir/u1738934/public_html/sipemuka.com/upload/document/capaian}
                            {--dry-run : Tampilkan preview tanpa menyalin file}';

    protected $description = 'Copy file capaian dari backup lama ke storage baru dan update path di database';

    public function handle(): int
    {
        $prodi     = $this->option('prodi');
        $periode   = $this->option('periode');
        $backupDir = rtrim($this->option('backup-dir'), '/\\');
        $dryRun    = $this->option('dry-run');
        $destDir   = storage_path('app/public/uploads/capaian/prodi');

        if (!is_dir($backupDir)) {
            $this->error("Direktori backup tidak ditemukan: {$backupDir}");
            return self::FAILURE;
        }

        if (!$dryRun && !is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // Load indikator kode mapping
        $amiKodes = DB::table('indikators')->pluck('indikator_kode', 'id'); // id → kode

        // Ambil capaian di ami_local yang file-nya masih kosong
        $targets = DB::table('standar_capaians')
            ->where('prodi', $prodi)
            ->where('periode', $periode)
            ->where(fn($q) => $q->where('dokumen_file', '')->orWhereNull('dokumen_file'))
            ->get();

        $this->info("Ditemukan {$targets->count()} record tanpa file untuk {$prodi} | {$periode}");

        if ($dryRun) $this->warn("MODE DRY-RUN");

        $copied = 0; $notFound = 0; $skipped = 0;

        $bar = $this->output->createProgressBar($targets->count());
        $bar->start();

        foreach ($targets as $target) {
            $indKode = $amiKodes[$target->indikator_id] ?? null;
            if (!$indKode) { $bar->advance(); $notFound++; continue; }

            // Cari record di unsika berdasarkan indikator_kode (dengan atau tanpa prefix S1-)
            $unsikaRow = DB::connection('mysql')->table('unsika.standar_capaian')
                ->where(function($q) use ($indKode) {
                    $q->where('indikator_kode', $indKode)
                      ->orWhere('indikator_kode', 'S1-' . $indKode);
                })
                ->where('dokumen_nama', $target->dokumen_nama)
                ->whereNotNull('dokumen_file')
                ->where('dokumen_file', '!=', '')
                ->first();

            if (!$unsikaRow) {
                // Fallback: cari by indikator_kode saja, ambil yang terpanjang keterangannya
                $unsikaRow = DB::connection('mysql')->table('unsika.standar_capaian')
                    ->where(function($q) use ($indKode) {
                        $q->where('indikator_kode', $indKode)
                          ->orWhere('indikator_kode', 'S1-' . $indKode);
                    })
                    ->whereNotNull('dokumen_file')
                    ->where('dokumen_file', '!=', '')
                    ->orderByRaw('LENGTH(dokumen_keterangan) DESC')
                    ->first();
            }

            if (!$unsikaRow || !$unsikaRow->dokumen_file) {
                $bar->advance(); $notFound++; continue;
            }

            $srcFile  = $backupDir . DIRECTORY_SEPARATOR . $unsikaRow->dokumen_file;
            $destFile = $destDir . DIRECTORY_SEPARATOR . $unsikaRow->dokumen_file;
            $newPath  = '/storage/uploads/capaian/prodi/' . $unsikaRow->dokumen_file;

            if (!file_exists($srcFile)) {
                $bar->advance(); $notFound++; continue;
            }

            if (!$dryRun) {
                copy($srcFile, $destFile);
                DB::table('standar_capaians')
                    ->where('id', $target->id)
                    ->update(['dokumen_file' => $newPath, 'updated_at' => now()]);
            }

            $copied++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->table(['Status', 'Jumlah'], [
            ['File disalin & path diupdate', $copied],
            ['File tidak ditemukan di backup', $notFound],
            ['Skip', $skipped],
        ]);

        return self::SUCCESS;
    }
}
