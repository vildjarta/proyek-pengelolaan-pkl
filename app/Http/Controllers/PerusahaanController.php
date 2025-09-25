<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;

class PerusahaanController extends Controller
{
    // Menampilkan daftar perusahaan
    public function index()
    {
        $perusahaans = Perusahaan::all();
        return view('perusahaan.perusahaan', compact('perusahaans'));
    }

    // Tampilkan form tambah perusahaan
    public function create()
    {
        return view('perusahaan.create');
    }

    // Proses simpan perusahaan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'status'        => 'required|string|max:50',
            'bidang_usaha'  => 'required|string|max:100',
        ]);

        Perusahaan::create($validated);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil ditambahkan!');
    }

    // Tampilkan form edit perusahaan
    public function edit($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('perusahaan.edit', compact('perusahaan'));
    }

    // Proses update perusahaan
    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'status'        => 'required|string|max:50',
            'bidang_usaha'  => 'required|string|max:100',
        ]);

        $perusahaan->update($validated);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui!');
    }

    // Proses hapus perusahaan
    public function destroy($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus!');
    }
}
