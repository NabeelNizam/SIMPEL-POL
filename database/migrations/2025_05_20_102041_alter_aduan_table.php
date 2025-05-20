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
            // Rename kolom user
            $table->renameColumn('id_user', 'id_user_pelapor');

            // Tambahkan kolom baru
            $table->string('deskripsi');
            $table->enum('status', ['MENUNGGU_VERIFIKASI', 'SEDANG_INSPEKSI', 'SEDANG_DIPERBAIKI', 'SELESAI']);
            $table->string('bukti_foto')->nullable(); // path gambar
        });

        // Drop foreign keys & kolom dalam blok terpisah untuk menghindari konflik
        Schema::table('aduan', function (Blueprint $table) {
            // Drop FK dan kolom id_bukti_foto
            if (Schema::hasColumn('aduan', 'id_bukti_foto')) {
                $table->dropForeign(['id_bukti_foto']);
                $table->dropColumn('id_bukti_foto');
            }

            // Drop FK dan kolom id_prioritas
            if (Schema::hasColumn('aduan', 'id_prioritas')) {
                $table->dropForeign(['id_prioritas']);
                $table->dropColumn('id_prioritas');
            }

            // Drop FK dan kolom id_penanganan
            if (Schema::hasColumn('aduan', 'id_penanganan')) {
                $table->dropForeign(['id_penanganan']);
                $table->dropColumn('id_penanganan');
            }
        });

        // Drop table terkait setelah semua FK ke tabel itu dihapus
        Schema::dropIfExists('penanganan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
