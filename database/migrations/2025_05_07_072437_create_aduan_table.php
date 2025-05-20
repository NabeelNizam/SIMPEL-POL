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
        Schema::create('aduan', function (Blueprint $table) {
            $table->id('id_aduan');
            $table->timestamps();

            $table->date('tanggal_aduan');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_fasilitas');
            $table->unsignedBigInteger('id_umpan_balik');
            $table->unsignedBigInteger('id_penanganan');
            $table->unsignedBigInteger('id_prioritas');
            $table->unsignedBigInteger('id_bukti_foto');


            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas');
            $table->foreign('id_umpan_balik')->references('id_umpan_balik')->on('umpan_balik');
            $table->foreign('id_penanganan')->references('id_penanganan')->on('penanganan');
            $table->foreign('id_prioritas')->references('id_prioritas')->on('prioritas');
            $table->foreign('id_bukti_foto')->references('id_bukti_foto')->on('bukti_foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
