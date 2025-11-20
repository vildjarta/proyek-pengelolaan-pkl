<?php

namespace App\Http\Controllers;

use App\Models\Kriteria; // Model Kriteria
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    // Menampilkan daftar kriteria
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('kriteria.kriteria', compact('kriterias'));
    }

    // Tampilkan form tambah kriteria
    public function create()
    {
        return view('kriteria.create');
    }

    // Proses simpan kriteria baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan' => 'required|integer|exists:perusahaan,id_perusahaan',
            'alternatif'    => 'required|string|max:255',
            'kriteria'      => 'required|string|max:255',
            'bobot'         => 'required|numeric|min:0',
        ]);

        Kriteria::create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan!');
    }

    // Tampilkan form edit kriteria
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('kriteria.edit', compact('kriteria'));
    }

    // Tampilkan detail kriteria
    public function show($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('kriteria.show', compact('kriteria'));
    }

    // Proses update kriteria
    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        $validated = $request->validate([
            'id_perusahaan' => 'required|integer|exists:perusahaan,id_perusahaan',
            'alternatif'    => 'required|string|max:255',
            'kriteria'      => 'required|string|max:255',
            'bobot'         => 'required|numeric|min:0',
        ]);

        $kriteria->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui!');
    }

    // Proses hapus kriteria
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus!');
    }
}