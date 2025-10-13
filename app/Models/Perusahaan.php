<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';           // nama tabel di database
    protected $primaryKey = 'id_perusahaan';   // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'alamat',
        'bidang_usaha',
        'status',
        'fasilitas',
        'level_legalitas',
        'jumlah_mahasiswa',
        'jam_operasi',
        'lat',
        'lng',
    ];

    /**
     * 🔗 Relasi ke RatingDanReview
     */
    public function reviews()
    {
        return $this->hasMany(RatingDanReview::class, 'id_perusahaan', 'id_perusahaan');
    }
}
