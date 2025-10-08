<?php

namespace App\Http\Controllers;

use App\Models\tempat_pengujian;
use Illuminate\Http\Request;

class TempatPengujianController extends Controller
{
    // Menampilkan daftar tempat pengujian
    public function index()
    {
        $tempat = tempat_pengujian::all();
        return view('tempat_pengujian.tempat_pengujian', compact('tempat'));
    }

    // Tampilkan form tambah tempat pengujian
    public function create()
    {
        return view('tempat_pengujian.create');
    }

    // Proses simpan tempat pengujian baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tempat' => 'required|string|max:255',
        ]);

        tempat_pengujian::create($validated);

        return redirect()->route('tempat_pengujian.index')
            ->with('success', 'Tempat pengujian berhasil ditambahkan!');
    }

    // Tampilkan form edit tempat pengujian
    public function edit($id)
    {
        $tempat = tempat_pengujian::findOrFail($id);
        return view('tempat_pengujian.edit', compact('tempat'));
    }

    // Proses update tempat pengujian
    public function update(Request $request, $id)
    {
        $tempat = tempat_pengujian::findOrFail($id);

        $validated = $request->validate([
            'tempat_pengujian' => 'required|string|max:255',
        ]);

        $tempat->update($validated);

        return redirect()->route('tempat.index')
            ->with('success', 'Tempat pengujian berhasil diperbarui!');
    }

    // Proses hapus tempat pengujian
    public function destroy($id)
    {
        $tempat = tempat_pengujian::findOrFail($id);
        $tempat->delete();

        return redirect()->route('tempat_pengujian.index')
            ->with('success', 'Tempat pengujian berhasil dihapus!');
    }
}
