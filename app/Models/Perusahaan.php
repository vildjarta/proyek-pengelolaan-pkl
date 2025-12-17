<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';           // nama tabel di database
    protected $primaryKey = 'id_perusahaan';   // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'alamat',
        'bidang_usaha',
        'status',
        'fasilitas',
        'level_legalitas',
        'jumlah_mahasiswa',
        'hari_operasi',
        'created_at',
        'updated_at',
    ];

    /**
     * Optional relation to user account representing this company
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * 🔗 Relasi ke RatingDanReview
     */
    public function reviews()
    {
        return $this->hasMany(RatingDanReview::class, 'id_perusahaan', 'id_perusahaan');
    }

    /** 
     * 🔗 relasi ke penilaian perusahaan
     */
    public function perusahaan()
    {
        return $this->hasOne(Penilaianperusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }
}
