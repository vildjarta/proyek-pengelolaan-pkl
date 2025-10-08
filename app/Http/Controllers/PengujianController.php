<?php

namespace App\Http\Controllers;

use App\Models\Pengujian;
use Illuminate\Http\Request;

class PengujianController extends Controller
{
    // Menampilkan daftar pengujian
    public function index()
    {
        $pengujian = Pengujian::all();
        return view('pengujian.index', compact('pengujian'));
    }

    // Tampilkan form tambah pengujian
    public function create()
    {
        return view('pengujian.create');
    }

    // Proses simpan pengujian baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_dosen'     => 'required|integer',
            'id_mahasiswa' => 'required|integer',
            'id_tempat'    => 'required|integer',
            'jam'          => 'required|string|max:20',
            'tanggal'      => 'required|date',
        ]);

        Pengujian::create($validated);

        return redirect()->route('pengujian.index')
            ->with('success', 'Data pengujian berhasil ditambahkan!');
    }

    // Tampilkan form edit pengujian
    public function edit($id)
    {
        $pengujian = Pengujian::findOrFail($id);
        return view('pengujian.edit', compact('pengujian'));
    }

    // Proses update pengujian
    public function update(Request $request, $id)
    {
        $pengujian = Pengujian::findOrFail($id);

        $validated = $request->validate([
            'id_dosen'     => 'required|integer',
            'id_mahasiswa' => 'required|integer',
            'id_tempat'    => 'required|integer',
            'jam'          => 'required|string|max:20',
            'tanggal'      => 'required|date',
        ]);

        $pengujian->update($validated);

        return redirect()->route('pengujian.index')
            ->with('success', 'Data pengujian berhasil diperbarui!');
    }

    // Proses hapus pengujian
    public function destroy($id)
    {
        $pengujian = Pengujian::findOrFail($id);
        $pengujian->delete();

        return redirect()->route('pengujian.index')
            ->with('success', 'Data pengujian berhasil dihapus!');
    }
}
