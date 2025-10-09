<?php

namespace App\Http\Controllers;

use App\Models\pengujian;
use App\Models\dosen_penguji;
use App\Models\tempat_pengujian;
use Illuminate\Http\Request;

class PengujianController extends Controller
{
    // Menampilkan daftar pengujian dengan relasi
    public function index()
    {
        $pengujian = pengujian::with(['dosen', 'tempat'])->get();
        return view('pengujian.pengujian', compact('pengujian'));
    }

    // Tampilkan form tambah pengujian
    public function create()
    {
        $dosen = dosen_penguji::all();
        $tempat = tempat_pengujian::all();
        return view('pengujian.create', compact('dosen', 'tempat'));
    }

    // Simpan data pengujian baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penguji'  => 'required|exists:dosen_penguji,id_penguji',
            'id_tempat'   => 'required|exists:tempat_pengujian,id_tempat',
            'jam'         => 'required|date_format:H:i',
            'tanggal'    => 'required|date_format:Y-m-d',
        ]);

        pengujian::create($validated);

        return redirect()->route('pengujian.index')
            ->with('success', 'Data pengujian berhasil ditambahkan!');
    }

    // Tampilkan form edit pengujian
    public function edit($id)
    {
        $pengujian = pengujian::findOrFail($id);
        $dosen = dosen_penguji::all();
        $tempat = tempat_pengujian::all();
        return view('pengujian.edit', compact('pengujian', 'dosen', 'tempat'));
    }

    // Proses update pengujian
    public function update(Request $request, $id)
    {
        $pengujian = pengujian::findOrFail($id);
        $validated = $request->validate([
            'id_penguji'  => 'required|exists:dosen_penguji,id_penguji',
            'id_tempat'   => 'required|exists:tempat_pengujian,id_tempat',
            'jam'         => 'required|date_format:H:i:s',
            'tanggal'    => 'required|date_format:Y-m-d',
        ]);


        // dd($validated);
        $pengujian->update($validated);

        return redirect()->route('pengujian.index')
            ->with('success', 'Data pengujian berhasil diperbarui!');
    }


    // Hapus data pengujian
    public function destroy($id)
    {
        $pengujian = pengujian::findOrFail($id);
        $pengujian->delete();

        return redirect()->route('pengujian.index')
            ->with('success', 'Data pengujian berhasil dihapus!');
    }

    // Detail pengujian
    public function show($id)
    {
        $pengujian = pengujian::with(['dosen', 'tempat'])->findOrFail($id);
        return view('pengujian.show', compact('pengujian'));
    }
}
