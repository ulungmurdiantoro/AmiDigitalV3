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
        Schema::create('standards', function (Blueprint $table) {
    $table->id();
    $table->foreignId('standar_akreditasi_id')->constrained('standar_akreditasis')->onDelete('cascade');
    $table->foreignId('jenjang_id')->constrained('jenjangs')->onDelete('cascade');
    $table->text('nama');
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
        Schema::dropIfExists('standards');
    }
};
