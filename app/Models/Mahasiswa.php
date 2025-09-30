<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';             // nama tabel
    protected $primaryKey = 'id_mahasiswa';     // primary key custom

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'no_hp',
        'prodi',
        'angkatan',
        'ipk',
        'id_pembimbing'
    ];

    // relasi ke user (sementara dimatikan karena belum dipakai)
    // public function user()
    // {
    //     return $this->belongsTo(\App\Models\User::class, 'id_user');
    // }

    // relasi ke dosen pembimbing (belum ada modelnya, jadi sementara dimatikan)
    // public function pembimbing()
    // {
    //     return $this->belongsTo(\App\Models\DosenPembimbing::class, 'id_pembimbing');
    // }
}
