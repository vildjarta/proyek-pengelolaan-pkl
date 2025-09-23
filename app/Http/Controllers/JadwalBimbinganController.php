<?php

namespace App\Http\Controllers;

use App\Models\jadwal_bimbingan;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    // Menampilkan daftar jadwal.
    public function index()
    {
        $jadwals = jadwal_bimbingan::with(['mahasiswa', 'dosen'])->get();
        return view('jadwal_bimbingan', compact('jadwals'));
    }

    public function create()
    {
        // Ambil data Mahasiswa dan Dosen dari database tanpa filter role
        $mahasiswas = User::get(); 
        $dosens = User::get(); 
        return view('create_jadwal', compact('mahasiswas', 'dosens'));
    }

    // Menyimpan jadwal baru.
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'dosen_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'topik' => 'required|string|max:255',
        ]);

        jadwal_bimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit jadwal.
    public function edit(jadwal_bimbingan $jadwal)
    {
        // Ambil data Mahasiswa dan Dosen dari database
        $mahasiswas = User::get();
        $dosens = User::get();

        return view('jadwal_bimbingan.edit', compact('jadwal', 'mahasiswas', 'dosens'));
    }

    // Memperbarui jadwal.
    public function update(Request $request, JadwalBimbingan $jadwal)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'topik' => 'required|string|max:255',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    // Menghapus jadwal.
    public function destroy(JadwalBimbingan $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}