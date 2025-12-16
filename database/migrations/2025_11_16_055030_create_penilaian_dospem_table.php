<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian_dospem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('nama_mahasiswa');
            $table->string('judul');
            $table->integer('presentasi');
            $table->integer('laporan');
            $table->integer('penguasaan');
            $table->integer('sikap');
            $table->text('catatan')->nullable();

            // Kolom Foreign Key untuk dosen
            $table->unsignedBigInteger('id_pembimbing');

            $table->timestamps();

            // Relasi ke tabel mahasiswa
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');

            // Relasi ke tabel dosen_pembimbing (Ini sudah sesuai dengan tabel Anda)
            $table->foreign('id_pembimbing')->references('id_pembimbing')->on('dosen_pembimbing')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_dospem');
    }
};
