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
            $table->string('feeder_kode_prodi', 20)->nullable()->after('prodi_akreditasi')
                ->comment('Kode program studi di PDDikti Neo Feeder');
        });
    }

    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn('feeder_kode_prodi');
        });
    }
};
