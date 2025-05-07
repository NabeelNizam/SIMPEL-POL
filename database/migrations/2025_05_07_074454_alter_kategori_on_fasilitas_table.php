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
            $table->dropColumn('kategori');
            $table->dropColumn('kondisi');

            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_kondisi');

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->foreign('id_kondisi')->references('id_kondisi')->on('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
