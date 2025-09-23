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
        $table->foreignId('mahasiswa_id')->constrained('users'); // Asumsi tabel users untuk mahasiswa
        $table->foreignId('dosen_id')->constrained('users');     // Asumsi tabel users untuk dosen
        $table->date('tanggal');
        $table->time('waktu');
        $table->string('topik');
        $table->text('catatan')->nullable();
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
