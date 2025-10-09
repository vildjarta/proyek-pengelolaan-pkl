<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengujian extends Model
{
    use HasFactory;

    protected $table = 'pengujian';
    protected $primaryKey = 'id_pengujian';
    public $incrementing = true; // kalau id_tempat auto increment
    protected $keyType = 'int'; // default int, kalau string ubah ke 'string'
    public $timestamps = true; // pastikan timestamps aktif

    protected $fillable = [
        'id_penguji',
        'id_tempat',
        'jam',
        'tanggal',
        'created_at',
        'updated_at',
    ];
    public function dosen()
    {
        return $this->belongsTo(dosen_penguji::class, 'id_penguji', 'id_penguji');
    }


    public function tempat()
    {
        return $this->belongsTo(tempat_pengujian::class, 'id_tempat', 'id_tempat');
    }
}
