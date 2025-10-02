<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDanReview extends Model
{
    use HasFactory;

    protected $table = 'rating_dan_reviews';   // pastikan sesuai nama tabel di DB
    protected $primaryKey = 'id_review';       // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_mahasiswa',
        'id_perusahaan',
        'rating',
        'review',
        'tanggal_review',
    ];

    /**
     * 🔗 Relasi ke Perusahaan
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }
}
