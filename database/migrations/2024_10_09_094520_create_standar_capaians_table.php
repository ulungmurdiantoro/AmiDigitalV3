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
        Schema::create('standar_capaians', function (Blueprint $table) {
            $table->id();
            $table->string('capaian_kode');
            $table->string('indikator_id');
            $table->string('dokumen_nama');
            $table->string('pertanyaan_nama');
            $table->string('dokumen_keterangan');
            $table->string('dokumen_file');
            $table->date('dokumen_kadaluarsa');
            $table->string('informasi');
            $table->string('periode');
            $table->string('prodi');
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
        Schema::dropIfExists('standar_capaians');
    }
};
