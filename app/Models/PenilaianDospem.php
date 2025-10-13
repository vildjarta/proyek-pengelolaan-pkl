<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDospem extends Model
{
    use HasFactory;

    protected $table = 'penilaian_dospem';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mahasiswa_id',
        'nama_mahasiswa',
        'judul',
        'penguasaan_teori', // Diperbarui
        'analisis_pemecahan_masalah', // Diperbarui
        'keaktifan_bimbingan', // Diperbarui
        'penulisan_laporan', // Diperbarui
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

    // Accessor untuk nilai total internal (sesuai bobot, maks 100)
    public function getNilaiDospemInternalAttribute()
    {
        $total = ($this->penguasaan_teori * 0.20) + // Bobot 20%
                 ($this->analisis_pemecahan_masalah * 0.25) + // Bobot 25%
                 ($this->keaktifan_bimbingan * 0.15) + // Bobot 15%
                 ($this->penulisan_laporan * 0.20) + // Bobot 20%
                 ($this->sikap * 0.20); // Bobot 20%
        return round($total, 2);
    }

    // Accessor untuk nilai akhir (30% dari total internal)
    public function getNilaiAkhirAttribute()
    {
        return round($this->nilai_dospem_internal * 0.30, 2);
    }

    // Accessor untuk grade (berdasarkan nilai total internal)
    public function getGradeAttribute()
    {
        $total = $this->nilai_dospem_internal;

        if ($total >= 80) return 'A';
        if ($total >= 75) return 'B+';
        if ($total >= 70) return 'B';
        if ($total >= 65) return 'C+';
        if ($total >= 60) return 'C';
        if ($total >= 55) return 'D+';
        if ($total >= 50) return 'D';
        return 'E';
    }
}