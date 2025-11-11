<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'id_nilai',
        // Pembimbing Lapangan
        'disiplin',
        'komunikasi',
        'kerja_tim',
        'kerja_mandiri',
        'penampilan',
        'sikap_etika_lapangan',
        'pengetahuan',
        // Dosen Pembimbing
        'penguasaan_teori',
        'kemampuan_analisis',
        'keaktifan_bimbingan',
        'kemampuan_penulisan_laporan',
        'sikap_etika_dospem',
        // Penguji
        'penyajian_presentasi',
        'pemahaman_materi',
        'hasil_yang_dicapai',
        'objektivitas_menangapi',
        'penulisan_laporan',
        // Catatan
        'catatan_pembimbing',
        'catatan_dospem',
        'catatan_penguji',
        // Total
        'nilai_total',
        'nilai_huruf',
        'skor',
        'id_user',
    ];

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'nim');
    }

    /**
     * Hitung total nilai pembimbing lapangan (50%)
     */
    public function getNilaiPembimbingAttribute()
    {
        return $this->disiplin + $this->komunikasi + $this->kerja_tim + 
               $this->kerja_mandiri + $this->penampilan + 
               $this->sikap_etika_lapangan + $this->pengetahuan;
    }

    /**
     * Hitung total nilai dosen pembimbing (30%)
     */
    public function getNilaiDospemAttribute()
    {
        return $this->penguasaan_teori + $this->kemampuan_analisis + 
               $this->keaktifan_bimbingan + $this->kemampuan_penulisan_laporan + 
               $this->sikap_etika_dospem;
    }

    /**
     * Hitung total nilai penguji (20%)
     */
    public function getNilaiPengujiAttribute()
    {
        return $this->penyajian_presentasi + $this->pemahaman_materi + 
               $this->hasil_yang_dicapai + $this->objektivitas_menangapi + 
               $this->penulisan_laporan;
    }

    /**
     * Konversi nilai angka ke huruf
     */
    public static function konversiNilaiHuruf($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'B+';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 70) return 'C+';
        if ($nilai >= 65) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }

    /**
     * Konversi nilai angka ke skor
     */
    public static function konversiSkor($nilai)
    {
        if ($nilai >= 85) return 4.0;
        if ($nilai >= 80) return 3.5;
        if ($nilai >= 75) return 3.0;
        if ($nilai >= 70) return 2.5;
        if ($nilai >= 65) return 2.0;
        if ($nilai >= 60) return 1.0;
        return 0.0;
    }
}
