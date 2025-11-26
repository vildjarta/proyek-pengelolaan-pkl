<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian_perusahaan extends Model
{
    use HasFactory;

    protected $table = 'penilaian_perusahaan';           // nama tabel di database
    protected $primaryKey = 'id_penilaian_perusahaan';   // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_penilaian_perusahaan',
        'id_perusahaan',
        'id_kriteria',
        'nilai',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function kriteria()
    {
        return $this->belongsTo(kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
