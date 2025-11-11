<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_pembimbing';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_dosen',
        'NIP',
        'nama',
        'email',
        'no_hp',
        'id_user',
    ];

    // relasi ke mahasiswa (asumsikan Mahasiswa model punya id_pembimbing column)
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_pembimbing', 'id_pembimbing');
    }

    // optional relation ke Dosen master
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    // alias id to keep views using ->id
    public function getIdAttribute()
    {
        return $this->attributes[$this->getKeyName()] ?? $this->getKey();
    }
}
