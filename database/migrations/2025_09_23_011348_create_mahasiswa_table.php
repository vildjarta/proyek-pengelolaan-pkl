<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('no_hp')->nullable();
            $table->string('prodi');
            $table->string('angkatan');
            $table->decimal('ipk', 3, 2)->nullable();
            $table->unsignedBigInteger('id_pembimbing')->nullable();
            $table->timestamps();

            // Jika kamu punya tabel pembimbing, tambahkan FK-nya di sini:
            // $table->foreign('id_pembimbing')->references('id_pembimbing')->on('pembimbing')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
