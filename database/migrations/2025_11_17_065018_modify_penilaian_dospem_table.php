<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penilaian_dospem', function (Blueprint $table) {
            // Hapus kolom-kolom lama
            $table->dropColumn('presentasi');
            $table->dropColumn('laporan');
            $table->dropColumn('penguasaan');

            // Tambahkan kolom-kolom baru setelah kolom 'judul'
            $table->integer('penguasaan_teori')->after('judul');
            $table->integer('analisis_pemecahan_masalah')->after('penguasaan_teori');
            $table->integer('keaktifan_bimbingan')->after('analisis_pemecahan_masalah');
            $table->integer('penulisan_laporan')->after('keaktifan_bimbingan');
            // Kolom 'sikap' sudah ada, jadi tidak perlu ditambahkan lagi.
        });
    }

    /**
     * Batalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penilaian_dospem', function (Blueprint $table) {
            // Tambahkan kembali kolom lama jika migrasi dibatalkan (rollback)
            $table->integer('presentasi');
            $table->integer('laporan');
            $table->integer('penguasaan');

            // Hapus kolom-kolom baru
            $table->dropColumn('penguasaan_teori');
            $table->dropColumn('analisis_pemecahan_masalah');
            $table->dropColumn('keaktifan_bimbingan');
            $table->dropColumn('penulisan_laporan');
        });
    }
};