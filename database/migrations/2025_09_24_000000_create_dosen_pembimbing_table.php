<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id('id_pembimbing'); // primary key

            $table->unsignedBigInteger('id_dosen')->nullable(); // FK to dosen.id_dosen
            $table->unsignedBigInteger('id_user')->nullable();  // <-- hanya satu kali

            $table->string('NIP')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('id_dosen')
                ->references('id_dosen')->on('dosen')
                ->onDelete('set null');

            // foreign key to users table for id_user
            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // drop FK safely
        if (Schema::hasTable('dosen_pembimbing')) {
            try {
                Schema::table('dosen_pembimbing', function (Blueprint $table) {
                    try {
                        $table->dropForeign(['id_dosen']);
                        try {
                            $table->dropForeign(['id_user']);
                        } catch (\Throwable $e) {
                            // ignore if foreign not exists
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                });
            } catch (\Throwable $e) {
                // ignore
            }
        }

        Schema::dropIfExists('dosen_pembimbing');
    }
};
