<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // atau sesuaikan kalau tabel kamu 'tbl_mahasiswa'
    protected $primaryKey = 'id_mahasiswa'; // ← ini wajib disesuaikan
    public $incrementing = true; // karena id_mahasiswa biasanya AUTO_INCREMENT
    protected $keyType = 'int';

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

    public function pembimbing()
    {
        return $this->belongsTo(DataDosenPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}
