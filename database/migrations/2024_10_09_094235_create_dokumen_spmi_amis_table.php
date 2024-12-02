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
        Schema::create('dokumen_spmi_amis', function (Blueprint $table) {
            $table->id();
            $table->string('dokumen_kode');
            $table->string('kategori_dokumen');
            $table->string('nama_dokumen');
            $table->string('file_spmi_ami');
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
        Schema::dropIfExists('dokumen_spmi_amis');
    }
};
