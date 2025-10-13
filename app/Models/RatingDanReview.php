<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDanReview extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'rating_dan_reviews'; // Pastikan sesuai dengan nama tabel di database

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_review';

    /**
     * Auto increment aktif.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /** 
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'id_mahasiswa',
        'id_perusahaan',
        'rating',
        'review',
        'tanggal_review',
    ];

    /**
     * 🔗 Relasi ke tabel perusahaan
     * Satu review dimiliki oleh satu perusahaan.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    /**
     * 🔗 Relasi ke tabel mahasiswa
     * Satu review dibuat oleh satu mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
