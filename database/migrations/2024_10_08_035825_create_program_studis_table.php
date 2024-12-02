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
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('program_studis_code')->unique();
            $table->string('prodi_nama');
            $table->string('prodi_jenjang');
            $table->string('prodi_jurusan');
            $table->string('prodi_fakultas');
            $table->string('prodi_akreditasi');
            $table->string('akreditasi_kadaluarsa');
            $table->string('akreditasi_bukti');
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
        Schema::dropIfExists('program_studis');
    }
};
