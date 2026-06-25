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
        Schema::table('standar_capaians', function (Blueprint $table) {
            $table->string('pertanyaan_nama')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('standar_capaians', function (Blueprint $table) {
            $table->string('pertanyaan_nama')->nullable(false)->change();
        });
    }
};
