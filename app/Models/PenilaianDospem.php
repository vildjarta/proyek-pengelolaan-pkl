<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDospem extends Model
{
    use HasFactory;

    protected $table = 'penilaian_dospem';

    protected $fillable = [
        'id_mahasiswa',
        'nama_mahasiswa',
        'judul',
        'penguasaan_teori',
        'analisis_pemecahan_masalah',
        'keaktifan_bimbingan',
        'penulisan_laporan',
        'sikap',
        'catatan',
        'id_pembimbing',
    ];

    /**
     * Relasi ke model Mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    // --- ACCESSORS UNTUK PERHITUNGAN NILAI OTOMATIS ---

    public function getNilaiDospemInternalAttribute()
    {
        $total = ($this->penguasaan_teori * 0.20) +
                 ($this->analisis_pemecahan_masalah * 0.25) +
                 ($this->keaktifan_bimbingan * 0.15) +
                 ($this->penulisan_laporan * 0.20) +
                 ($this->sikap * 0.20);
        return round($total, 2);
    }

    public function getNilaiAkhirAttribute()
    {
        return round($this->getNilaiDospemInternalAttribute() * 0.30, 2);
    }

    public function getGradeAttribute()
    {
        $nilai = $this->getNilaiDospemInternalAttribute();
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }
}

