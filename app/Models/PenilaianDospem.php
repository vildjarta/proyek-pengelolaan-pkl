<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDospem extends Model
{
    use HasFactory;

    protected $table = 'penilaian_dospem';

    protected $fillable = [
        'mahasiswa_id',
        'nama_mahasiswa',
        'judul',
        'presentasi',
        'laporan',
        'penguasaan',
        'sikap',
        'catatan',
        'dospem_id',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function dospem()
    {
        return $this->belongsTo(Dosen::class, 'dospem_id');
    }

    // Accessor untuk nilai total (optional)
    public function getTotalAttribute()
    {
        return (
            $this->presentasi + 
            $this->laporan + 
            $this->penguasaan + 
            $this->sikap
        ) / 4;
    }

    // Accessor untuk grade (optional)
    public function getGradeAttribute()
    {
        $total = $this->total;
        
        if ($total >= 85) return 'A';
        if ($total >= 75) return 'B';
        if ($total >= 65) return 'C';
        if ($total >= 55) return 'D';
        return 'E';
    }
}