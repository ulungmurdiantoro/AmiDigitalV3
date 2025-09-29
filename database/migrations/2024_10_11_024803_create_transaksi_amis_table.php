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
        Schema::create('transaksi_amis', function (Blueprint $table) {
            $table->id();
            $table->string('ami_kode');
            $table->string('auditor_kode');
            $table->text('informasi_tambahan')->nullable();
            $table->string('prodi');
            $table->string('fakultas');
            $table->string('standar_akreditasi');
            $table->string('periode');
            $table->string('status');
            $table->text('alasan')->nullable();
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
        Schema::dropIfExists('transaksi_amis');
    }
};
