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
        Schema::create('auditor_amis', function (Blueprint $table) {
            $table->id();
            $table->string('auditor_kode');
            $table->string('users_kode')->references('users_kode')->on('users')->cascadeOnDelete();
            $table->string('tim_ami');
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
        Schema::dropIfExists('auditor_amis');
    }
};
