<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            // Primary Key
            $table->id('id_perusahaan'); // BIGINT UNSIGNED AUTO_INCREMENT

            // Informasi dasar perusahaan
            $table->string('nama', 255);
            $table->string('alamat', 255)->nullable();
            $table->string('status', 50)->nullable()->comment('Contoh: Aktif / Tidak Aktif');
            $table->string('bidang_usaha', 100)->nullable();
            $table->string('fasilitas', 150)->nullable();
            $table->string('level_legalitas', 100)->nullable();
            $table->string('jumlah_mahasiswa', 50)->nullable();
            $table->string('hari_operasi', 100)->nullable();

            // Lokasi geografis (latitude & longitude)
            $table->decimal('lat', 10, 7)->nullable()->comment('Latitude');
            $table->decimal('lng', 10, 7)->nullable()->comment('Longitude');

            // Timestamp
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
