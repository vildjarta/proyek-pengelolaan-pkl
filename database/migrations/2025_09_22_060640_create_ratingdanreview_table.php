<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rating_dan_review', function (Blueprint $table) {
            $table->id('id_review');
            
            // Perbaikan: Gunakan unsignedBigInteger untuk ID manual
            // $table->foreignId('id_mahasiswa')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_mahasiswa');
            
            // Perbaikan: Tambahkan kolom id_perusahaan
            $table->unsignedBigInteger('id_perusahaan');

            $table->tinyInteger('rating')->comment('1-5');
            $table->text('review')->nullable();
            $table->date('tanggal_review'); // Hapus default now() agar bisa diisi manual
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rating_dan_review');
    }
};