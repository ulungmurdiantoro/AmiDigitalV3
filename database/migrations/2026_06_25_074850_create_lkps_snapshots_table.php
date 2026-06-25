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
        Schema::create('lkps_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('prodi');
            $table->string('prodi_kode')->nullable();
            $table->string('periode');
            $table->json('data');
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['prodi', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkps_snapshots');
    }
};
