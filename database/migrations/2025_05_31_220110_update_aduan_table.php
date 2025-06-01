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
        Schema::table('aduan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perbaikan')->nullable()->after('id_fasilitas');
            $table->foreign('id_perbaikan')->references('id_perbaikan')->on('perbaikan');

            $table->unsignedBigInteger('id_periode')->after('id_perbaikan');
            $table->foreign('id_periode')->references('id_periode')->on('periode');
        });

        Schema::table('umpan_balik', function (Blueprint $table) {
            $table->foreign('id_aduan')->references('id_aduan')->on('aduan');
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
