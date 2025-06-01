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
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->string('deskripsi_perbaikan')->nullable()->change();
            $table->dropColumn('tingkat_kerusakan');
            $table->dateTime('tanggal_mulai')->nullable()->change();
            $table->dateTime('tanggal_selesai')->nullable()->change();

        });
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->enum('tingkat_kerusakan', ['PARAH', 'SEDANG', 'RINGAN'])->nullable()->after('deskripsi_perbaikan');
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
