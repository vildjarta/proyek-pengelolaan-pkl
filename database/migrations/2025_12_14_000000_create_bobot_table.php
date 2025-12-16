<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bobot', function (Blueprint $table) {
            $table->id('id_bobot');
            $table->decimal('jumlah_mahasiswa', 10, 6)->default(0);
            $table->decimal('fasilitas', 10, 6)->default(0);
            $table->decimal('hari_operasi', 10, 6)->default(0);
            $table->decimal('level_legalitas', 10, 6)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot');
    }
};
