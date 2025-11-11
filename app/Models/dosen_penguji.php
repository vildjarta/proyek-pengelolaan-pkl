<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dosen_penguji extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'dosen_penguji';

    // Primary key
    protected $primaryKey = 'id_penguji';

    // Jika primary key auto increment dan bertipe integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'id_mahasiswa',
        'id_dosen',
        'created_at',
        'updated_at',
    ];
    // One to one: satu dosen_penguji punya satu nilai_pengujian
    public function penilaian_penguji()
    {
        return $this->hasMany(PenilaianPenguji::class, 'id_penguji', 'id_penguji');
    }

    // Di model dosen_penguji, ganti nama method:
    public function Mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    // Di model dosen_penguji, ganti nama method:
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }
}
