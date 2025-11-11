<?php

namespace App\Http\Controllers;

use App\Models\dosen_penguji;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenPengujiController extends Controller
{
    /**
     * Menampilkan semua data dosen penguji
     */
    public function index()
    {
        $dosenPenguji = dosen_penguji::with(['Mahasiswa', 'dosen'])
            ->orderBy('id_penguji', 'desc')
            ->get();

        // Pastikan $search tetap dikirim agar tidak undefined
        $search = '';

        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji', 'search'));
    }

    /**
     * Fungsi untuk search
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $dosenPenguji = dosen_penguji::with(['Mahasiswa', 'dosen'])
            ->whereHas('Mahasiswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhereHas('dosen', function ($q) use ($search) {
                $q->where('nama_dosen', 'like', "%{$search}%");
            })
            ->orderBy('id_penguji', 'desc')
            ->get();

        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji', 'search'));
    }

    /**
     * Menampilkan form tambah dosen penguji
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        $dosen = Dosen::all();
        return view('dosen_penguji.create', compact('mahasiswa', 'dosen'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dosen' => 'required|exists:dosen,id_dosen',
        ]);

        dosen_penguji::create($request->only(['id_mahasiswa', 'id_dosen']));

        return redirect()->route('dosen_penguji.index')->with('success', 'Data dosen penguji berhasil ditambahkan.');
    }

    /**
     * Form edit data
     */
    public function edit($id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        $dosen = Dosen::all();
        return view('dosen_penguji.edit', compact('dosenPenguji', 'mahasiswa', 'dosen'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dosen' => 'required|exists:dosen,id_dosen',
        ]);

        $dosenPenguji = dosen_penguji::findOrFail($id);
        $dosenPenguji->update($request->only(['id_mahasiswa', 'id_dosen']));

        return redirect()->route('dosen_penguji.index')->with('success', 'Data dosen penguji berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        $dosenPenguji->delete();

        return redirect()->route('dosen_penguji.index')->with('success', 'Data dosen penguji berhasil dihapus.');
    }
}
