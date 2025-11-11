<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Mahasiswa;

class NilaiController extends Controller
{
    /**
     * Display daftar resource.
     */
    public function index()
    {
        $data = Nilai::with('mahasiswa')->latest()->get();
        return view('nilai.index', compact('data'));
    }

    /**
     * Show form untuk membuat resource yang baru.
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('nilai.create', compact('mahasiswa'));
    }

    /**
     * Store resource yang baru dibuat ke dalam penyimpanan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required',
            'id_nilai' => 'required|unique:nilai_mahasiswa,id_nilai',
        ]);

        // Hitung total nilai
        $nilaiTotal =
            // Pembimbing Lapangan (50%)
            ($request->disiplin ?? 0) +
            ($request->komunikasi ?? 0) +
            ($request->kerja_tim ?? 0) +
            ($request->kerja_mandiri ?? 0) +
            ($request->penampilan ?? 0) +
            ($request->sikap_etika_lapangan ?? 0) +
            ($request->pengetahuan ?? 0) +
            // Dosen Pembimbing (30%)
            ($request->penguasaan_teori ?? 0) +
            ($request->kemampuan_analisis ?? 0) +
            ($request->keaktifan_bimbingan ?? 0) +
            ($request->kemampuan_penulisan_laporan ?? 0) +
            ($request->sikap_etika_dospem ?? 0) +
            // Penguji (20%)
            ($request->penyajian_presentasi ?? 0) +
            ($request->pemahaman_materi ?? 0) +
            ($request->hasil_yang_dicapai ?? 0) +
            ($request->objektivitas_menangapi ?? 0) +
            ($request->penulisan_laporan ?? 0);

        $nilaiHuruf = Nilai::konversiNilaiHuruf($nilaiTotal);
        $skor = Nilai::konversiSkor($nilaiTotal);

        Nilai::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_nilai' => $request->id_nilai,
            // Pembimbing Lapangan
            'disiplin' => $request->disiplin ?? 0,
            'komunikasi' => $request->komunikasi ?? 0,
            'kerja_tim' => $request->kerja_tim ?? 0,
            'kerja_mandiri' => $request->kerja_mandiri ?? 0,
            'penampilan' => $request->penampilan ?? 0,
            'sikap_etika_lapangan' => $request->sikap_etika_lapangan ?? 0,
            'pengetahuan' => $request->pengetahuan ?? 0,
            // Dosen Pembimbing
            'penguasaan_teori' => $request->penguasaan_teori ?? 0,
            'kemampuan_analisis' => $request->kemampuan_analisis ?? 0,
            'keaktifan_bimbingan' => $request->keaktifan_bimbingan ?? 0,
            'kemampuan_penulisan_laporan' => $request->kemampuan_penulisan_laporan ?? 0,
            'sikap_etika_dospem' => $request->sikap_etika_dospem ?? 0,
            // Penguji
            'penyajian_presentasi' => $request->penyajian_presentasi ?? 0,
            'pemahaman_materi' => $request->pemahaman_materi ?? 0,
            'hasil_yang_dicapai' => $request->hasil_yang_dicapai ?? 0,
            'objektivitas_menangapi' => $request->objektivitas_menangapi ?? 0,
            'penulisan_laporan' => $request->penulisan_laporan ?? 0,
            // Catatan
            'catatan_pembimbing' => $request->catatan_pembimbing,
            'catatan_dospem' => $request->catatan_dospem,
            'catatan_penguji' => $request->catatan_penguji,
            // Total
            'nilai_total' => $nilaiTotal,
            'nilai_huruf' => $nilaiHuruf,
            'skor' => $skor,
            'id_user' => $request->id_user ?? null,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * Display nilai yang didalam penyimpanan.
     */
    public function show(string $id)
    {
        $nilai = Nilai::with('mahasiswa')->findOrFail($id);
        return view('nilai.show', compact('nilai'));
    }

    /**
     * Show form editing nilai yang didalam penyimpanan.
     */
    public function edit(string $id)
    {
        $nilai = Nilai::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('nilai.edit', compact('nilai', 'mahasiswa'));
    }

    /**
     * Perbarui nilai yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, string $id)
    {
        $nilai = Nilai::findOrFail($id);

        $request->validate([
            'id_mahasiswa' => 'required',
            'id_nilai' => 'required|unique:nilai_mahasiswa,id_nilai,' . $id,
        ]);

        // Hitung total nilai
        $nilaiTotal =
            // Pembimbing Lapangan (50%)
            ($request->disiplin ?? 0) +
            ($request->komunikasi ?? 0) +
            ($request->kerja_tim ?? 0) +
            ($request->kerja_mandiri ?? 0) +
            ($request->penampilan ?? 0) +
            ($request->sikap_etika_lapangan ?? 0) +
            ($request->pengetahuan ?? 0) +
            // Dosen Pembimbing (30%)
            ($request->penguasaan_teori ?? 0) +
            ($request->kemampuan_analisis ?? 0) +
            ($request->keaktifan_bimbingan ?? 0) +
            ($request->kemampuan_penulisan_laporan ?? 0) +
            ($request->sikap_etika_dospem ?? 0) +
            // Penguji (20%)
            ($request->penyajian_presentasi ?? 0) +
            ($request->pemahaman_materi ?? 0) +
            ($request->hasil_yang_dicapai ?? 0) +
            ($request->objektivitas_menangapi ?? 0) +
            ($request->penulisan_laporan ?? 0);

        $nilaiHuruf = Nilai::konversiNilaiHuruf($nilaiTotal);
        $skor = Nilai::konversiSkor($nilaiTotal);

        $nilai->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_nilai' => $request->id_nilai,
            // Pembimbing Lapangan
            'disiplin' => $request->disiplin ?? 0,
            'komunikasi' => $request->komunikasi ?? 0,
            'kerja_tim' => $request->kerja_tim ?? 0,
            'kerja_mandiri' => $request->kerja_mandiri ?? 0,
            'penampilan' => $request->penampilan ?? 0,
            'sikap_etika_lapangan' => $request->sikap_etika_lapangan ?? 0,
            'pengetahuan' => $request->pengetahuan ?? 0,
            // Dosen Pembimbing
            'penguasaan_teori' => $request->penguasaan_teori ?? 0,
            'kemampuan_analisis' => $request->kemampuan_analisis ?? 0,
            'keaktifan_bimbingan' => $request->keaktifan_bimbingan ?? 0,
            'kemampuan_penulisan_laporan' => $request->kemampuan_penulisan_laporan ?? 0,
            'sikap_etika_dospem' => $request->sikap_etika_dospem ?? 0,
            // Penguji
            'penyajian_presentasi' => $request->penyajian_presentasi ?? 0,
            'pemahaman_materi' => $request->pemahaman_materi ?? 0,
            'hasil_yang_dicapai' => $request->hasil_yang_dicapai ?? 0,
            'objektivitas_menangapi' => $request->objektivitas_menangapi ?? 0,
            'penulisan_laporan' => $request->penulisan_laporan ?? 0,
            // Catatan
            'catatan_pembimbing' => $request->catatan_pembimbing,
            'catatan_dospem' => $request->catatan_dospem,
            'catatan_penguji' => $request->catatan_penguji,
            // Total
            'nilai_total' => $nilaiTotal,
            'nilai_huruf' => $nilaiHuruf,
            'skor' => $skor,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Remove nilai yang ditentukan dalam penyimpanan.
     */
    public function destroy(string $id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }
}
