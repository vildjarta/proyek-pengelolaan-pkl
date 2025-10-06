<?php

namespace App\Http\Controllers;

use App\Models\jadwal_bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini ditambahkan

class JadwalBimbinganController extends Controller
{
    /**
     * Menampilkan daftar resource.
     */
    public function index(Request $request)
    {
        // Ambil input untuk search dan sort
        $search = $request->query('search');
        $sort = $request->query('sort', 'waktu_terdekat'); // Default sort: waktu terdekat

        // Mulai query builder
        $query = jadwal_bimbingan::query();

        // --- LOGIKA PENCARIAN ---
        if ($search) {
            // Cari di kolom 'mahasiswa' atau 'dosen'
            $query->where(function($q) use ($search) {
                $q->where('mahasiswa', 'like', "%{$search}%")
                  ->orWhere('dosen', 'like', "%{$search}%");
            });
        }

        // --- LOGIKA SORTING ---
        switch ($sort) {
            case 'mahasiswa':
                $query->orderBy('mahasiswa', 'asc');
                break;
            case 'dosen':
                $query->orderBy('dosen', 'asc');
                break;
            default: // default ke 'waktu_terdekat'
                // Mengurutkan berdasarkan tanggal dan waktu mulai terdekat
                $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
                break;
        }

        $jadwals = $query->get();

        // Kirim data ke view
        return view('jadwal_bimbingan', compact('jadwals', 'sort', 'search'));
    }

    /**
     * Menampilkan form untuk membuat resource baru.
     */
    public function create()
    {
        return view('create_jadwal');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa' => 'nullable|string|max:255',
            'dosen' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
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
        return view('edit_jadwal', compact('jadwal'));
    }

    /**
     * Memperbarui resource yang ada di storage.
     */
    public function update(Request $request, jadwal_bimbingan $jadwal)
    {
        $request->validate([
            'mahasiswa' => 'nullable|string|max:255',
            'dosen' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());
            
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Menghapus resource dari storage.
     */
    public function destroy(jadwal_bimbingan $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}