<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'kriteria';           // nama tabel di database
    protected $primaryKey = 'id_kriteria';   // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'alternatif',
        'kriteria',
        'bobot',        
    ];
}