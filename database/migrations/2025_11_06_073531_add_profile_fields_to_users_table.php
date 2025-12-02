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
            // Tambah kolom hanya kalau belum ada
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')
                      ->nullable()
                      ->after('email');
            }

            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['Laki-laki', 'Perempuan'])
                      ->nullable()
                      ->after('phone_number');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')
                      ->nullable()
                      ->default('avatars/default.png')
                      ->after('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom hanya kalau benar-benar ada,
            // supaya rollback tidak error.
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }

            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }

            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
