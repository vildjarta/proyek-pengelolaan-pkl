<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('mahasiswa', function (Blueprint $table) {
    $table->id('id_mahasiswa');
    $table->string('nim')->unique();
    $table->string('nama');
    $table->string('email')->unique();
    $table->string('no_hp')->nullable();
    $table->string('prodi');
    $table->string('angkatan');
    $table->decimal('ipk', 3, 2)->nullable();
    $table->unsignedBigInteger('id_pembimbing')->nullable();
    $table->timestamps();
});


}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
