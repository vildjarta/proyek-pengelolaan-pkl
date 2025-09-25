<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // READ: tampilkan semua jadwal
    public function index() {
        $jadwal = JadwalBimbingan::orderBy('tanggal')->orderBy('waktu')->get();
        return view('jadwal.daftar-jadwal', compact('jadwal'));
    }

    // CREATE: form tambah
    public function create() {
        return view('jadwal.tambah-jadwal');
    }

    // STORE: simpan data baru
    public function store(Request $request) {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'dosen_pembimbing' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
        ]);

        JadwalBimbingan::create($request->all());

        return redirect()->route('jadwal.index')
                         ->with('success','Jadwal berhasil ditambahkan');
    }

    // EDIT: form edit
    public function edit($id) {
        $jadwal = JadwalBimbingan::findOrFail($id);
        return view('jadwal.edit-jadwal', compact('jadwal'));
    }

    // UPDATE: simpan hasil edit
    public function update(Request $request, $id) {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'dosen_pembimbing' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
        ]);

        $jadwal = JadwalBimbingan::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')
                         ->with('success','Jadwal berhasil diperbarui');
    }

    // DELETE: hapus data
    public function destroy($id) {
        JadwalBimbingan::findOrFail($id)->delete();

        return redirect()->route('jadwal.index')
                         ->with('success','Jadwal berhasil dihapus');
    }
}
