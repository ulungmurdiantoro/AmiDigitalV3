<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standar_elemen_infokom_terapan_s2_s', function (Blueprint $table) {
            $table->id();
            $table->string('indikator_kode');
            $table->string('standar_nama');
            $table->string('elemen_nama');
            $table->longText('indikator_nama');
            $table->longText('indikator_info');
            $table->string('indikator_lkps');
            $table->float('indikator_bobot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_elemen_infokom_terapan_s2_s');
    }
};
