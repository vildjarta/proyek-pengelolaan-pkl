<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan ke database.
     */
    public function up(): void
    {
        Schema::table('penilaian_dospem', function (Blueprint $table) {
            // Pastikan kolom mahasiswa_id ada sebelum menggantinya
            if (Schema::hasColumn('penilaian_dospem', 'mahasiswa_id')) {
                $table->renameColumn('mahasiswa_id', 'id_mahasiswa');
            }
        });
    }

    /**
     * Batalkan perubahan (rollback).
     */
    public function down(): void
    {
        Schema::table('penilaian_dospem', function (Blueprint $table) {
            if (Schema::hasColumn('penilaian_dospem', 'id_mahasiswa')) {
                $table->renameColumn('id_mahasiswa', 'mahasiswa_id');
            }
        });
    }
};
