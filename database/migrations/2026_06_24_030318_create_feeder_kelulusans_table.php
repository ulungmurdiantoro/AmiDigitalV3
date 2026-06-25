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
        Schema::create('feeder_kelulusans', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 150);
            $table->year('angkatan');
            $table->year('tahun_lulus');
            $table->unsignedTinyInteger('semester_ke'); // berapa semester hingga lulus
            $table->decimal('ipk_lulus', 3, 2);
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
        Schema::dropIfExists('feeder_kelulusans');
    }
};
