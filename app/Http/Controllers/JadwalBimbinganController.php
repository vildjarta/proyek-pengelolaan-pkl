<?php

namespace App\Http\Controllers;

use App\Models\jadwal_bimbingan;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
=======
>>>>>>> 9c7b63e (membaiki di branch)
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
<<<<<<< HEAD
<<<<<<< HEAD
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
=======
    // Menampilkan daftar jadwal.
=======
    /**
     * Menampilkan daftar resource.
     */
>>>>>>> 9c7b63e (membaiki di branch)
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
<<<<<<< HEAD
            'waktu' => 'required|date_format:H:i',
            'topik' => 'required|string|max:255',
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
=======
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
>>>>>>> 9c7b63e (membaiki di branch)
        ]);

        jadwal_bimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * Menampilkan form untuk mengedit resource.
     */
    public function edit(jadwal_bimbingan $jadwal)
    {
        // Model 'jadwal' secara otomatis dikirim melalui route model binding.
        // Anda perlu membuat view 'edit.blade.php' di dalam folder 'jadwal_bimbingan'.
        return view('edit_jadwal', compact('jadwal'));
    }

    /**
     * Memperbarui resource yang ada di storage.
     */
    public function update(Request $request, jadwal_bimbingan $jadwal)
    {
        // Validasi yang benar untuk memperbarui data.
        $request->validate([
            'mahasiswa' => 'nullable|string|max:255',
            'dosen' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
=======
    // Menampilkan form untuk mengedit jadwal.
=======
    /**
     * Menampilkan form untuk mengedit resource.
     */
>>>>>>> 9c7b63e (membaiki di branch)
    public function edit(jadwal_bimbingan $jadwal)
    {
        // Model 'jadwal' secara otomatis dikirim melalui route model binding.
        // Anda perlu membuat view 'edit.blade.php' di dalam folder 'jadwal_bimbingan'.
        return view('edit_jadwal', compact('jadwal'));
    }

    /**
     * Memperbarui resource yang ada di storage.
     */
    public function update(Request $request, jadwal_bimbingan $jadwal)
    {
        // Validasi yang benar untuk memperbarui data.
        $request->validate([
            'mahasiswa' => 'nullable|string|max:255',
            'dosen' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
<<<<<<< HEAD
            'waktu' => 'required|date_format:H:i',
            'topik' => 'required|string|max:255',
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
=======
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
>>>>>>> 9c7b63e (membaiki di branch)
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9c7b63e (membaiki di branch)
    /**
     * Menghapus resource dari storage.
     */
    public function destroy(jadwal_bimbingan $jadwal)
<<<<<<< HEAD
=======
    // Menghapus jadwal.
    public function destroy(JadwalBimbingan $jadwal)
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
=======
>>>>>>> 9c7b63e (membaiki di branch)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
<<<<<<< HEAD
<<<<<<< HEAD
}
=======
}
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
=======
}
>>>>>>> 9c7b63e (membaiki di branch)
