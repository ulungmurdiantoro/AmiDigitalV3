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
        Schema::create('penjadwalan_amis', function (Blueprint $table) {
            $table->id();
            $table->string('jadwal_kode');
            $table->string('auditor_kode')->references('auditor_kode')->on('auditor_amis')->cascadeOnDelete();
            $table->string('informasi_tambahan');
            $table->string('prodi');
            $table->string('fakultas');
            $table->string('standar_akreditasi');
            $table->string('periode');
            $table->date('opening_ami');
            $table->string('pengisian_dokumen');
            $table->string('deskevaluasion');
            $table->string('assessment');
            $table->string('tindakan_koreksi');
            $table->string('laporan_ami');
            $table->date('rtm');
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
        Schema::dropIfExists('penjadwalan_amis');
    }
};
