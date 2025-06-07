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
        // Drop perbaikan's foreing on aduan
        Schema::table('aduan', function (Blueprint $table) {
            $table->dropForeign(['id_perbaikan']);
            $table->dropColumn('id_perbaikan');
        });
        // Drop perbaikan table
        Schema::dropIfExists('perbaikan');

        // Create inspeksi table
        Schema::create('inspeksi', function (Blueprint $table) {
            $table->id('id_inspeksi');

            $table->foreignId('id_user_teknisi')->constrained('users', 'id_user');
            $table->foreignId('id_user_sarpras')->constrained('users', 'id_user');
            $table->foreignId('id_fasilitas')->constrained('fasilitas', 'id_fasilitas')
                ->cascadeOnDelete();
            $table->foreignId('id_periode')->constrained('periode', 'id_periode');

            $table->enum('tingkat_kerusakan', ['RINGAN', 'SEDANG', 'PARAH'])
                ->comment('Tingkat kerusakan fasilitas yang diinspeksi')
                ->nullable();

            $table->text('deskripsi')->nullable()->comment('Deskripsi|rincian inspeksi');

            $table->date('tanggal_mulai')->comment('Tanggal mulai inspeksi');
            $table->date('tanggal_selesai')->comment('Tanggal selesai inspeksi')->nullable();

            $table->timestamps();
        });

        // reattach biaya to inspeksi
        Schema::table('biaya', function (Blueprint $table) {
            // $table->dropForeign(['id_perbaikan']);
            $table->dropColumn('id_perbaikan');
            $table->foreignId('id_inspeksi')->after('id_biaya')->constrained('inspeksi', 'id_inspeksi')
                ->cascadeOnDelete();
        });

        // re-create perbaikan table
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id('id_perbaikan');

            $table->foreignId('id_inspeksi')->constrained('inspeksi', 'id_inspeksi')
                ->cascadeOnDelete();
            $table->foreignId('id_periode')->constrained('periode', 'id_periode');

            $table->text('deskripsi')->nullable()
                ->comment('Deskripsi perbaikan yang dilakukan');

            $table->date('tanggal_mulai')->comment('Tanggal mulai perbaikan');
            $table->date('tanggal_selesai')->comment('Tanggal selesai perbaikan')->nullable();

            $table->timestamps();
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
