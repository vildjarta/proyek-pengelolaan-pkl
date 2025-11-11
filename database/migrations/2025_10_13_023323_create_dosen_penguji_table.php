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
            $table->unsignedBigInteger('id_dosen');
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
