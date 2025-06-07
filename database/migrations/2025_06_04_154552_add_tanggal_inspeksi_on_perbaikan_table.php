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
            $table->date('tanggal_inspeksi')->nullable(); // ini tanggal ditangkap pas teknisi selesai inspeksi (berarti dia filled bareng kolom tingkat kerusakan, biaya, dsb)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->dropColumn('tanggal_inspeksi'); // rollback, hapus kolom tanggal_inspeksi
        });
    }
};
