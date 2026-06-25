<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Indikator;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Jenjang;
use App\Models\StandarAkreditasi;
use App\Models\StandarTarget;

/**
 * Seed standar_targets untuk semua jenjang BAN-PT (IAPTS 5.1 / PerBAN-PT 36/2025).
 *
 * S1 dibaca dari CSV. Jenjang lain (D1-D3, S1/S2/S3 Terapan) menggunakan
 * template S1 yang di-map ulang berdasarkan kesamaan nama_indikator.
 *
 * Jalankan setelah KriteriaBanptSeeder.
 */
class KriteriaBanptTargetSeeder extends Seeder
{
    protected array $otherJenjang = ['D1', 'D2', 'D3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'];

    public function run(): void
    {
        $this->seedS1();
        $this->seedOtherJenjang();
    }

    // ── S1 dari CSV ───────────────────────────────────────────────────────────

    protected function seedS1(): void
    {
        $csvPath = database_path('data/BAN-PT/banpt_s1_targets.csv');
        if (!file_exists($csvPath)) {
            $this->command?->error("File tidak ditemukan: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->command?->error("Tidak bisa membuka CSV.");
            return;
        }

        fgetcsv($handle, 0, ';'); // skip header

        StandarTarget::where('jenjang', 'BAN-PT S1')->delete();

        $seq = $created = $skipped = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 7) { $skipped++; continue; }

            [$indId, , , $dokNama, $pertNama, $tipe, $ket] = $row;
            $indId   = (int) trim($indId);
            $dokNama = trim($dokNama);
            $pertNama = trim($pertNama);
            $tipe    = trim($tipe) ?: 'Dokumen';
            $ket     = trim($ket);

            if (!$indId || !$dokNama) { $skipped++; continue; }
            if (!Indikator::where('id', $indId)->exists()) { $skipped++; continue; }

            $seq++;
            StandarTarget::create([
                'jenjang'            => 'BAN-PT S1',
                'target_kode'        => 'BANPT-S1-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                'indikator_id'       => $indId,
                'pertanyaan_nama'    => $pertNama ?: $dokNama,
                'dokumen_nama'       => $dokNama,
                'dokumen_tipe'       => $tipe,
                'dokumen_keterangan' => $ket ?: null,
            ]);
            $created++;
        }

        fclose($handle);
        $this->command?->info("BAN-PT S1: {$created} targets (dilewati: {$skipped}).");
    }

    // ── Jenjang lain: map ulang dari S1 via nama_indikator ───────────────────

    protected function seedOtherJenjang(): void
    {
        $akr = StandarAkreditasi::where('nama', 'BAN-PT')->first();
        if (!$akr) {
            $this->command?->warn("StandarAkreditasi BAN-PT tidak ditemukan.");
            return;
        }

        // Ambil template S1 beserta nama_indikator-nya
        $s1Targets = StandarTarget::where('jenjang', 'BAN-PT S1')
            ->get()
            ->map(function ($t) {
                $t->nama_indikator = Indikator::where('id', $t->indikator_id)->value('nama_indikator');
                return $t;
            });

        if ($s1Targets->isEmpty()) {
            $this->command?->warn("BAN-PT S1 targets belum ada. Jalankan seedS1() dulu.");
            return;
        }

        foreach ($this->otherJenjang as $jenjangNama) {
            $jenjang = Jenjang::where('nama', $jenjangNama)->first();
            if (!$jenjang) {
                $this->command?->warn("  Jenjang '{$jenjangNama}' tidak ditemukan, lewati.");
                continue;
            }

            $jenjangKey = 'BAN-PT ' . $jenjangNama;
            $prefix     = 'BANPT-' . strtoupper(str_replace([' ', '-'], '', $jenjangNama));

            // Index semua indikator jenjang ini berdasarkan nama_indikator
            $indikatorByName = Indikator::whereIn('elemen_id',
                Element::whereIn('standard_id',
                    Standard::where('standar_akreditasi_id', $akr->id)
                            ->where('jenjang_id', $jenjang->id)
                            ->pluck('id')
                )->pluck('id')
            )->get(['id', 'nama_indikator'])
             ->keyBy('nama_indikator');

            StandarTarget::where('jenjang', $jenjangKey)->delete();

            $seq = $created = $notFound = 0;

            foreach ($s1Targets as $tmpl) {
                $ind = $indikatorByName->get($tmpl->nama_indikator);
                if (!$ind) { $notFound++; continue; }

                $seq++;
                StandarTarget::create([
                    'jenjang'            => $jenjangKey,
                    'target_kode'        => $prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                    'indikator_id'       => $ind->id,
                    'pertanyaan_nama'    => $tmpl->pertanyaan_nama,
                    'dokumen_nama'       => $tmpl->dokumen_nama,
                    'dokumen_tipe'       => $tmpl->dokumen_tipe,
                    'dokumen_keterangan' => $tmpl->dokumen_keterangan,
                ]);
                $created++;
            }

            $this->command?->info("BAN-PT {$jenjangNama}: {$created} targets (tdk cocok: {$notFound}).");
        }
    }
}
