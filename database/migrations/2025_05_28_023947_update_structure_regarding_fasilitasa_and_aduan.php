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
            $table->dropForeign(['id_aduan']);
            $table->dropColumn('id_aduan');

            $table->unsignedBigInteger('id_fasilitas')->after('id_perbaikan');
            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas');
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
