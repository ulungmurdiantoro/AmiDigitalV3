<?php

namespace App\Console\Commands;

use App\Services\NeoFeeder\NeoFeederService;
use Illuminate\Console\Command;

class SyncFeederCommand extends Command
{
    protected $signature   = 'feeder:sync {--entity= : mahasiswa|dosen|kelulusan (default: semua)}';
    protected $description = 'Sinkronisasi data dari Neo Feeder PDDikti ke database lokal';

    public function handle(): int
    {
        $service = new NeoFeederService();
        $entity  = $this->option('entity');

        if ($service->isFakeMode()) {
            $this->warn('[FAKE MODE] Data yang disinkronkan adalah data dummy.');
        }

        $targets = match ($entity) {
            'mahasiswa'  => ['mahasiswa'],
            'dosen'      => ['dosen'],
            'kelulusan'  => ['kelulusan'],
            default      => ['mahasiswa', 'dosen', 'kelulusan'],
        };

        foreach ($targets as $target) {
            $this->info("Sinkronisasi {$target}...");
            $result = match ($target) {
                'mahasiswa' => $service->syncMahasiswa(),
                'dosen'     => $service->syncDosen(),
                'kelulusan' => $service->syncKelulusan(),
            };

            if ($result['status'] === 'ok') {
                $this->line("  <fg=green>✓</> {$result['total']} record berhasil disimpan.");
            } else {
                $this->error("  Gagal: {$result['message']}");
                return self::FAILURE;
            }
        }

        $this->info('Sinkronisasi selesai.');
        return self::SUCCESS;
    }
}
