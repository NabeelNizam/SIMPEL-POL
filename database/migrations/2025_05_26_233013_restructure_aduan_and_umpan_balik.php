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
            $table->dropForeign(['id_umpan_balik']);
            $table->dropColumn('id_umpan_balik');
        });
        Schema::table('umpan_balik', function (Blueprint $table) {
            $table->unsignedBigInteger('id_aduan');
        });
        Schema::table('gedung', function (Blueprint $table) {
            $table->string('nama_gedung')->change();
        });
        Schema::table('perbaikan', function (Blueprint $table) {
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
