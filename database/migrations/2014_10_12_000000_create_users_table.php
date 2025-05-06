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
            $table->id('id_user');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('nama');
            $table->enum('role', ['ADMIN', 'TEKNISI', 'MAHASISWA', 'DOSEN', 'TENDIK', 'SARANA_PRASARANA']);
            $table->string('no_hp');
            $table->binary('foto_profil');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
