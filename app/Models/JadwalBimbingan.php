<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalBimbingan extends Model
{
    use HasFactory;

    // 1. Sesuaikan nama tabel agar cocok dengan migrasi
    protected $table = 'jadwal_bimbingans';

    // 2. Sesuaikan fillable dengan kolom baru di migrasi
    protected $fillable = [
        'id_mahasiswa',
        'id_pembimbing',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'topik',
        'catatan',
        'status',
    ];


    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}