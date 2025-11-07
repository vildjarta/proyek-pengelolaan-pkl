<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_pembimbing';
    public $timestamps = true;

    protected $fillable = [
        'NIP',
        'nama',
        'email',
        'no_hp',
        'id_user'
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_pembimbing', 'id_pembimbing');
    }
}
