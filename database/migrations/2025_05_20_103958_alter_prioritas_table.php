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
            $table->dropColumn('nama_prioritas');

            $table->unsignedBigInteger('id_aduan');

            $table->integer('aduan');

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
