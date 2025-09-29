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
        Schema::create('standar_targets', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang');
            $table->string('target_kode');
            $table->string('indikator_id');
            $table->string('pertanyaan_nama');
            $table->string('dokumen_nama');
            $table->string('dokumen_tipe');
            $table->string('dokumen_keterangan');
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
        Schema::dropIfExists('standar_targets');
    }
};
