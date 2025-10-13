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
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('nama_mahasiswa');
            $table->string('judul');
            $table->integer('presentasi');
            $table->integer('laporan');
            $table->integer('penguasaan');
            $table->integer('sikap');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('dospem_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
            // PASTIKAN INI JUGA MERUJUK KE 'dospem'
            $table->foreign('dospem_id')->references('id')->on('dospem')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_dospem');
    }
};