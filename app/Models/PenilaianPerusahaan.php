<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'penilaian_perusahaan';

    // Bobot masing-masing komponen (dalam persen)
    const BOBOT_DISIPLIN = 15;
    const BOBOT_KOMUNIKASI = 10;
    const BOBOT_KERJA_TIM = 15;
    const BOBOT_KERJA_MANDIRI = 10;
    const BOBOT_PENAMPILAN = 10;
    const BOBOT_SIKAP_ETIKA = 20;
    const BOBOT_PENGETAHUAN = 20;

    protected $fillable = [
        'id_mahasiswa',
        'disiplin',
        'komunikasi',
        'kerja_tim',
        'kerja_mandiri',
        'penampilan',
        'sikap_etika',
        'pengetahuan',
        'catatan',
        'nilai_total',
        'nilai_huruf',
        'skor',
        'id_user',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'nim');
    }

    // Hitung nilai dengan bobot
    public static function hitungNilaiDenganBobot($nilaiMentah, $bobot)
    {
        return ($nilaiMentah / 100) * $bobot;
    }

    // Hitung total nilai dari semua komponen
    public static function hitungTotalNilai($data)
    {
        $total = 0;
        $total += self::hitungNilaiDenganBobot($data['disiplin'] ?? 0, self::BOBOT_DISIPLIN);
        $total += self::hitungNilaiDenganBobot($data['komunikasi'] ?? 0, self::BOBOT_KOMUNIKASI);
        $total += self::hitungNilaiDenganBobot($data['kerja_tim'] ?? 0, self::BOBOT_KERJA_TIM);
        $total += self::hitungNilaiDenganBobot($data['kerja_mandiri'] ?? 0, self::BOBOT_KERJA_MANDIRI);
        $total += self::hitungNilaiDenganBobot($data['penampilan'] ?? 0, self::BOBOT_PENAMPILAN);
        $total += self::hitungNilaiDenganBobot($data['sikap_etika'] ?? 0, self::BOBOT_SIKAP_ETIKA);
        $total += self::hitungNilaiDenganBobot($data['pengetahuan'] ?? 0, self::BOBOT_PENGETAHUAN);
        
        return $total;
    }

    // Accessor untuk nilai terbobot tiap komponen
    public function getNilaiDisiplinTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->disiplin, self::BOBOT_DISIPLIN);
    }

    public function getNilaiKomunikasiTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->komunikasi, self::BOBOT_KOMUNIKASI);
    }

    public function getNilaiKerjaTimTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->kerja_tim, self::BOBOT_KERJA_TIM);
    }

    public function getNilaiKerjaMandiriTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->kerja_mandiri, self::BOBOT_KERJA_MANDIRI);
    }

    public function getNilaiPenampilanTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->penampilan, self::BOBOT_PENAMPILAN);
    }

    public function getNilaiSikapEtikaTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->sikap_etika, self::BOBOT_SIKAP_ETIKA);
    }

    public function getNilaiPengetahuanTerbobotAttribute()
    {
        return self::hitungNilaiDenganBobot($this->pengetahuan, self::BOBOT_PENGETAHUAN);
    }

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
