<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal_bimbingan extends Model
{
    protected $fillable = [
        'mahasiswa',
        'dosen',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'topik',
        'catatan',
        'status',
    ];

    /**
     * Get the Mahasiswa that owns the JadwalBimbingan.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Get the Dosen that owns the JadwalBimbingan.
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}