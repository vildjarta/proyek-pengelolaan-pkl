<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDanReview extends Model
{
    use HasFactory;

    protected $table = 'rating_dan_reviews';     // nama tabel sesuai migration
    protected $primaryKey = 'id_review';         // kunci utama
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_mahasiswa',
        'id_perusahaan',
        'rating',
        'review',
        'tanggal_review',
    ];
}
