<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void {
        Schema::create('rating_dan_reviews', function (Blueprint $table) {
            $table->id('id_review');
            
            // Kolom id_mahasiswa: unsignedBigInteger (Sesuai dengan table mahasiswa)
            $table->unsignedBigInteger('id_mahasiswa'); 
            
            // KOREKSI: Kolom id_perusahaan harus unsignedInteger (Sesuai dengan table perusahaan yang INT)
            $table->unsignedInteger('id_perusahaan'); 
            
            $table->tinyInteger('rating')->comment('1-5');
            $table->text('review')->nullable();
            $table->date('tanggal_review');
            $table->timestamps();

            // 🔗 Relasi Foreign Key MAHASISWA
            $table->foreign('id_mahasiswa')
                  ->references('id_mahasiswa')   // kolom primary key di tabel mahasiswa (BIGINT)
                  ->on('mahasiswa')
                  ->onDelete('cascade');

            // 🔗 Relasi Foreign Key PERUSAHAAN
            $table->foreign('id_perusahaan')
                  ->references('id_perusahaan')  // kolom primary key di tabel perusahaan (INT)
                  ->on('perusahaan')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Kembalikan (rollback) migrasi.
     */
    public function down(): void {
        Schema::dropIfExists('rating_dan_reviews');
    }
};