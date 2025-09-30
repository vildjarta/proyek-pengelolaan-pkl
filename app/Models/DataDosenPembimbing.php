<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_pembimbing';

    protected $fillable = ['NIP', 'nama', 'email', 'nama_mahasiswa', 'id_user'];

}
