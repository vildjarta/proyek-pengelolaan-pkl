<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalBimbingan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_bimbingan';

    protected $fillable = [
        'nama_mahasiswa',
        'dosen_pembimbing',
        'tanggal',
        'waktu',
        'topik',
    ];
}
