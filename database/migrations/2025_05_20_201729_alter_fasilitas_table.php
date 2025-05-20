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
            $table->renameColumn('kode', 'kode_fasilitas');

            $table->dropForeign(['id_kondisi']);
            $table->dropColumn('id_kondisi');

            $table->enum('kondisi', ['BAIK', 'RUSAK', 'RUSAK BERAT'])->default('BAIK');
        });

        Schema::dropIfExists('kondisi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
