<?php

namespace App\Http\Controllers;

use App\Models\PenilaianPenguji;
use Illuminate\Http\Request;

class PenilaianPengujiController extends Controller
{
    /**
     * Tampilkan daftar penilaian dosen penguji
     */
    public function index()
    {
        $penilaian = PenilaianPenguji::all();
        return view('penilaian.daftar_penilaian_dospeng', compact('penilaian'));
    }

    /**
     * Form tambah data penilaian
     */
    public function create()
    {
        return view('penilaian.tambah_penilaian_dospeng');
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20',
            'nama_dosen' => 'required|string|max:100',
            'nama_mahasiswa' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'sikap' => 'nullable|string',
            'penguasaan' => 'nullable|string',
            'nilai' => 'nullable|numeric|min:0|max:100',
            'tanggal_ujian' => 'nullable|date',
            'jenis_ujian' => 'nullable|string|max:50',
            'komentar' => 'nullable|string',
        ]);

        PenilaianPenguji::create($validated);

        return redirect()->route('penilaian.index')
                         ->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Form edit data penilaian
     */
    public function edit($id)
    {
        $penilaian = PenilaianPenguji::findOrFail($id);
        return view('penilaian.edit_penilaian_dospeng', compact('penilaian'));
    }

    /**
     * Update data penilaian
     */
    public function update(Request $request, $id)
    {
        $penilaian = PenilaianPenguji::findOrFail($id);

        $validated = $request->validate([
            'nilai' => 'nullable|numeric|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        $penilaian->update($validated);

        return redirect()->route('penilaian.index')
                         ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Hapus data penilaian
     */
    public function destroy($id)
    {
        $penilaian = PenilaianPenguji::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian.index')
                         ->with('success', 'Data berhasil dihapus!');
    }
}
