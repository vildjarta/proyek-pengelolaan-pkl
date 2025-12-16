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

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_pembimbing', 'id_pembimbing');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    /**
     * Optional relation to the user account for this pembimbing (foreign key `id_user` -> `users.id`).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function getIdAttribute()
    {
        return $this->attributes[$this->getKeyName()] ?? $this->getKey();
    }
}
