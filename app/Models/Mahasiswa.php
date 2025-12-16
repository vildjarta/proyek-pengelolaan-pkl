<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    public $timestamps = true;

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'no_hp',
        'prodi',
        'angkatan',
        'ipk',
        'perusahaan',
        'id_pembimbing',
        'judul_pkl',
        'user_id',
        'avatar' // ← WAJIB ADA
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dosen()
    {
        // Asumsi: tabel mahasiswa punya kolom 'dosen_id'
        // Sesuaikan 'dosen_id' dengan nama foreign key di database kamu
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function dosen_penguji()
    {
        return $this->hasOne(dosen_penguji::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function transcript()
    {
        return $this->hasOne(Transcript::class, 'nim', 'nim');
    }

    /**
     * ACCESSOR: URL avatar final
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return asset('storage/avatars/default.png');
    }
}
