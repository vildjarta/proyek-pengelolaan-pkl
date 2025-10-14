<?php

namespace App\Http\Controllers;

use App\Models\PenilaianDospem;
use App\Models\Mahasiswa;
use App\Models\DataDosenPembimbing; // Menggunakan nama Model yang benar
use Illuminate\Http\Request;

class PenilaianDospemController extends Controller
{
    /**
     * Menampilkan daftar semua data penilaian dengan fungsi search dan sort.
     */
    public function index(Request $request)
    {
        // 1. Ambil parameter sort dan search dari URL
        $sort = $request->query('sort');
        $search = $request->query('search');

        // 2. Mulai query builder
        $query = PenilaianDospem::query();

        // 3. Terapkan filter pencarian jika ada
        if ($search) {
            $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        // Definisikan formula perhitungan nilai mentah
        $rawScoreCalculation = '(penguasaan_teori + analisis_pemecahan_masalah + keaktifan_bimbingan + penulisan_laporan + sikap)';

        // 4. Terapkan sorting berdasarkan parameter
        switch ($sort) {
            case 'mahasiswa':
                $query->orderBy('nama_mahasiswa', 'asc');
                break;
            case 'nilai_internal':
            case 'nilai':
            case 'grade':
                $query->orderByRaw($rawScoreCalculation . ' desc');
                break;
            default:
                $query->latest(); // Urutan default
                break;
        }

        // 5. Ambil data setelah di-filter dan di-sort
        $penilaian = $query->get();

        // 6. Kirim data ke view
        return view('PenilaianDospem.penilaian_dospem', compact('penilaian', 'search', 'sort'));
    }

    /**
     * Menampilkan form untuk membuat data penilaian baru.
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('PenilaianDospem.create', compact('mahasiswa'));
    }

    /**
     * Menyimpan data penilaian baru ke dalam database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'nama_mahasiswa' => 'required|string|exists:mahasiswa,nama',
            'judul' => 'required|string|max:255',
            'penguasaan_teori' => 'required|integer|min:0|max:100',
            'analisis_pemecahan_masalah' => 'required|integer|min:0|max:100',
            'keaktifan_bimbingan' => 'required|integer|min:0|max:100',
            'penulisan_laporan' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ], [
            'nama_mahasiswa.exists' => 'Nama mahasiswa yang Anda input tidak terdaftar di database.'
        ]);

        // 2. Cari Mahasiswa berdasarkan nama yang diinput
        $mahasiswa = Mahasiswa::where('nama', $request->nama_mahasiswa)->first();

        // Pastikan mahasiswa memiliki data dosen pembimbing
        if (!$mahasiswa || !$mahasiswa->id_pembimbing) {
            return back()->withInput()->withErrors(['nama_mahasiswa' => 'Mahasiswa yang dipilih belum memiliki Dosen Pembimbing.']);
        }
        
        // Cari Dosen Pembimbing berdasarkan NIP yang ada di tabel mahasiswa
        $dosenPembimbing = DataDosenPembimbing::where('NIP', $mahasiswa->id_pembimbing)->first();

        // Jika dosen dengan NIP tersebut tidak ditemukan, kembalikan error
        if (!$dosenPembimbing) {
            return back()->withInput()->withErrors(['nama_mahasiswa' => 'Data Dosen Pembimbing tidak valid atau tidak ditemukan.']);
        }


        // 3. Buat record baru di database dengan data yang sudah divalidasi
        PenilaianDospem::create([
            'id_mahasiswa' => $mahasiswa->id_mahasiswa, // Menggunakan kolom yang benar dari tabel mahasiswa
            'nama_mahasiswa' => $mahasiswa->nama,
            'judul' => $request->judul,
            'penguasaan_teori' => $request->penguasaan_teori,
            'analisis_pemecahan_masalah' => $request->analisis_pemecahan_masalah,
            'keaktifan_bimbingan' => $request->keaktifan_bimbingan,
            'penulisan_laporan' => $request->penulisan_laporan,
            'sikap' => $request->sikap,
            'catatan' => $request->catatan,
            'id_pembimbing' => $dosenPembimbing->id_pembimbing, // Gunakan ID yang benar
        ]);

        // 4. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil disimpan!');
    }

    /**
     * Menampilkan form untuk mengedit data penilaian.
     */
    public function edit($id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('PenilaianDospem.edit', compact('penilaian', 'mahasiswa'));
    }
    
    /**
     * Memperbarui data penilaian di dalam database.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'nama_mahasiswa' => 'required|string|exists:mahasiswa,nama',
            'judul' => 'required|string|max:255',
            'penguasaan_teori' => 'required|integer|min:0|max:100',
            'analisis_pemecahan_masalah' => 'required|integer|min:0|max:100',
            'keaktifan_bimbingan' => 'required|integer|min:0|max:100',
            'penulisan_laporan' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ], [
            'nama_mahasiswa.exists' => 'Nama mahasiswa yang Anda input tidak terdaftar di database.'
        ]);

        // 2. Cari record penilaian yang akan diupdate
        $penilaian = PenilaianDospem::findOrFail($id);
        
        // 3. Cari Mahasiswa berdasarkan nama yang diinput
        $mahasiswa = Mahasiswa::where('nama', $request->nama_mahasiswa)->first();

        // Pastikan mahasiswa memiliki dosen pembimbing
        if (!$mahasiswa || !$mahasiswa->id_pembimbing) {
            return back()->withInput()->withErrors(['nama_mahasiswa' => 'Mahasiswa yang dipilih belum memiliki Dosen Pembimbing.']);
        }
        
        // Cari Dosen Pembimbing berdasarkan NIP
        $dosenPembimbing = DataDosenPembimbing::where('NIP', $mahasiswa->id_pembimbing)->first();
        if (!$dosenPembimbing) {
            return back()->withInput()->withErrors(['nama_mahasiswa' => 'Data Dosen Pembimbing tidak valid atau tidak ditemukan.']);
        }

        // 4. Update record di database
        $penilaian->update([
            'id_mahasiswa' => $mahasiswa->id_mahasiswa, // Menggunakan kolom yang benar dari tabel mahasiswa
            'nama_mahasiswa' => $mahasiswa->nama,
            'judul' => $request->judul,
            'penguasaan_teori' => $request->penguasaan_teori,
            'analisis_pemecahan_masalah' => $request->analisis_pemecahan_masalah,
            'keaktifan_bimbingan' => $request->keaktifan_bimbingan,
            'penulisan_laporan' => $request->penulisan_laporan,
            'sikap' => $request->sikap,
            'catatan' => $request->catatan,
            'id_pembimbing' => $dosenPembimbing->id_pembimbing, // Gunakan ID yang benar
        ]);

        // 5. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil diperbarui!');
    }
    
    /**
     * Menghapus data penilaian dari database.
     */
    public function destroy($id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil dihapus!');
    }
}

