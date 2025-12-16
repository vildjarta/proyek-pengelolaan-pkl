<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bobot;
use App\Models\Perusahaan;

class SAWController extends Controller
{
    // Menampilkan halaman SAW dengan perhitungan
    public function index()
    {
        $perusahaan = Perusahaan::all();
        $bobot = Bobot::first(); // Ambil bobot dari database

        // Default bobot jika tidak ada di database
        $bobotKriteria = [
            'jumlah_mahasiswa' => $bobot ? $bobot->jumlah_mahasiswa : 0.0554,
            'fasilitas' => $bobot ? $bobot->fasilitas : 0.1282,
            'hari_operasi' => $bobot ? $bobot->hari_operasi : 0.6241,
            'level_legalitas' => $bobot ? $bobot->level_legalitas : 0.1923,
        ];

        // Hitung nilai maksimum untuk setiap kriteria (untuk normalisasi)
        $maxJumlahMahasiswa = $perusahaan->max('jumlah_mahasiswa') ?: 1;
        $maxFasilitas = $perusahaan->max('fasilitas') ?: 1;
        $maxHariOperasi = $perusahaan->max('hari_operasi') ?: 1;
        $maxLevelLegalitas = $perusahaan->max('level_legalitas') ?: 1;

        // Hitung normalisasi untuk setiap perusahaan (r = X / Xmax)
        $normalisasi = [];
        foreach ($perusahaan as $p) {
            $normalisasi[$p->id_perusahaan] = [
                'nama' => $p->nama,
                'jumlah_mahasiswa' => $maxJumlahMahasiswa > 0 ? round($p->jumlah_mahasiswa / $maxJumlahMahasiswa, 4) : 0,
                'fasilitas' => $maxFasilitas > 0 ? round($p->fasilitas / $maxFasilitas, 4) : 0,
                'hari_operasi' => $maxHariOperasi > 0 ? round($p->hari_operasi / $maxHariOperasi, 4) : 0,
                'level_legalitas' => $maxLevelLegalitas > 0 ? round($p->level_legalitas / $maxLevelLegalitas, 4) : 0,
            ];
        }

        // Hitung nilai SAW (bobot * normalisasi) untuk setiap perusahaan
        $hasilSAW = [];
        foreach ($normalisasi as $id => $n) {
            $nilai = ($n['jumlah_mahasiswa'] * $bobotKriteria['jumlah_mahasiswa']) +
                     ($n['fasilitas'] * $bobotKriteria['fasilitas']) +
                     ($n['hari_operasi'] * $bobotKriteria['hari_operasi']) +
                     ($n['level_legalitas'] * $bobotKriteria['level_legalitas']);
            
            $hasilSAW[$id] = [
                'nama' => $n['nama'],
                'nilai' => round($nilai, 4),
            ];
        }

        // Urutkan berdasarkan nilai tertinggi untuk ranking
        $ranking = collect($hasilSAW)->sortByDesc('nilai')->values()->all();

        // Tambahkan ranking ke setiap item
        foreach ($ranking as $index => &$item) {
            $item['ranking'] = $index + 1;
        }

        return view('saw.saw', compact(
            'perusahaan', 
            'bobotKriteria', 
            'normalisasi', 
            'hasilSAW', 
            'ranking',
            'maxJumlahMahasiswa',
            'maxFasilitas',
            'maxHariOperasi',
            'maxLevelLegalitas'
        ));
    }
}