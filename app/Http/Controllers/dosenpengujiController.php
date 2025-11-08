<?php

namespace App\Http\Controllers;

use App\Models\dosen_penguji;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DosenPengujiController extends Controller
{
    // Menampilkan daftar dosen penguji
    public function index()
    {
        $dosenPenguji = dosen_penguji::leftjoin('mahasiswa', 'mahasiswa.id_mahasiswa', '=', 'dosen_penguji.id_mahasiswa')
            ->select('dosen_penguji.*', 'mahasiswa.nama as nama_mahasiswa')
            ->get();
        // dd($dosenPenguji);
        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji'));

        // Rename attributes untuk view
        // $dosenPenguji->each(function ($dp) {
        //     if ($dp->Mahasiswa) {
        //         $dp->Mahasiswa->nama_mahasiswa = $dp->Mahasiswa->nama;
        //     }
        // });
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
        $Mahasiswa = Mahasiswa::all();
        return view('dosen_penguji.create', compact('Mahasiswa'));
    }

    // Proses simpan dosen penguji baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
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
        $Mahasiswa = Mahasiswa::all();
        return view('dosen_penguji.edit', compact('dosenPenguji','Mahasiswa'));
    }

    // Proses update dosen penguji
    public function update(Request $request, $id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        $Mahasiswa = Mahasiswa::all();
        $validated = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
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
