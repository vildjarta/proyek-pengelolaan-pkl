<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianPenguji extends Model
{
    protected $table = 'penilaian_penguji';

    protected $fillable = [
        'nip',
        'nama_dosen',
        'nama_mahasiswa',
        'judul',
        'sikap',
        'penguasaan',
        'nilai',
        'tanggal_ujian',
        'jenis_ujian',
        'komentar'
    ];
}
