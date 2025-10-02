<?php

namespace App\Http\Controllers;

use App\Models\jadwal_bimbingan;
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    /**
     * Menampilkan daftar resource.
     */
    public function index()
    {
        // Ambil semua jadwal secara langsung tanpa mencoba memuat relasi yang tidak ada.
        $jadwals = jadwal_bimbingan::all();
        return view('jadwal_bimbingan', compact('jadwals'));
    }

    /**
     * Menampilkan form untuk membuat resource baru.
     */
    public function create()
    {
        // Tidak perlu mengirim data user karena form menggunakan input teks biasa.
        return view('create_jadwal');
    }

    /**
     * Menyimpan resource yang baru dibuat.
     */
    public function store(Request $request)
    {
        // Lakukan validasi request berdasarkan input form yang sebenarnya.
        $request->validate([
            'mahasiswa' => 'nullable|string|max:255',
            'dosen' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        jadwal_bimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit resource.
     */
    public function edit(jadwal_bimbingan $jadwal)
    {
        // DIUBAH: Memanggil view 'edit_jadwal' yang sesuai dengan nama file
        return view('edit_jadwal', compact('jadwal'));
    }

    /**
     * Memperbarui resource yang ada di storage.
     */
    public function update(Request $request, jadwal_bimbingan $jadwal)
    {
        dd($jadwal, $request->all());
        // Validasi yang benar untuk memperbarui data.
        $request->validate([
            'mahasiswa' => 'nullable|string|max:255',
            'dosen' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.edit', $jadwal->id)->with('success', 'Jadwal berhasil diupdate!');    }

    /**
     * Menghapus resource dari storage.
     */
    public function destroy(jadwal_bimbingan $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
