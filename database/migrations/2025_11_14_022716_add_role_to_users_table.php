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
        Schema::table('users', function (Blueprint $table) {
            // "SESUAIKAN DISINI"
            // Tambahkan kolom 'role' setelah kolom 'email'
            // Kita beri nilai default 'mahasiswa' untuk semua user
            // yang sudah ada, atau user baru yang tidak mendaftar via Google.
            $table->string('role')->default('mahasiswa')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini adalah kebalikan dari 'up', 
            // jika Anda perlu membatalkan migration (rollback)
            $table->dropColumn('role');
        });
    }
};