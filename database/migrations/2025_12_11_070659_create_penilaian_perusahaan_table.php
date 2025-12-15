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
        Schema::create('penilaian_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('id_penilaian')->unique();
            $table->string('id_mahasiswa');
            
            // Komponen Penilaian Perusahaan (Total 100%)
            $table->decimal('disiplin', 5, 2)->default(0);
            $table->decimal('komunikasi', 5, 2)->default(0);
            $table->decimal('kerja_tim', 5, 2)->default(0);
            $table->decimal('kerja_mandiri', 5, 2)->default(0);
            $table->decimal('penampilan', 5, 2)->default(0);
            $table->decimal('sikap_etika', 5, 2)->default(0);
            $table->decimal('pengetahuan', 5, 2)->default(0);
            
            // Catatan dan Total
            $table->text('catatan')->nullable();
            $table->decimal('nilai_total', 5, 2)->default(0);
            $table->string('nilai_huruf', 2)->nullable();
            $table->decimal('skor', 3, 2)->default(0);
            
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_perusahaan');
    }
};
