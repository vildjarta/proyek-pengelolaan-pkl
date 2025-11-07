<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id('id_pembimbing'); // BIGINT UNSIGNED primary
            $table->string('NIP')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable(); // sudah termasuk kolom no_hp
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};
