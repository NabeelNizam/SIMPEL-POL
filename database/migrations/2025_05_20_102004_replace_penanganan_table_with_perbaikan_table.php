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
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id('id_perbaikan');

            $table->string('deskripsi_perbaikan');
            $table->enum('tingkat_kerusakan', ['PARAH', 'SEDANG', 'RINGAN']);

            $table->unsignedBigInteger('id_user_teknisi');
            $table->unsignedBigInteger('id_user_sarpras');
            $table->unsignedBigInteger('id_aduan');


            $table->timestamp('tanggal_mulai');
            $table->timestamp('tanggal_selesai');

            $table->foreign('id_user_teknisi')->references('id_user')->on('users');
            $table->foreign('id_user_sarpras')->references('id_user')->on('users');
            $table->foreign('id_aduan')->references('id_aduan')->on('aduan');
        });
        // Schema::dropIfExists('penanganan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no one rollbacking anyway
    }
};
