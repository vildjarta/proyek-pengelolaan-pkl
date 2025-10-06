<?php

namespace App\Http\Controllers;

use App\Models\PenilaianDospem;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PenilaianDospemController extends Controller
{
    public function index()
    {
        $penilaian = PenilaianDospem::with('mahasiswa')->latest()->get();
        return view('PenilaianDospem.penilaian_dospem', compact('penilaian'));
    }

    public function create()
    {
        // Kirim data mahasiswa untuk fitur autocomplete (datalist)__
        $mahasiswa = Mahasiswa::all();
        return view('PenilaianDospem.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|exists:mahasiswa,nama',
            'judul' => 'required|string|max:255',
            'presentasi' => 'required|integer|min:0|max:100',
            'laporan' => 'required|integer|min:0|max:100',
            'penguasaan' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ], [
            'nama_mahasiswa.exists' => 'Nama mahasiswa yang Anda input tidak terdaftar di database.'
        ]);

        $namaInput = $request->nama_mahasiswa;
        $mahasiswaFound = Mahasiswa::where('nama', $namaInput)->get();

        if ($mahasiswaFound->count() > 1) {
            return back()
                ->withErrors(['nama_mahasiswa' => 'Error: Terdapat lebih dari satu mahasiswa dengan nama "' . $namaInput . '". Silakan gunakan NIM untuk input data ini atau perbaiki data master mahasiswa.'])
                ->withInput();
        }

        $mahasiswa = $mahasiswaFound->first();

        PenilaianDospem::create([
            'mahasiswa_id' => $mahasiswa->id,
            'nama_mahasiswa' => $mahasiswa->nama,
            'judul' => $request->judul,
            'presentasi' => $request->presentasi,
            'laporan' => $request->laporan,
            'penguasaan' => $request->penguasaan,
            'sikap' => $request->sikap,
            'catatan' => $request->catatan,
            'dospem_id' => $mahasiswa->dospem_id,
        ]);

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil disimpan!');
    }

    public function edit($id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);
        // Kirim juga daftar mahasiswa untuk datalist di halaman edit
        $mahasiswa = Mahasiswa::all();
        return view('PenilaianDospem.edit', compact('penilaian', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|exists:mahasiswa,nama',
            'judul' => 'required|string|max:255',
            'presentasi' => 'required|integer|min:0|max:100',
            'laporan' => 'required|integer|min:0|max:100',
            'penguasaan' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ], [
            'nama_mahasiswa.exists' => 'Nama mahasiswa yang Anda input tidak terdaftar di database.'
        ]);

        $penilaian = PenilaianDospem::findOrFail($id);
        
        $namaInput = $request->nama_mahasiswa;
        $mahasiswaFound = Mahasiswa::where('nama', $namaInput)->get();
        
        if ($mahasiswaFound->count() > 1) {
            return back()
                ->withErrors(['nama_mahasiswa' => 'Error: Terdapat lebih dari satu mahasiswa dengan nama "' . $namaInput . '". Silakan gunakan NIM untuk input data ini atau perbaiki data master mahasiswa.'])
                ->withInput();
        }

        $mahasiswa = $mahasiswaFound->first();

        $penilaian->update([
            'mahasiswa_id' => $mahasiswa->id,
            'nama_mahasiswa' => $mahasiswa->nama,
            'judul' => $request->judul,
            'presentasi' => $request->presentasi,
            'laporan' => $request->laporan,
            'penguasaan' => $request->penguasaan,
            'sikap' => $request->sikap,
            'catatan' => $request->catatan,
            'dospem_id' => $mahasiswa->dospem_id,
        ]);

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil dihapus!');
    }
}