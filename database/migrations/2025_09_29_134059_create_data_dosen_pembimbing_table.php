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
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id('id_pembimbing'); // Primary Key
            $table->string('NIP'); // tidak unique, bukan foreign key
            $table->string('nama');
            $table->string('email'); // tidak unique, bukan foreign key
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            // jika suatu saat mau aktifkan relasi ke tabel users:
            // $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};
