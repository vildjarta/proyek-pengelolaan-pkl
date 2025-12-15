<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('id_perusahaan');
            $table->string('email')->nullable()->unique()->after('nama');

            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('perusahaan')) {
            Schema::table('perusahaan', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
                $table->dropColumn('email');
            });
        }
    }
};
