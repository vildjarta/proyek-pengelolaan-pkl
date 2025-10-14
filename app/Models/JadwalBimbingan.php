<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalBimbingan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_bimbingans';

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

    /**
     * Relasi ke model Mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Relasi ke model DataDosenPembimbing.
     */
    public function dosen()
    {
        // Menggunakan nama Model yang benar
        return $this->belongsTo(DataDosenPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}

