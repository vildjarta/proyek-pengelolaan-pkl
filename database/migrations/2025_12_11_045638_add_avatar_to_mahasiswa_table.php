<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {

            if (!Schema::hasColumn('mahasiswa', 'avatar')) {
                $table->string('avatar')->nullable()->after('email')
                    ->comment('Path avatar mahasiswa pada storage/app/public/');
            }

        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswa', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
