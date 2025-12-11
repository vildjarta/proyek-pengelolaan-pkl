<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenilaianPenguji;
use App\Models\Dosen;
use App\Models\Mahasiswa;

class PenilaianPengujiController extends Controller
{
    public function index()
    {
        $penilaian = PenilaianPenguji::with(['dosen'])->get();
        return view('penilaianpenguji.daftar_penilaian_dospeng', compact('penilaian'));
    }

    public function create()
    {
        $dosen = Dosen::all();
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        return view('penilaianpenguji.tambah_penilaian_dospeng', compact('dosen', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'nama_mahasiswa' => 'required|string|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'materi' => 'required|numeric|min:0|max:100', 
            'hasil' => 'required|numeric|min:0|max:100',
            'objektif' => 'required|numeric|min:0|max:100',
            'laporan' => 'required|numeric|min:0|max:100',
            'tanggal_ujian' => 'required|date',
        ]);

        // Hitung total dan nilai akhir
        $total = ($request->presentasi * 0.10) +
                 ($request->materi * 0.15) +
                 ($request->hasil * 0.40) +
                 ($request->objektif * 0.20) +
                 ($request->laporan * 0.15);

        $validated['total_nilai'] = round($total, 2);
        $validated['nilai_akhir'] = round($total * 0.20, 2);

        // Simpan ke database
        PenilaianPenguji::create($validated);

        // Redirect ke halaman daftar + tampilkan pesan sukses
        return redirect()->route('penilaian-penguji.index')
                         ->with('success', 'Data penilaian berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $penilaian = PenilaianPenguji::findOrFail($id);
        $dosen = Dosen::all();
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        return view('penilaianpenguji.edit_penilaian_dospeng', compact('penilaian', 'dosen', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $penilaian = PenilaianPenguji::findOrFail($id);

        $validated = $request->validate([
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'nama_mahasiswa' => 'required|string|max:100',
            'judul' => 'nullable|string|max:255',
            'presentasi' => 'required|numeric|min:0|max:100',
            'materi' => 'required|numeric|min:0|max:100',
            'hasil' => 'required|numeric|min:0|max:100',
            'objektif' => 'required|numeric|min:0|max:100',
            'laporan' => 'required|numeric|min:0|max:100',
            'tanggal_ujian' => 'required|date',
        ]);

        $total = ($request->presentasi * 0.10) +
                 ($request->materi * 0.15) +
                 ($request->hasil * 0.40) +
                 ($request->objektif * 0.20) +
                 ($request->laporan * 0.15);

        $validated['total_nilai'] = round($total, 2);
        $validated['nilai_akhir'] = round($total * 0.20, 2);

        $penilaian->update($validated);

        return redirect()->route('penilaian-penguji.index')
                         ->with('success', 'Data penilaian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $penilaian = PenilaianPenguji::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian-penguji.index')
                         ->with('success', 'Data penilaian berhasil dihapus!');
    }
}