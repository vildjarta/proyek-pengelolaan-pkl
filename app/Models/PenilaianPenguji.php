<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianPenguji extends Model
{
    use HasFactory;

    //Tambahkan ini supaya pakai tabel 'penguji'
    protected $table = 'penilaian_penguji';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_penguji',
        'nama_mahasiswa',
        'presentasi',
        'materi',
        'hasil',
        'objektif',
        'laporan',
        'total_nilai',
        'nilai_akhir',
        'tanggal_ujian',
    ];
    public function dosen()
    {
        return $this->belongsTo(dosen_penguji::class, 'id_penguji', 'id_penguji');
    }
}