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
        'id_perusahaan',
        'alternatif',
        'kriteria',
        'bobot',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }
}
