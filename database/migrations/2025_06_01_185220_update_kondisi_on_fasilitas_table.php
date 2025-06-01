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
        Schema::table('fasilitas', function (Blueprint $table){
            $table->dropColumn('kondisi');
        });
        Schema::table('fasilitas', function (Blueprint $table){
            $table->enum('kondisi', ['LAYAK', 'RUSAK'])->default('LAYAK')->after('urgensi');
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
