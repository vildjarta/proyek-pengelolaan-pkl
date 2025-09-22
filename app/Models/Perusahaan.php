<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';
    public $incrementing = false; // karena NIM biasanya bukan auto-increment
    protected $keyType = 'string'; // kalau NIM berupa string, bukan integer

    protected $fillable = [
        'id_perusahaan',
        'nama',
        'alamat',
        'status',
        'bidang_usaha',
        'created_at',
        'updated_at',
    ];
}
