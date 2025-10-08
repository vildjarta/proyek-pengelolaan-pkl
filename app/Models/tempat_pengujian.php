<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tempat_pengujian extends Model
{
    protected $table = 'tempat_pengujian';
    protected $primaryKey = 'id_tempat';
    public $incrementing = true; // kalau id_tempat auto increment
    protected $keyType = 'int'; // default int, kalau string ubah ke 'string'

    protected $fillable = [
        'id_tempat',
        'tempat',
        'created_at',
        'updated_at',
    ];
        /**
     * Relasi one-to-many dengan Pengujian
     */
    public function pengujian()
    {
        return $this->hasMany(Pengujian::class, 'id_tempat', 'id_tempat');
    }
}
