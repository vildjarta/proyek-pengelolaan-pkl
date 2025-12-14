<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;
use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Penilaian_perusahaan;

class PenilaianPerusahaanController extends Controller
{
    private function useNewModel(): bool
    {
        return class_exists('\\App\\Models\\PenilaianPerusahaan');
    }

    public function index()
    {
        if ($this->useNewModel()) {
            $data = \App\Models\PenilaianPerusahaan::with('mahasiswa')->latest()->get();
            return view('penilaian-perusahaan.index', compact('data'));
        }

        $penilaians = Penilaian_perusahaan::leftjoin('perusahaan', 'perusahaan.id_perusahaan', '=', 'penilaian_perusahaan.id_perusahaan')
            ->leftjoin('kriteria', 'kriteria.id_kriteria', '=', 'penilaian_perusahaan.id_kriteria')
            ->select('penilaian_perusahaan.*', 'perusahaan.nama as nama_perusahaan', 'kriteria.kriteria as kriteria', 'kriteria.bobot', 'kriteria.jenis')
            ->get();

        $nilaiGrouped = [];
        $perusahaanNama = [];
        $kriteriaInfo = [];

        foreach ($penilaians as $p) {
            $nilaiGrouped[$p->id_perusahaan][$p->id_kriteria] = $p->nilai;
            $perusahaanNama[$p->id_perusahaan] = $p->nama_perusahaan;
            $kriteriaInfo[$p->id_kriteria] = [
                'bobot' => $p->bobot ?? 0,
                'jenis' => $p->jenis ?? 'benefit'
            ];
        }

        $normalisasi = [];
        foreach ($kriteriaInfo as $idKriteria => $info) {
            $values = [];
            foreach ($nilaiGrouped as $idPerusahaan => $nilaiPerusahaan) {
                if (isset($nilaiPerusahaan[$idKriteria])) {
                    $values[] = $nilaiPerusahaan[$idKriteria];
                }
            }

            if (empty($values) || (max($values) == 0 && $info['jenis'] == 'benefit')) {
                continue;
            }

            foreach ($nilaiGrouped as $idPerusahaan => $nilaiPerusahaan) {
                if (!isset($nilaiPerusahaan[$idKriteria])) {
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

        $hasilSaw = [];
        foreach ($normalisasi as $idPerusahaan => $nilaiNorm) {
            $skor = 0;
            foreach ($kriteriaInfo as $idKriteria => $info) {
                $nilaiN = $nilaiNorm[$idKriteria] ?? 0;
                $skor += $nilaiN * ($info['bobot'] ?? 0);
            }
            $hasilSaw[] = [
                'id_perusahaan' => $idPerusahaan,
                'nama_perusahaan' => $perusahaanNama[$idPerusahaan] ?? '-',
                'skor' => round($skor, 4)
            ];
        }

        usort($hasilSaw, fn ($a, $b) => $b['skor'] <=> $a['skor']);

        $perusahaans = Perusahaan::get();

        return view('penilaian_perusahaan.index', [
            'penilaians' => $perusahaans,
            'hasilSaw' => $hasilSaw
        ]);
    }

    public function create()
    {
        if ($this->useNewModel()) {
            $mahasiswa = Mahasiswa::all();
            return view('penilaian-perusahaan.create', compact('mahasiswa'));
        }

        $perusahaans = Perusahaan::all();
        $kriterias = Kriteria::all();
        return view('penilaian_perusahaan.create', compact('perusahaans', 'kriterias'));
    }

    public function store(Request $request)
    {
        if ($this->useNewModel()) {
            $request->validate([
                'id_mahasiswa' => 'required',
                'disiplin' => 'nullable|numeric|min:0|max:100',
                'komunikasi' => 'nullable|numeric|min:0|max:100',
                'kerja_tim' => 'nullable|numeric|min:0|max:100',
                'kerja_mandiri' => 'nullable|numeric|min:0|max:100',
                'penampilan' => 'nullable|numeric|min:0|max:100',
                'sikap_etika' => 'nullable|numeric|min:0|max:100',
                'pengetahuan' => 'nullable|numeric|min:0|max:100',
            ]);

            $fields = [
                'disiplin' => $request->disiplin ?? 0,
                'komunikasi' => $request->komunikasi ?? 0,
                'kerja_tim' => $request->kerja_tim ?? 0,
                'kerja_mandiri' => $request->kerja_mandiri ?? 0,
                'penampilan' => $request->penampilan ?? 0,
                'sikap_etika' => $request->sikap_etika ?? 0,
                'pengetahuan' => $request->pengetahuan ?? 0,
            ];

            if (method_exists('App\\Models\\PenilaianPerusahaan', 'hitungTotalNilai')) {
                $nilaiTotal = \App\Models\PenilaianPerusahaan::hitungTotalNilai($fields);
            } else {
                $nilaiTotal = array_sum($fields) / max(1, count($fields));
            }

            $nilaiHuruf = $this->konversiNilaiHuruf($nilaiTotal);
            $skor = $this->konversiSkor($nilaiTotal);

            \App\Models\PenilaianPerusahaan::create([
                'id_mahasiswa' => $request->id_mahasiswa,
                'disiplin' => $fields['disiplin'],
                'komunikasi' => $fields['komunikasi'],
                'kerja_tim' => $fields['kerja_tim'],
                'kerja_mandiri' => $fields['kerja_mandiri'],
                'penampilan' => $fields['penampilan'],
                'sikap_etika' => $fields['sikap_etika'],
                'pengetahuan' => $fields['pengetahuan'],
                'catatan' => $request->catatan,
                'nilai_total' => $nilaiTotal,
                'nilai_huruf' => $nilaiHuruf,
                'skor' => $skor,
                'id_user' => auth()->id(),
            ]);

            return redirect()->route('penilaian-perusahaan.index')->with('success', 'Penilaian perusahaan berhasil ditambahkan.');
        }

        // Legacy store
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'id_kriteria' => 'required|exists:kriteria,id_kriteria',
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        Penilaian_perusahaan::create($request->all());

        return redirect()->route('penilaian_perusahaan.index')
            ->with('success', 'Penilaian berhasil ditambahkan!');
    }

    public function show($id)
    {
        if ($this->useNewModel()) {
            $penilaian = \App\Models\PenilaianPerusahaan::with('mahasiswa')->findOrFail($id);
            return view('penilaian-perusahaan.show', compact('penilaian'));
        }

        abort(404);
    }

    public function edit($id)
    {
        if ($this->useNewModel()) {
            $penilaian = \App\Models\PenilaianPerusahaan::findOrFail($id);
            $mahasiswa = Mahasiswa::all();
            return view('penilaian-perusahaan.edit', compact('penilaian', 'mahasiswa'));
        }

        $penilaian = Penilaian_perusahaan::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $kriterias = Kriteria::all();

        return view('penilaian_perusahaan.edit', compact('penilaian', 'perusahaans', 'kriterias'));
    }

    public function update(Request $request, $id)
    {
        if ($this->useNewModel()) {
            $penilaian = \App\Models\PenilaianPerusahaan::findOrFail($id);

            $request->validate([
                'id_mahasiswa' => 'required',
                'disiplin' => 'nullable|numeric|min:0|max:100',
                'komunikasi' => 'nullable|numeric|min:0|max:100',
                'kerja_tim' => 'nullable|numeric|min:0|max:100',
                'kerja_mandiri' => 'nullable|numeric|min:0|max:100',
                'penampilan' => 'nullable|numeric|min:0|max:100',
                'sikap_etika' => 'nullable|numeric|min:0|max:100',
                'pengetahuan' => 'nullable|numeric|min:0|max:100',
            ]);

            $fields = [
                'disiplin' => $request->disiplin ?? 0,
                'komunikasi' => $request->komunikasi ?? 0,
                'kerja_tim' => $request->kerja_tim ?? 0,
                'kerja_mandiri' => $request->kerja_mandiri ?? 0,
                'penampilan' => $request->penampilan ?? 0,
                'sikap_etika' => $request->sikap_etika ?? 0,
                'pengetahuan' => $request->pengetahuan ?? 0,
            ];

            if (method_exists('App\\Models\\PenilaianPerusahaan', 'hitungTotalNilai')) {
                $nilaiTotal = \App\Models\PenilaianPerusahaan::hitungTotalNilai($fields);
            } else {
                $nilaiTotal = array_sum($fields) / max(1, count($fields));
            }

            $nilaiHuruf = $this->konversiNilaiHuruf($nilaiTotal);
            $skor = $this->konversiSkor($nilaiTotal);

            $penilaian->update([
                'id_mahasiswa' => $request->id_mahasiswa,
                'disiplin' => $fields['disiplin'],
                'komunikasi' => $fields['komunikasi'],
                'kerja_tim' => $fields['kerja_tim'],
                'kerja_mandiri' => $fields['kerja_mandiri'],
                'penampilan' => $fields['penampilan'],
                'sikap_etika' => $fields['sikap_etika'],
                'pengetahuan' => $fields['pengetahuan'],
                'catatan' => $request->catatan,
                'nilai_total' => $nilaiTotal,
                'nilai_huruf' => $nilaiHuruf,
                'skor' => $skor,
            ]);

            return redirect()->route('penilaian-perusahaan.index')->with('success', 'Penilaian perusahaan berhasil diperbarui.');
        }

        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'id_kriteria' => 'required|exists:kriteria,id_kriteria',
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        Penilaian_perusahaan::findOrFail($id)->update($request->all());

        return redirect()->route('penilaian_perusahaan.index')
            ->with('success', 'Penilaian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if ($this->useNewModel()) {
            $penilaian = \App\Models\PenilaianPerusahaan::findOrFail($id);
            $penilaian->delete();
            return redirect()->route('penilaian-perusahaan.index')->with('success', 'Penilaian perusahaan berhasil dihapus.');
        }

        Penilaian_perusahaan::findOrFail($id)->delete();

        return redirect()->route('penilaian_perusahaan.index')
            ->with('success', 'Penilaian berhasil dihapus!');
    }

    private function konversiNilaiHuruf($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }

    private function konversiSkor($nilai)
    {
        return round(($nilai / 100) * 4, 2);
    }
}
