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
        Schema::create('jadwal_bimbingans', function (Blueprint $table) {
            $table->id();
            
            // DIUBAH: Sesuai input teks di form, bukan relasi ID
            $table->string('mahasiswa')->nullable();
            $table->string('dosen')->nullable();

            $table->date('tanggal');

            // DIUBAH: Sesuai form yang meminta rentang waktu
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');

            // DISESUAIKAN: Dibuat nullable karena di form bersifat opsional
            $table->string('topik')->nullable(); 
            
            // Kolom ini sudah sesuai dengan form Anda
            $table->text('catatan')->nullable(); 
            
            // Kolom ini tidak ada di form, tapi kita simpan untuk status internal
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_bimbingans');
    }
};