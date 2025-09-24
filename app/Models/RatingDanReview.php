<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDanReview extends Model
{
    use HasFactory;

    protected $table = 'rating_dan_reviews';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_mahasiswa',
        'id_perusahaan',
        'rating',
        'review',
        'tanggal_review',
    ];

    // Jika Anda ingin menggunakan timestamp (created_at dan updated_at) di tabel Anda, 
    // pastikan kolom-kolom ini ada. Jika tidak, tambahkan baris berikut:
    // public $timestamps = false;
}