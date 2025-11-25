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
            
            // 1. Tambahkan google_id jika belum ada
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('password');
            }

            // 2. Tambahkan phone_number jika belum ada
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }

            // 3. Tambahkan gender jika belum ada
            if (!Schema::hasColumn('users', 'gender')) {
                // Hati-hati: jika phone_number sudah ada, 'after' ini mungkin perlu disesuaikan
                // Tapi kita akan letakkan setelah 'phone_number' jika ada, atau setelah 'email' jika tidak.
                $afterColumn = Schema::hasColumn('users', 'phone_number') ? 'phone_number' : 'email';
                $table->string('gender')->nullable()->after($afterColumn);
            }

            // 4. Ubah kolom password menjadi nullable
            // Kita bisa menjalankan ini beberapa kali tanpa error
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * (Saya tambahkan fungsi down() untuk kelengkapan)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
            $table->string('password')->nullable(false)->change();
            
        });
    }
};