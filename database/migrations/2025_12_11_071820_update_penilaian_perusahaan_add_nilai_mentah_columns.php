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
        Schema::table('penilaian_perusahaan', function (Blueprint $table) {
            // Ubah kolom untuk menyimpan nilai 0-100
            $table->decimal('disiplin', 5, 2)->default(0)->change();
            $table->decimal('komunikasi', 5, 2)->default(0)->change();
            $table->decimal('kerja_tim', 5, 2)->default(0)->change();
            $table->decimal('kerja_mandiri', 5, 2)->default(0)->change();
            $table->decimal('penampilan', 5, 2)->default(0)->change();
            $table->decimal('sikap_etika', 5, 2)->default(0)->change();
            $table->decimal('pengetahuan', 5, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_perusahaan', function (Blueprint $table) {
            $table->decimal('disiplin', 5, 2)->default(0)->change();
            $table->decimal('komunikasi', 5, 2)->default(0)->change();
            $table->decimal('kerja_tim', 5, 2)->default(0)->change();
            $table->decimal('kerja_mandiri', 5, 2)->default(0)->change();
            $table->decimal('penampilan', 5, 2)->default(0)->change();
            $table->decimal('sikap_etika', 5, 2)->default(0)->change();
            $table->decimal('pengetahuan', 5, 2)->default(0)->change();
        });
    }
};
