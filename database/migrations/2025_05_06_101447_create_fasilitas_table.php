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
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id('id_fasilitas');
            $table->timestamps();

            $table->string('nama_fasilitas');
            $table->string('kode')->unique();
            $table->enum('kategori', ['GAK TAU BELUM DIKASIH TAU NABEEL']);
            $table->enum('kondisi', ['MASIH NUNGGU NABEEL']);
            $table->string('deskripsi');

            $table->unsignedBigInteger('id_lokasi');


            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
