<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengujian extends Model
{
    use HasFactory;

    protected $table = 'Pengujian';
    protected $primaryKey = 'id_tempat';
    public $incrementing = true; // kalau id_tempat auto increment
    protected $keyType = 'int'; // default int, kalau string ubah ke 'string'

    protected $fillable = [
        'id_pengujian',
        'id_dosen',
        'id_penilaianpenguji',
        'id_tempat',
        'jam',
        'tanggal',
        'created_at',
        'updated_at',
    ];
}
