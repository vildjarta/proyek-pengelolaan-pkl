<?php

namespace App\Http\Controllers;

use App\Models\jadwal_bimbingan;
<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
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
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
        ]);

        jadwal_bimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

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
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

<<<<<<< HEAD
    /**
     * Menghapus resource dari storage.
     */
    public function destroy(jadwal_bimbingan $jadwal)
=======
    // Menghapus jadwal.
    public function destroy(JadwalBimbingan $jadwal)
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
