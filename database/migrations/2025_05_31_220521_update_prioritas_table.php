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
        Schema::table('prioritas', function (Blueprint $table) {
            $table->dropForeign(['id_aduan']);
            $table->dropColumn('id_aduan');

            $table->unsignedBigInteger('id_perbaikan')->after('id_prioritas');
            $table->foreign('id_perbaikan')->references('id_perbaikan')->on('perbaikan');
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
