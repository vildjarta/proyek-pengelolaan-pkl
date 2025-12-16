<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mahasiswa', 'nim', 'ipk', 'total_sks_d', 'has_e', 'eligible'
    ];

    protected $casts = [
        'has_e' => 'boolean',
        'eligible' => 'boolean',
        'ipk' => 'decimal:2',
        'total_sks_d' => 'integer',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
