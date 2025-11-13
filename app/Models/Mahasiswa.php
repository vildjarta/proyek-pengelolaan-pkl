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

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'no_hp',
        'prodi',
        'angkatan',
        'ipk',
        'perusahaan',    
        'id_pembimbing',
        'judul_pkl',
    ];

    /**
     * 🔗 Relasi ke tabel dosen_pembimbing
     * Setiap mahasiswa memiliki satu dosen pembimbing.
     */
    public function dosen()
    {
        return $this->belongsTo(DataDosenPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}
