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
        Schema::table('program_studis', function (Blueprint $table) {
            $table->unsignedBigInteger('standar_akreditasi_id')->nullable()->after('prodi_fakultas');
            $table->foreign('standar_akreditasi_id')
                  ->references('id')->on('standar_akreditasis')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropForeign(['standar_akreditasi_id']);
            $table->dropColumn('standar_akreditasi_id');
        });
    }
};
