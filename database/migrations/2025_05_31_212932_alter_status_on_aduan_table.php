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
            // $table->enum('status', ['MENUNGGU_DIPROSES', 'SEDANG_INSPEKSI', 'SEDANG_DIPERBAIKI', 'SELESAI'])->change();
            $table->dropColumn('status');
        });
        Schema::table('aduan', function (Blueprint $table) {
            $table->enum('status', ['MENUNGGU_DIPROSES', 'SEDANG_INSPEKSI', 'SEDANG_DIPERBAIKI', 'SELESAI']);
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
