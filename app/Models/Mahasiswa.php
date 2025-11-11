<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'mahasiswa';

    // Primary key
    protected $primaryKey = 'id_mahasiswa';

    // Aktifkan created_at & updated_at
    public $timestamps = true;

    // Kolom yang bisa diisi
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'no_hp',
        'prodi',
        'angkatan',
        'ipk',
        'id_pembimbing',
        'judul_pkl',
    ];


    public function dosen_penguji()
    {
        return $this->hasOne(dosen_penguji::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * 🔗 Relasi ke tabel dosen_pembimbing (setiap mahasiswa punya satu dosen)
     */
    public function dosen()
    {
        return $this->belongsTo(DataDosenPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}
