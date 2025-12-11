<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';           // nama tabel di database
    protected $primaryKey = 'id_kriteria';   // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kriteria',
        'bobot',
    ];

    /** 
     * 🔗 relasi ke penilaian perusahaan
     */
    public function kriteria()
    {
        return $this->hasOne(Penilaian_perusahaan::class, 'id_kriteria', 'id_kriteria');
    }
}
