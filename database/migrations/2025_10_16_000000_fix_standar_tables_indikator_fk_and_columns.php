<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rapikan jalur DB tabel turunan indikator:
 *  - indikator_id (string, tanpa relasi) -> FK unsignedBigInteger ke `indikators` (cascade on delete).
 *  - lengkapi kolom yang ditulis aplikasi tapi belum ada (standar_capaians.dokumen_tipe, bukti_standar_id).
 *  - jadikan kolom opsional benar-benar nullable agar tidak gagal insert.
 *
 * Dibuat SETELAH migration `indikators` (2025_07_29) supaya target FK sudah ada.
 */
return new class extends Migration
{
    /** Tabel yang punya kolom indikator_id -> dijadikan FK ke indikators. */
    private array $fkTables = ['standar_targets', 'standar_capaians', 'standar_nilais', 'standar_outputs'];

    public function up(): void
    {
        // 1) Lengkapi kolom yang ditulis aplikasi tapi belum ada di tabel.
        Schema::table('standar_capaians', function (Blueprint $table) {
            if (!Schema::hasColumn('standar_capaians', 'dokumen_tipe')) {
                $table->string('dokumen_tipe')->nullable()->after('pertanyaan_nama');
            }
            if (!Schema::hasColumn('standar_capaians', 'bukti_standar_id')) {
                $table->unsignedBigInteger('bukti_standar_id')->nullable()->after('indikator_id');
            }
        });

        // 2) Kolom opsional -> nullable (cegah error insert pada alur yang memang membiarkannya kosong).
        Schema::table('standar_targets', function (Blueprint $table) {
            $table->string('dokumen_keterangan')->nullable()->change();
        });

        Schema::table('standar_capaians', function (Blueprint $table) {
            $table->string('dokumen_keterangan')->nullable()->change();
            $table->string('informasi')->nullable()->change();
            $table->date('dokumen_kadaluarsa')->nullable()->change();
        });

        Schema::table('standar_nilais', function (Blueprint $table) {
            // Hasil temuan & rencana tindak lanjut diisi bertahap oleh auditor.
            foreach ([
                'hasil_kriteria', 'hasil_deskripsi', 'jenis_temuan', 'hasil_akibat', 'hasil_masalah', 'hasil_rekomendasi',
                'hasil_rencana_perbaikan', 'hasil_jadwal_perbaikan', 'hasil_perbaikan_penanggung',
                'hasil_rencana_pencegahan', 'hasil_jadwal_pencegahan', 'hasil_rencana_penanggung', 'status_akhir',
            ] as $col) {
                $table->string($col)->nullable()->change();
            }
        });

        // 3) indikator_id: string -> unsignedBigInteger + FK ke indikators (cascade on delete).
        foreach ($this->fkTables as $t) {
            // Buang baris yatim (indikator_id tak ada di indikators) agar constraint bisa dibuat.
            DB::table($t)->whereNotIn('indikator_id', function ($q) {
                $q->select('id')->from('indikators');
            })->delete();

            Schema::table($t, function (Blueprint $table) {
                $table->unsignedBigInteger('indikator_id')->change();
            });

            Schema::table($t, function (Blueprint $table) {
                $table->foreign('indikator_id')->references('id')->on('indikators')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->fkTables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropForeign(['indikator_id']);
            });
            Schema::table($t, function (Blueprint $table) {
                $table->string('indikator_id')->change();
            });
        }

        Schema::table('standar_capaians', function (Blueprint $table) {
            if (Schema::hasColumn('standar_capaians', 'dokumen_tipe')) {
                $table->dropColumn('dokumen_tipe');
            }
            if (Schema::hasColumn('standar_capaians', 'bukti_standar_id')) {
                $table->dropColumn('bukti_standar_id');
            }
        });
    }
};
