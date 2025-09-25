<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalBimbinganTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mahasiswa');
            $table->string('dosen_pembimbing');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('topik')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_bimbingan');
    }
}
