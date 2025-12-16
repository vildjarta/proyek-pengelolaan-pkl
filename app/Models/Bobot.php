<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory;

    protected $table = 'bobot';
    protected $primaryKey = 'id_bobot';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'jumlah_mahasiswa',
        'fasilitas',
        'hari_operasi',
        'level_legalitas',
    ];
}