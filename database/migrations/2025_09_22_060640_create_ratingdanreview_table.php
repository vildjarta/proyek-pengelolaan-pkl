<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rating_dan_reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_perusahaan');
            $table->tinyInteger('rating')->comment('1-5');
            $table->text('review')->nullable();
            $table->date('tanggal_review');
            $table->timestamps();

            // 🔗 Relasi Foreign Key
            $table->foreign('id_mahasiswa')
                  ->references('id_mahasiswa')   // kolom primary key di tabel mahasiswa
                  ->on('mahasiswa')
                  ->onDelete('cascade');         // hapus otomatis jika mahasiswa dihapus

            $table->foreign('id_perusahaan')
                  ->references('id_perusahaan')  // kolom primary key di tabel perusahaan
                  ->on('perusahaan')
                  ->onDelete('cascade');         // hapus otomatis jika perusahaan dihapus
        });
    }

    public function down(): void {
        Schema::dropIfExists('rating_dan_reviews');
    }
};
