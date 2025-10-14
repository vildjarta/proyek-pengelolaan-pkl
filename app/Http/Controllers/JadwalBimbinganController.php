<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan; 
use App\Models\Mahasiswa;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'waktu_terdekat');

        // Ganti nama model dan gunakan 'with' untuk eager loading (lebih efisien)
        $query = JadwalBimbingan::with(['mahasiswa', 'dosen']);

        if ($search) {
            // Pencarian melalui relasi
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('dosen', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        switch ($sort) {
            case 'mahasiswa':
                // Sorting melalui relasi (membutuhkan join)
                $query->select('jadwal_bimbingans.*')
                      ->leftJoin('mahasiswa', 'jadwal_bimbingans.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
                      ->orderBy('mahasiswa.nama', 'asc');
                break;
            case 'dosen':
                $query->select('jadwal_bimbingans.*')
                      ->leftJoin('dosen_pembimbing', 'jadwal_bimbingans.id_pembimbing', '=', 'dosen_pembimbing.id_pembimbing')
                      ->orderBy('dosen_pembimbing.nama', 'asc');
                break;
            case 'waktu':
                $query->orderBy('waktu_mulai', 'asc');
                break;
            default: // default 'waktu_terdekat'
                $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
                break;
        }

        $jadwals = $query->get();

        return view('jadwal_bimbingan', compact('jadwals', 'sort', 'search'));
    }

    public function create()
    {
        // Ambil semua data mahasiswa dan dosen untuk ditampilkan di form
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = DosenPembimbing::orderBy('nama')->get();
        return view('create_jadwal', compact('mahasiswas', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'nullable|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'nullable|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        JadwalBimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(JadwalBimbingan $jadwal) // Type-hinting yang benar
    {
        // Ambil semua data mahasiswa dan dosen untuk dropdown di form edit
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = DosenPembimbing::orderBy('nama')->get();
        return view('edit_jadwal', compact('jadwal', 'mahasiswas', 'dosens'));
    }

    public function update(Request $request, JadwalBimbingan $jadwal)
    {
        $request->validate([
            'id_mahasiswa' => 'nullable|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'nullable|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy(JadwalBimbingan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}