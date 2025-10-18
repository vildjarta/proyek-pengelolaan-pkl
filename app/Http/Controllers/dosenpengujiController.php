<?php

namespace App\Http\Controllers;

use App\Models\dosen_penguji;
use Illuminate\Http\Request;

class DosenPengujiController extends Controller
{
    // Menampilkan daftar dosen penguji
    public function index()
    {
        $dosenPenguji = dosen_penguji::all();
        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji'));
    }
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Kalau kolom kamu nama_dosen, nip, dan email
        $dosenPenguji = dosen_penguji::where('nama_dosen', 'like', "%{$query}%")
            ->orWhere('nip', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();

        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji', 'query'));
    }

    // Tampilkan form tambah dosen penguji
    public function create()
    {
        return view('dosen_penguji.create');
    }

    // Proses simpan dosen penguji baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'nip'        => 'required|string|max:50|unique:dosen_penguji,nip',
            'email'      => 'required|email|max:255|unique:dosen_penguji,email',
            'no_hp'      => 'nullable|string|max:255',
        ]);

        dosen_penguji::create($validated);

        return redirect()->route('dosen_penguji.index')
            ->with('success', 'Data dosen penguji berhasil ditambahkan!');
    }

    // Tampilkan form edit dosen penguji
    public function edit($id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        return view('dosen_penguji.edit', compact('dosenPenguji'));
    }

    // Proses update dosen penguji
    public function update(Request $request, $id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);

        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'nip'       => 'required|string|max:50' . $id . ',id_penguji',
            'email'     => 'required|email|max:255' . $id . ',id_penguji',
            'no_hp'     => 'nullable|string|max:255',
        ]);

        $dosenPenguji->update($validated);

        return redirect()->route('dosen_penguji.index')
            ->with('success', 'Data dosen penguji berhasil diperbarui!');
    }

    // Proses hapus dosen penguji
    public function destroy($id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        $dosenPenguji->delete();

        return redirect()->route('dosen_penguji.index')
            ->with('success', 'Data dosen penguji berhasil dihapus!');
    }
}
