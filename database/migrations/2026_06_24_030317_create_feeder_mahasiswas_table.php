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
        Schema::create('feeder_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->year('angkatan');
            $table->unsignedTinyInteger('semester_aktif')->default(1);
            $table->enum('status', ['Aktif', 'Cuti', 'Lulus', 'Keluar'])->default('Aktif');
            $table->decimal('ipk', 3, 2)->nullable();
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
        Schema::dropIfExists('feeder_mahasiswas');
    }
};
