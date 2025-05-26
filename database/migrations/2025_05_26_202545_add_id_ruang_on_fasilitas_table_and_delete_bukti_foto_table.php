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
        Schema::table('fasilitas', function (Blueprint $table) {
           $table->unsignedBigInteger('id_ruangan');

           $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
        Schema::dropIfExists('bukti_foto');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropForeign(['id_ruang']);
            $table->dropColumn('id_ruang');
        });
    }
};
