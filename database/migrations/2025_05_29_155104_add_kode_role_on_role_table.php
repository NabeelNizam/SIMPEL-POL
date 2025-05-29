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
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['nama_role']);
            $table->string('nama_role')->change();
            $table->string('kode_role', 15)->unique();
        });
        Schema::table('gedung', function (Blueprint $table) {
            $table->string('kode_gedung')->unique()->change();
        });
        Schema::table('ruangan', function (Blueprint $table) {
            $table->dropUnique(['nama_ruangan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('kode_role');
            $table->string('nama_role')->unique()->change();
        });
    }
};
