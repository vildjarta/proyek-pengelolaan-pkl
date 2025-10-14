<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan;
use App\Models\Mahasiswa;
use App\Models\DataDosenPembimbing;
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    public function index()
    {
        // Ambil semua jadwal dan muat relasi mahasiswa dan dosen
        $jadwals = JadwalBimbingan::with(['mahasiswa', 'dosen'])->latest()->get();
        return view('jadwal_bimbingan', compact('jadwals'));
    }

    public function create()
    {
        // Ambil semua data mahasiswa dan dosen untuk dropdown di form
        $mahasiswas = Mahasiswa::all();
        $dosens = DataDosenPembimbing::all();
        return view('create_jadwal', compact('mahasiswas', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        JadwalBimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil ditambahkan!');
    }

    public function edit(JadwalBimbingan $jadwal)
    {
        $mahasiswas = Mahasiswa::all();
        $dosens = DataDosenPembimbing::all();
        return view('edit_jadwal', compact('jadwal', 'mahasiswas', 'dosens'));
    }

    public function update(Request $request, JadwalBimbingan $jadwal)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());
            
        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil diupdate!');
    }

    public function destroy(JadwalBimbingan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil dihapus!');
    }
}
