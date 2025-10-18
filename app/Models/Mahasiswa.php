<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// TYPO DIPERBAIKI DI SINI: 'suse' menjadi 'use'
use App\Models\DataDosenPembimbing;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'no_hp',
        'prodi',
        'angkatan',
        'ipk',
        'id_pembimbing',
        'judul_pkl'
    ];
 
    public function dosen()
    {
        return $this->belongsTo(DataDosenPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}