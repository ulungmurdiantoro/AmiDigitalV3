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
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elemen_id')->constrained('elements')->onDelete('cascade');
            $table->text('nama_indikator');
            $table->string('kategori')->nullable();
            $table->text('info')->nullable();
            $table->text('lkps')->nullable();
            $table->decimal('bobot', 5, 2)->nullable();
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
        Schema::dropIfExists('indikators');
    }
};
