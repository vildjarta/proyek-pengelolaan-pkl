<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gunakan nama tabel yang konsisten: 'jadwal_bimbingans' (plural)
        Schema::create('jadwal_bimbingans', function (Blueprint $table) {
            $table->id();

            // Kolom untuk Foreign Key ke tabel mahasiswa
            $table->unsignedBigInteger('id_mahasiswa')->nullable();
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('set null');

            // Kolom untuk Foreign Key ke tabel dosen_pembimbing
            $table->unsignedBigInteger('id_pembimbing')->nullable();
            $table->foreign('id_pembimbing')->references('id_pembimbing')->on('dosen_pembimbing')->onDelete('set null');

            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('topik')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_bimbingans');
    }
};
