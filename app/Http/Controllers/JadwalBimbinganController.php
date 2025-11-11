<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan;
use App\Models\Mahasiswa;
use App\Models\DataDosenPembimbing; // Pastikan nama model ini sesuai dengan model dosen Anda
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    /**
     * Menampilkan daftar jadwal bimbingan dengan fitur pencarian dan pengurutan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mengambil parameter dari URL untuk pencarian dan pengurutan
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'tanggal');
        $sortDirection = $request->query('sort_direction', 'asc');

        // Memulai query dengan eager loading untuk efisiensi
        $query = JadwalBimbingan::with(['mahasiswa', 'dosen']);

        // Menerapkan filter pencarian jika ada input dari pengguna
        if ($search) {
            // Mengelompokkan kondisi WHERE untuk memastikan logika OR berjalan dengan benar
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('dosen', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        // Menerapkan logika pengurutan (sorting)
        $sortableColumns = ['tanggal', 'waktu_mulai'];
        if (in_array($sortBy, $sortableColumns)) {
            if ($sortBy === 'tanggal') {
                // Jika diurutkan berdasarkan tanggal, urutan kedua adalah waktu
                $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
            } else {
                // Jika diurutkan berdasarkan waktu, urutan kedua adalah tanggal
                $query->orderBy('waktu_mulai', $sortDirection)
                      ->orderBy('tanggal', $sortDirection);
            }
        } else {
            // Pengurutan default jika parameter tidak valid
            $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
        }

        // Mengeksekusi query dan mengambil hasilnya
        $jadwals = $query->get();

        // Mengirimkan data ke view
        return view('jadwal_bimbingan', compact('jadwals', 'search', 'sortBy', 'sortDirection'));
    }

    /**
     * Menampilkan form untuk membuat jadwal bimbingan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengambil data mahasiswa dan dosen untuk ditampilkan di dropdown form
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = DataDosenPembimbing::orderBy('nama')->get();
        return view('create_jadwal', compact('mahasiswas', 'dosens'));
    }

    /**
     * Menyimpan data jadwal bimbingan baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ], [
            'id_mahasiswa.required' => 'Anda harus memilih mahasiswa dari daftar.',
            'id_pembimbing.required' => 'Anda harus memilih dosen pembimbing dari daftar.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.'
        ]);

        // Validasi kustom untuk memastikan dosen pembimbing sesuai dengan mahasiswa
        $mahasiswa = Mahasiswa::find($request->id_mahasiswa);
        if ($mahasiswa && $mahasiswa->id_pembimbing != $request->id_pembimbing) {
            return back()->withErrors([
                'id_pembimbing' => 'Dosen Pembimbing yang dipilih tidak sesuai untuk mahasiswa ini.'
            ])->withInput();
        }

        // Membuat data baru jika semua validasi berhasil
        JadwalBimbingan::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit jadwal bimbingan yang sudah ada.
     * Menggunakan Route Model Binding untuk mengambil data JadwalBimbingan.
     *
     * @param  \App\Models\JadwalBimbingan  $jadwal
     * @return \Illuminate\View\View
     */
    public function edit(JadwalBimbingan $jadwal)
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = DataDosenPembimbing::orderBy('nama')->get();
        return view('edit_jadwal', compact('jadwal', 'mahasiswas', 'dosens'));
    }

    /**
     * Memperbarui data jadwal bimbingan di dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JadwalBimbingan  $jadwal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, JadwalBimbingan $jadwal)
    {
        // Validasi data yang masuk dari form edit
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.'
        ]);

        // Validasi kustom untuk memastikan dosen pembimbing sesuai dengan mahasiswa
        $mahasiswa = Mahasiswa::find($request->id_mahasiswa);
        if ($mahasiswa && $mahasiswa->id_pembimbing != $request->id_pembimbing) {
            return back()->withErrors([
                'id_pembimbing' => 'Dosen Pembimbing yang dipilih tidak sesuai untuk mahasiswa ini.'
            ])->withInput();
        }

        // Memperbarui data di database
        $jadwal->update($request->all());
            
        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil diperbarui!');
    }

    /**
     * Menghapus data jadwal bimbingan dari database.
     *
     * @param  \App\Models\JadwalBimbingan  $jadwal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JadwalBimbingan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil dihapus!');
    }
}

