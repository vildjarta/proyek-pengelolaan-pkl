<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('dosen_penguji', function (Blueprint $table) {
            $table->id('id_penguji'); // Primary key
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('nip', 50)->unique(); // Nomor induk dosen
            $table->string('nama_dosen', 255);
            $table->string('email', 255)->unique();
            $table->string('no_hp', 20)->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_penguji');
    }
};
