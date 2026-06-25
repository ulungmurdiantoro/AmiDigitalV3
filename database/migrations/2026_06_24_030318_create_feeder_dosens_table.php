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
        Schema::create('feeder_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nidn', 20)->unique();
            $table->string('nama', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('pendidikan_terakhir', ['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Profesi']);
            $table->enum('jabatan_akademik', ['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar']);
            $table->enum('status_ketenagaan', ['Tetap', 'Tidak Tetap'])->default('Tetap');
            $table->string('bidang_keahlian', 150)->nullable();
            $table->string('prodi_kode', 20)->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeder_dosens');
    }
};
