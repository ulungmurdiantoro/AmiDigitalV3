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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('users_code')->unique();
            $table->string('user_id');
            $table->string('user_nama');
            $table->string('user_jabatan');
            $table->string('user_penempatan');
            $table->string('user_fakultas')->nullable();
            $table->string('user_akses')->nullable();
            $table->string('user_pelatihan')->nullable();
            $table->string('user_sertfikat')->nullable();
            $table->string('user_sk')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('user_level');
            $table->string('user_status');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
