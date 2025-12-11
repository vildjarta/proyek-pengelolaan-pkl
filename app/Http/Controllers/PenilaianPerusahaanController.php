<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenilaianPerusahaan;
use App\Models\Mahasiswa;

class PenilaianPerusahaanController extends Controller
{
    public function index()
    {
        $data = PenilaianPerusahaan::with('mahasiswa')->latest()->get();
        return view('penilaian-perusahaan.index', compact('data'));
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('penilaian-perusahaan.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
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

        // Hitung total nilai dengan bobot
        $nilaiTotal = PenilaianPerusahaan::hitungTotalNilai([
            'disiplin' => $request->disiplin ?? 0,
            'komunikasi' => $request->komunikasi ?? 0,
            'kerja_tim' => $request->kerja_tim ?? 0,
            'kerja_mandiri' => $request->kerja_mandiri ?? 0,
            'penampilan' => $request->penampilan ?? 0,
            'sikap_etika' => $request->sikap_etika ?? 0,
            'pengetahuan' => $request->pengetahuan ?? 0,
        ]);

        $nilaiHuruf = PenilaianPerusahaan::konversiNilaiHuruf($nilaiTotal);
        $skor = PenilaianPerusahaan::konversiSkor($nilaiTotal);

        PenilaianPerusahaan::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'disiplin' => $request->disiplin ?? 0,
            'komunikasi' => $request->komunikasi ?? 0,
            'kerja_tim' => $request->kerja_tim ?? 0,
            'kerja_mandiri' => $request->kerja_mandiri ?? 0,
            'penampilan' => $request->penampilan ?? 0,
            'sikap_etika' => $request->sikap_etika ?? 0,
            'pengetahuan' => $request->pengetahuan ?? 0,
            'catatan' => $request->catatan,
            'nilai_total' => $nilaiTotal,
            'nilai_huruf' => $nilaiHuruf,
            'skor' => $skor,
            'id_user' => auth()->id(),
        ]);

        return redirect()->route('penilaian-perusahaan.index')->with('success', 'Penilaian perusahaan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $penilaian = PenilaianPerusahaan::with('mahasiswa')->findOrFail($id);
        return view('penilaian-perusahaan.show', compact('penilaian'));
    }

    public function edit(string $id)
    {
        $penilaian = PenilaianPerusahaan::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('penilaian-perusahaan.edit', compact('penilaian', 'mahasiswa'));
    }

    public function update(Request $request, string $id)
    {
        $penilaian = PenilaianPerusahaan::findOrFail($id);

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

        // Hitung total nilai dengan bobot
        $nilaiTotal = PenilaianPerusahaan::hitungTotalNilai([
            'disiplin' => $request->disiplin ?? 0,
            'komunikasi' => $request->komunikasi ?? 0,
            'kerja_tim' => $request->kerja_tim ?? 0,
            'kerja_mandiri' => $request->kerja_mandiri ?? 0,
            'penampilan' => $request->penampilan ?? 0,
            'sikap_etika' => $request->sikap_etika ?? 0,
            'pengetahuan' => $request->pengetahuan ?? 0,
        ]);

        $nilaiHuruf = PenilaianPerusahaan::konversiNilaiHuruf($nilaiTotal);
        $skor = PenilaianPerusahaan::konversiSkor($nilaiTotal);

        $penilaian->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'disiplin' => $request->disiplin ?? 0,
            'komunikasi' => $request->komunikasi ?? 0,
            'kerja_tim' => $request->kerja_tim ?? 0,
            'kerja_mandiri' => $request->kerja_mandiri ?? 0,
            'penampilan' => $request->penampilan ?? 0,
            'sikap_etika' => $request->sikap_etika ?? 0,
            'pengetahuan' => $request->pengetahuan ?? 0,
            'catatan' => $request->catatan,
            'nilai_total' => $nilaiTotal,
            'nilai_huruf' => $nilaiHuruf,
            'skor' => $skor,
        ]);

        return redirect()->route('penilaian-perusahaan.index')->with('success', 'Penilaian perusahaan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $penilaian = PenilaianPerusahaan::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian-perusahaan.index')->with('success', 'Penilaian perusahaan berhasil dihapus.');
    }
}
