<?php

namespace App\Http\Controllers;

use App\Models\PenilaianPerusahaan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianPerusahaanController extends Controller
{
    /**
     * Menampilkan semua data penilaian perusahaan
     */
    public function index()
    {
<<<<<<< HEAD
        // $penilaians = Penilaian_perusahaan::leftjoin('perusahaan', 'perusahaan.id_perusahaan', '=', 'penilaian_perusahaan.id_perusahaan')
        //     ->leftjoin('kriteria', 'kriteria.id_kriteria', '=', 'penilaian_perusahaan.id_kriteria')
        //     ->select('penilaian_perusahaan.*', 'perusahaan.nama as nama_perusahaan', 'kriteria.kriteria as kriteria')
        //     // ->groupby('penilaian_perusahaan.id_perusahaan')
        //     ->get();
        $penilaians = Penilaian_perusahaan::all();
        // dd($penilaians);
        // exit;
// Ganti tanda strip (-) dengan titik (.) dan tambah .index
        return view('penilaian-perusahaan.index', ['data' => $penilaians]);

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
=======
        $data = PenilaianPerusahaan::with('mahasiswa')->get();
        return view('penilaian-perusahaan.index', compact('data'));
>>>>>>> aca19585fa575c28d7adde47d144709868eccc27
    }

    /**
     * Tampilkan form untuk tambah penilaian
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('penilaian-perusahaan.create', compact('mahasiswa'));
    }

    /**
     * Simpan data penilaian baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,nim',
            'disiplin' => 'required|numeric|min:0|max:100',
            'komunikasi' => 'required|numeric|min:0|max:100',
            'kerja_tim' => 'required|numeric|min:0|max:100',
            'kerja_mandiri' => 'required|numeric|min:0|max:100',
            'penampilan' => 'required|numeric|min:0|max:100',
            'sikap_etika' => 'required|numeric|min:0|max:100',
            'pengetahuan' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung nilai total, huruf, dan skor
        $nilaiTotal = PenilaianPerusahaan::hitungTotalNilai($request->all());
        $nilaiHuruf = PenilaianPerusahaan::konversiNilaiHuruf($nilaiTotal);
        $skor = PenilaianPerusahaan::konversiSkor($nilaiTotal);

        // Buat data penilaian
        $data = $request->all();
        $data['nilai_total'] = $nilaiTotal;
        $data['nilai_huruf'] = $nilaiHuruf;
        $data['skor'] = $skor;
        $data['id_user'] = Auth::check() ? Auth::user()->id : null;

        PenilaianPerusahaan::create($data);

        return redirect()->route('penilaian-perusahaan.index')
            ->with('success', 'Penilaian berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail penilaian
     */
    public function show($id)
    {
        $penilaian = PenilaianPerusahaan::with('mahasiswa')->findOrFail($id);
        return view('penilaian-perusahaan.show', compact('penilaian'));
    }

    /**
     * Tampilkan form edit penilaian
     */
    public function edit($id)
    {
        $penilaian = PenilaianPerusahaan::findOrFail($id);
        $mahasiswa = Mahasiswa::all();

        return view('penilaian-perusahaan.edit', compact('penilaian', 'mahasiswa'));
    }

    /**
     * Update data penilaian
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,nim',
            'disiplin' => 'required|numeric|min:0|max:100',
            'komunikasi' => 'required|numeric|min:0|max:100',
            'kerja_tim' => 'required|numeric|min:0|max:100',
            'kerja_mandiri' => 'required|numeric|min:0|max:100',
            'penampilan' => 'required|numeric|min:0|max:100',
            'sikap_etika' => 'required|numeric|min:0|max:100',
            'pengetahuan' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung ulang nilai total, huruf, dan skor
        $nilaiTotal = PenilaianPerusahaan::hitungTotalNilai($request->all());
        $nilaiHuruf = PenilaianPerusahaan::konversiNilaiHuruf($nilaiTotal);
        $skor = PenilaianPerusahaan::konversiSkor($nilaiTotal);

        // Update data
        $penilaian = PenilaianPerusahaan::findOrFail($id);
        $data = $request->all();
        $data['nilai_total'] = $nilaiTotal;
        $data['nilai_huruf'] = $nilaiHuruf;
        $data['skor'] = $skor;

        $penilaian->update($data);

        return redirect()->route('penilaian-perusahaan.index')
            ->with('success', 'Penilaian berhasil diperbarui!');
    }

    /**
     * Hapus data penilaian
     */
    public function destroy($id)
    {
        $penilaian = PenilaianPerusahaan::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian-perusahaan.index')
            ->with('success', 'Penilaian berhasil dihapus!');
    }
}
