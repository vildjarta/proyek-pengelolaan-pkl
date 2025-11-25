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
        Schema::table('penilaian_penguji', function (Blueprint $table) {
            // Cek dan hapus kolom id_penguji jika ada
            if (Schema::hasColumn('penilaian_penguji', 'id_penguji')) {
                $table->dropColumn('id_penguji');
            }
            
            // Cek dan hapus kolom nip jika ada
            if (Schema::hasColumn('penilaian_penguji', 'nip')) {
                $table->dropColumn('nip');
            }
            
            // Cek dan hapus kolom nama_dosen jika ada
            if (Schema::hasColumn('penilaian_penguji', 'nama_dosen')) {
                $table->dropColumn('nama_dosen');
            }
            
            // Tambah foreign key ke tabel dosen jika belum ada
            if (!Schema::hasColumn('penilaian_penguji', 'id_dosen')) {
                $table->unsignedBigInteger('id_dosen')->after('id');
                $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_penguji', function (Blueprint $table) {
            // Kembalikan struktur lama
            $table->dropForeign(['id_dosen']);
            $table->dropColumn('id_dosen');
            
            $table->string('nip');
            $table->string('nama_dosen');
        });
    }
};
