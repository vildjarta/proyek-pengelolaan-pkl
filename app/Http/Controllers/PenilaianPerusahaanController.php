<?php

namespace App\Http\Controllers;

use App\Models\Penilaian_perusahaan;
use App\Models\Perusahaan;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class PenilaianPerusahaanController extends Controller
{
    /**
     * Menampilkan semua data penilaian perusahaan
     */
    public function index()
    {
        // $penilaians = Penilaian_perusahaan::leftjoin('perusahaan', 'perusahaan.id_perusahaan', '=', 'penilaian_perusahaan.id_perusahaan')
        //     ->leftjoin('kriteria', 'kriteria.id_kriteria', '=', 'penilaian_perusahaan.id_kriteria')
        //     ->select('penilaian_perusahaan.*', 'perusahaan.nama as nama_perusahaan', 'kriteria.kriteria as kriteria')
        //     // ->groupby('penilaian_perusahaan.id_perusahaan')
        //     ->get();
        $penilaians = Perusahaan::get();
        // dd($penilaians);
        // exit;
        return view('penilaian_perusahaan.index', compact('penilaians'));

        // 🔹 Kelompokkan nilai berdasarkan perusahaan & kriteria (disesuaikan dengan hasil left join)
        $nilaiGrouped = [];
        $perusahaanNama = [];
        $kriteriaInfo = [];

        foreach ($penilaians as $p) {
            $nilaiGrouped[$p->id_perusahaan][$p->id_kriteria] = $p->nilai;
            $perusahaanNama[$p->id_perusahaan] = $p->nama_perusahaan;
            // Pastikan bobot dan jenis diambil dari join, cek null (left join bisa null)
            $kriteriaInfo[$p->id_kriteria] = [
                'bobot' => $p->bobot ?? 0,
                'jenis' => $p->jenis ?? 'benefit'
            ];
        }

        // 🔹 Normalisasi
        $normalisasi = [];
        foreach ($kriteriaInfo as $idKriteria => $info) {
            // Dapatkan semua nilai pada kriteria ini (perlu filter agar hanya nilai yg ada)
            $values = [];
            foreach ($nilaiGrouped as $idPerusahaan => $nilaiPerusahaan) {
                if (isset($nilaiPerusahaan[$idKriteria])) {
                    $values[] = $nilaiPerusahaan[$idKriteria];
                }
            }

            // Lewati normalisasi jika tidak ada data
            if (empty($values) || (max($values) == 0 && $info['jenis'] == 'benefit')) {
                continue;
            }

            foreach ($nilaiGrouped as $idPerusahaan => $nilaiPerusahaan) {
                if (!isset($nilaiPerusahaan[$idKriteria])) {
                    // Data kosong krn hasil left join, set auto 0 normalisasi
                    $normalisasi[$idPerusahaan][$idKriteria] = 0;
                } else {
                    if ($info['jenis'] == 'benefit') {
                        $normalisasi[$idPerusahaan][$idKriteria] =
                            (max($values) != 0) ? $nilaiPerusahaan[$idKriteria] / max($values) : 0;
                    } else {
                        $normalisasi[$idPerusahaan][$idKriteria] =
                            ($nilaiPerusahaan[$idKriteria] != 0) ? min($values) / $nilaiPerusahaan[$idKriteria] : 0;
                    }
                }
            }
        }

        // 🔹 Hitung skor SAW
        $hasilSaw = [];
        foreach ($normalisasi as $idPerusahaan => $nilaiNorm) {
            $skor = 0;
            foreach ($kriteriaInfo as $idKriteria => $info) {
                $nilaiN = $nilaiNorm[$idKriteria] ?? 0; // Jika tidak ada nilai, anggap 0
                $skor += $nilaiN * ($info['bobot'] ?? 0);
            }
            $hasilSaw[] = [
                'id_perusahaan' => $idPerusahaan,
                'nama_perusahaan' => $perusahaanNama[$idPerusahaan] ?? '-',
                'skor' => round($skor, 4)
            ];
        }

        // 🔹 Urut dari skor tertinggi
        usort($hasilSaw, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return view('penilaian_perusahaan.index', [
            'penilaians' => $penilaians,
            'hasilSaw' => $hasilSaw
        ]);
    }

    /**
     * Tampilkan form untuk tambah penilaian
     */
    public function create()
    {
        $perusahaans = Perusahaan::all();
        $kriterias = Kriteria::all();
        return view('penilaian_perusahaan.create', compact('perusahaans', 'kriterias'));
    }

    /**
     * Simpan data penilaian baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'id_kriteria' => 'required|exists:kriteria,id_kriteria',
            'nilai' => 'required|string|min:0|max:100'
        ]);

        Penilaian_perusahaan::create($request->all());

        return redirect()->route('penilaian_perusahaan.index')
            ->with('success', 'Penilaian berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit penilaian
     */
    public function edit($id)
    {
        $penilaian = Penilaian_perusahaan::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $kriterias = Kriteria::all();

        return view('penilaian_perusahaan.edit', compact('penilaian', 'perusahaans', 'kriterias'));
    }

    /**
     * Update data penilaian
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'id_kriteria' => 'required|exists:kriteria,id_kriteria',
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        $penilaian = Penilaian_perusahaan::findOrFail($id);
        $penilaian->update($request->all());

        return redirect()->route('penilaian_perusahaan.index')
            ->with('success', 'Penilaian berhasil diperbarui!');
    }

    /**
     * Hapus data penilaian
     */
    public function destroy($id)
    {
        $penilaian = Penilaian_perusahaan::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian_perusahaan.index')
            ->with('success', 'Penilaian berhasil dihapus!');
    }
}
