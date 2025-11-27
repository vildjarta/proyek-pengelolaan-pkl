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
            
            // Jika kolom id_dosen belum ada, tambahkan
            if (!Schema::hasColumn('penilaian_penguji', 'id_dosen')) {
                $table->unsignedBigInteger('id_dosen')->after('id');
            }
        });
        
        // Tambah foreign key constraint dalam schema terpisah
        Schema::table('penilaian_penguji', function (Blueprint $table) {
            // Cek apakah foreign key sudah ada, jika belum tambahkan
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
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
