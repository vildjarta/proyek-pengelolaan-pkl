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
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('id_mahasiswa'); // NIM mahasiswa
            $table->integer('id_nilai'); // ID nilai unik
            
            // Komponen Pembimbing Lapangan (50%)
            $table->decimal('disiplin', 5, 2)->default(0); // max 15%
            $table->decimal('komunikasi', 5, 2)->default(0); // max 10%
            $table->decimal('kerja_tim', 5, 2)->default(0); // max 15%
            $table->decimal('kerja_mandiri', 5, 2)->default(0); // max 10%
            $table->decimal('penampilan', 5, 2)->default(0); // max 10%
            $table->decimal('sikap_etika_lapangan', 5, 2)->default(0); // max 20%
            $table->decimal('pengetahuan', 5, 2)->default(0); // max 20%
            
            // Komponen Dosen Pembimbing (30%)
            $table->decimal('penguasaan_teori', 5, 2)->default(0); // max 20%
            $table->decimal('kemampuan_analisis', 5, 2)->default(0); // max 25%
            $table->decimal('keaktifan_bimbingan', 5, 2)->default(0); // max 15%
            $table->decimal('kemampuan_penulisan_laporan', 5, 2)->default(0); // max 20%
            $table->decimal('sikap_etika_dospem', 5, 2)->default(0); // max 20%
            
            // Komponen Penguji (20%)
            $table->decimal('penyajian_presentasi', 5, 2)->default(0); // max 10%
            $table->decimal('pemahaman_materi', 5, 2)->default(0); // max 15%
            $table->decimal('hasil_yang_dicapai', 5, 2)->default(0); // max 40%
            $table->decimal('objektivitas_menangapi', 5, 2)->default(0); // max 20%
            $table->decimal('penulisan_laporan', 5, 2)->default(0); // max 15%
            
            // Catatan Penilaian
            $table->text('catatan_pembimbing')->nullable();
            $table->text('catatan_dospem')->nullable();
            $table->text('catatan_penguji')->nullable();
            
            // Nilai Total dan Huruf
            $table->decimal('nilai_total', 5, 2)->default(0); // Total 100%
            $table->string('nilai_huruf', 2)->nullable(); // A, B+, B, C+, C, D, E
            $table->decimal('skor', 3, 2)->nullable(); // 4.0, 3.5, 3.0, dst
            
            // ID User (pemberi nilai)
            $table->string('id_user')->nullable();
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('id_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
};
