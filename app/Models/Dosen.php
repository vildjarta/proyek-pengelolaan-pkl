<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'dosen';

    protected $fillable = [
        'nama',
        'nidn',
        'prodi',
        'email', 
        'no_hp',
        'jabatan'
    ];

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(Mahasiswa::class, 'dospem_id');
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianDospem::class, 'dospem_id');
    }
}