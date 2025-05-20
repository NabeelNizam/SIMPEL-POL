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
        Schema::create('gedung', function (Blueprint $table) {
            $table->id('id_gedung');
            $table->string('kode_gedung', 10);
            $table->string('nama_gedung');

            $table->unsignedBigInteger('id_jurusan');
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');

            $table->timestamps();
        });
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropForeign(['id_lokasi']);
            $table->dropColumn('id_lokasi');
        });
        Schema::dropIfExists('lokasi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gedung', function (Blueprint $table) {
            //
        });
    }
};
