<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nama',
        'nim', 
        'prodi',
        'email',
        'no_hp',
        'dospem_id'
    ];

    public function dospem()
    {
        return $this->belongsTo(Dosen::class, 'dospem_id');
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianDospem::class, 'mahasiswa_id');
    }
}