<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan;
use App\Models\Mahasiswa;
use App\Models\DataDosenPembimbing; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk Auth

class JadwalBimbinganController extends Controller
{
    /**
     * Menampilkan daftar jadwal bimbingan (Bisa diakses SEMUA role).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'tanggal');
        $sortDirection = $request->query('sort_direction', 'asc');

        $query = JadwalBimbingan::with(['mahasiswa', 'dosen']);

        // (Opsional) Jika Anda ingin Mahasiswa HANYA melihat jadwalnya sendiri, aktifkan kode ini:
        // if (Auth::user()->role === 'mahasiswa') {
        //     // Asumsi User model punya relasi ke Mahasiswa atau berdasarkan email/nama
        //     // $query->whereHas('mahasiswa', function($q) {
        //     //     $q->where('email', Auth::user()->email); 
        //     // });
        // }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('dosen', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $sortableColumns = ['tanggal', 'waktu_mulai'];
        if (in_array($sortBy, $sortableColumns)) {
            if ($sortBy === 'tanggal') {
                $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
            } else {
                $query->orderBy('waktu_mulai', $sortDirection)
                      ->orderBy('tanggal', $sortDirection);
            }
        } else {
            $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
        }

        $jadwals = $query->get();

        return view('jadwal_bimbingan', compact('jadwals', 'search', 'sortBy', 'sortDirection'));
    }

    /**
     * Menampilkan form create (DILARANG UNTUK MAHASISWA).
     */
    public function create()
    {
        // Proteksi: Mahasiswa tidak boleh akses
        if (Auth::user()->role === 'mahasiswa') {
            abort(403, 'Akses Ditolak: Mahasiswa tidak dapat menambah jadwal.');
        }

        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = DataDosenPembimbing::orderBy('nama')->get();
        return view('create_jadwal', compact('mahasiswas', 'dosens'));
    }

    /**
     * Menyimpan data (DILARANG UNTUK MAHASISWA).
     */
    public function store(Request $request)
    {
        // Proteksi
        if (Auth::user()->role === 'mahasiswa') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'id_mahasiswa' => 'nullable|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ], [
            'id_pembimbing.required' => 'Anda harus memilih dosen pembimbing dari daftar.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.'
        ]);

        if ($request->id_mahasiswa) {
            $mahasiswa = Mahasiswa::find($request->id_mahasiswa);
            if ($mahasiswa && $mahasiswa->id_pembimbing != $request->id_pembimbing) {
                return back()->withErrors([
                    'id_pembimbing' => 'Dosen Pembimbing yang dipilih tidak sesuai untuk mahasiswa ini.'
                ])->withInput();
            }
        }

        JadwalBimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil ditambahkan!');
    }

    /**
     * Form Edit (DILARANG UNTUK MAHASISWA).
     */
    public function edit(JadwalBimbingan $jadwal)
    {
        // Proteksi
        if (Auth::user()->role === 'mahasiswa') {
            abort(403, 'Akses Ditolak: Mahasiswa tidak dapat mengedit jadwal.');
        }

        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = DataDosenPembimbing::orderBy('nama')->get();
        return view('edit_jadwal', compact('jadwal', 'mahasiswas', 'dosens'));
    }

    /**
     * Update data (DILARANG UNTUK MAHASISWA).
     */
    public function update(Request $request, JadwalBimbingan $jadwal)
    {
        // Proteksi
        if (Auth::user()->role === 'mahasiswa') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'id_mahasiswa' => 'nullable|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.'
        ]);

        if ($request->id_mahasiswa) {
            $mahasiswa = Mahasiswa::find($request->id_mahasiswa);
            if ($mahasiswa && $mahasiswa->id_pembimbing != $request->id_pembimbing) {
                return back()->withErrors([
                    'id_pembimbing' => 'Dosen Pembimbing yang dipilih tidak sesuai untuk mahasiswa ini.'
                ])->withInput();
            }
        }

        $jadwal->update($request->all());
            
        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil diperbarui!');
    }

    /**
     * Hapus data (DILARANG UNTUK MAHASISWA).
     */
    public function destroy(JadwalBimbingan $jadwal)
    {
        // Proteksi
        if (Auth::user()->role === 'mahasiswa') {
            abort(403, 'Akses Ditolak: Mahasiswa tidak dapat menghapus jadwal.');
        }

        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil dihapus!');
    }
}