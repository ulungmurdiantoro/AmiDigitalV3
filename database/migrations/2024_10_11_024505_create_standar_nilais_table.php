<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standar_nilais', function (Blueprint $table) {
            $table->id();
            $table->string('ami_kode');
            $table->string('indikator_kode');
            $table->float('mandiri_nilai');
            $table->float('hasil_nilai');
            $table->float('bobot');
            $table->string('hasil_kriteria');
            $table->string('hasil_deskripsi');
            $table->string('jenis_temuan');
            $table->string('hasil_akibat');
            $table->string('hasil_masalah');
            $table->string('hasil_rekomendasi');
            $table->string('hasil_rencana_perbaikan');
            $table->string('hasil_jadwal_perbaikan');
            $table->string('hasil_perbaikan_penanggung');
            $table->string('hasil_rencana_pencegahan');
            $table->string('hasil_jadwal_pencegahan');
            $table->string('hasil_rencana_penanggung');
            $table->string('status_akhir');
            $table->string('prodi');
            $table->string('periode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standar_nilais');
    }
};
