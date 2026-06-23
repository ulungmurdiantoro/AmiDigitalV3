<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StandarAkreditasi;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;

class HapusDataLamsamaLamteknik extends Command
{
    protected $signature = 'app:hapus-lamsama-lamteknik {--force : Jalankan tanpa konfirmasi}';
    protected $description = 'Hapus semua Standards, Elements, dan Indikators untuk LAMSAMA dan LAMTEKNIK (rollback seeder yang salah)';

    public function handle(): int
    {
        $names = ['LAMSAMA', 'LAMTEKNIK'];

        $akreditasiList = StandarAkreditasi::whereIn('nama', $names)->get();

        if ($akreditasiList->isEmpty()) {
            $this->warn('Tidak ditemukan StandarAkreditasi LAMSAMA atau LAMTEKNIK di database.');
            return 0;
        }

        $akreditasiIds = $akreditasiList->pluck('id');
        $standardIds   = Standard::whereIn('standar_akreditasi_id', $akreditasiIds)->pluck('id');
        $elementIds    = Element::whereIn('standard_id', $standardIds)->pluck('id');

        $cInd = Indikator::whereIn('elemen_id', $elementIds)->count();
        $cEl  = $elementIds->count();
        $cStd = $standardIds->count();

        $this->table(['Tabel', 'Jumlah akan dihapus'], [
            ['indikators', $cInd],
            ['elements',   $cEl],
            ['standards',  $cStd],
        ]);

        if (!$this->option('force') && !$this->confirm('Lanjutkan penghapusan?', false)) {
            $this->info('Dibatalkan.');
            return 0;
        }

        Indikator::whereIn('elemen_id', $elementIds)->delete();
        Element::whereIn('standard_id', $standardIds)->delete();
        Standard::whereIn('standar_akreditasi_id', $akreditasiIds)->delete();

        $this->info("Selesai. Dihapus: {$cInd} indikator, {$cEl} elemen, {$cStd} standar (LAMSAMA + LAMTEKNIK).");
        return 0;
    }
}
