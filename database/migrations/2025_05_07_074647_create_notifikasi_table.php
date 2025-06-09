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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->timestamps();

            $table->string('pesan');
            $table->boolean('is_read')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('waktu_kirim');
            $table->enum('tipe_notifikasi', ['ALERT', 'WARNING', 'INFO']);

            $table->unsignedBigInteger('id_user');

            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
