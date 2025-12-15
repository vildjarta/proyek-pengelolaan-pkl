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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->string('nama', 255);
            $table->string('alamat', 255);
            $table->string('status', 50);
            $table->string('bidang_usaha', 100);
            $table->string('fasilitas', 100);
            $table->string('level_legalitas', 100);
            $table->string('jumlah_mahasiswa', 100);
            $table->string('hari_operasi', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
