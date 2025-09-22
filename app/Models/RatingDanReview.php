<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDanReview extends Model
{
    use HasFactory;

    protected $table = 'rating_dan_review';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_mahasiswa',
        'id_perusahaan', // Tambahkan baris ini
        'rating',
        'review',
        'tanggal_review',
    ];
}