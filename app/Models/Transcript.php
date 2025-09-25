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
}
