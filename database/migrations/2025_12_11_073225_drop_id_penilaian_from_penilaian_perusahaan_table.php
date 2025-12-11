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
            $table->dropColumn('id_penilaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_perusahaan', function (Blueprint $table) {
            $table->string('id_penilaian')->unique()->after('id');
        });
    }
};
