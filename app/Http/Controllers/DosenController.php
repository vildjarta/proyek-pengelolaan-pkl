<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::orderBy('nama')->get();
        return view('Dosen.datadosen', compact('dosen'));
    }

    public function create()
    {
        return view('Dosen.tambahdatadosen');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:dosen,nip',
            'nama' => 'required',
            'email' => 'nullable|email|unique:dosen,email',
            'nomor_hp' => 'nullable',
        ]);

        Dosen::create($validated);
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan');
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('Dosen.editdatadosen', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|unique:dosen,nip,'.$dosen->id,
            'nama' => 'required',
            'email' => 'nullable|email|unique:dosen,email,'.$dosen->id,
            'nomor_hp' => 'nullable',
        ]);

        $dosen->update($validated);
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus');
    }
}
