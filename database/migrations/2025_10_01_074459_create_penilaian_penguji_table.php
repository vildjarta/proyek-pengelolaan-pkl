<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('penilaian_penguji', function (Blueprint $table) {
    $table->id();
    $table->string('nip');
    $table->string('nama_dosen');
    $table->string('nama_mahasiswa');
    $table->float('presentasi')->nullable();
    $table->float('materi')->nullable();
    $table->float('hasil')->nullable();
    $table->float('objektif')->nullable();
    $table->float('laporan')->nullable();
    $table->float('total_nilai')->nullable();
    $table->float('nilai_akhir')->nullable();
    $table->date('tanggal_ujian')->nullable();
    $table->timestamps();
});

    }


    public function down(): void
    {
        Schema::dropIfExists('penilaian_penguji');
    }
};
