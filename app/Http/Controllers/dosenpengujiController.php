<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\dosen_penguji;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DosenPengujiController extends Controller
{
    // Menampilkan daftar dosen penguji
    public function index()
    {
        $dosenPenguji = dosen_penguji::leftjoin('mahasiswa', 'mahasiswa.id_mahasiswa', '=', 'dosen_penguji.id_mahasiswa')
            ->leftjoin('dosen', 'dosen.id_dosen', '=', 'dosen_penguji.id_dosen')
            ->select('dosen_penguji.*', 'mahasiswa.nama as nama_mahasiswa', 'dosen.nama as nama_dosen', 'dosen.nip', 'dosen.email', 'dosen.no_hp')
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
        $dosen = Dosen::all();
        $Mahasiswa = Mahasiswa::all();
        return view('dosen_penguji.create', compact('Mahasiswa', 'dosen'));
    }

    // Proses simpan dosen penguji baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dosen'    => 'required|exists:dosen,id_dosen',
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
        $dosen = Dosen::all();
        return view('dosen_penguji.edit', compact('dosenPenguji', 'Mahasiswa', 'dosen'));
    }

    // Proses update dosen penguji
    public function update(Request $request, $id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        $Mahasiswa = Mahasiswa::all();
        $dosen = Dosen::all();
        $validated = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dosen'    => 'required|exists:dosen,id_dosen',
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
