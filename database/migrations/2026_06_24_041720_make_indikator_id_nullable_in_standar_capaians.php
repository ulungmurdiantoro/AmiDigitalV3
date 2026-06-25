<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('standar_capaians', function (Blueprint $table) {
            $table->unsignedBigInteger('indikator_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('standar_capaians', function (Blueprint $table) {
            $table->unsignedBigInteger('indikator_id')->nullable(false)->change();
        });
    }
};
