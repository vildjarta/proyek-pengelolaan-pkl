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
            $table->string('nip', 20);
            $table->string('nama_dosen', 100);
            $table->string('nama_mahasiswa', 100);
            $table->string('judul', 255);
            $table->text('sikap')->nullable();
            $table->text('penguasaan')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->date('tanggal_ujian')->nullable();
            $table->string('jenis_ujian', 50)->nullable();
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('penilaian_penguji');
    }
};
