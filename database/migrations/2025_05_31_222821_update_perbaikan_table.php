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
            $table->unsignedBigInteger('id_periode')->after('id_fasilitas');
            $table->foreign('id_periode')->references('id_periode')->on('periode');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
