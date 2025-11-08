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
            $table->unsignedBigInteger('id_dosen')->nullable(); // optional FK to dosen.id_dosen
            $table->string('NIP')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            // add foreign key (nullable, set null on delete)
            $table->foreign('id_dosen')
                  ->references('id_dosen')->on('dosen')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // drop FK safely without relying on Doctrine
        if (Schema::hasTable('dosen_pembimbing')) {
            try {
                Schema::table('dosen_pembimbing', function (Blueprint $table) {
                    // attempt to drop the foreign key; if it doesn't exist, ignore error
                    try {
                        $table->dropForeign(['id_dosen']);
                    } catch (\Throwable $e) {
                        // ignore: foreign key might not exist (older DB state)
                    }
                });
            } catch (\Throwable $e) {
                // ignore any issues during drop; continue to drop table below
            }
        }

        Schema::dropIfExists('dosen_pembimbing');
    }
};
