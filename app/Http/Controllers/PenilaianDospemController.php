<?php

namespace App\Http\Controllers;

use App\Models\PenilaianDospem;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PenilaianDospemController extends Controller
{
    /**
     * Menampilkan daftar semua data penilaian.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort');
        $search = $request->query('search');
        $query = PenilaianDospem::query();

        if ($search) {
            $query->where('nama_mahasiswa', 'like', '%' . $search . '%')
                  ->orWhereHas('mahasiswa', function ($q) use ($search) {
                      $q->where('nim', 'like', '%' . $search . '%');
                  });
        }
        
        // Urutkan berdasarkan data terbaru jika tidak ada sort parameter
        if (!$sort) {
            $query->latest();
        }

        $penilaian = $query->get();

        // Lakukan sorting di collection setelah data diambil untuk accessor
        if ($sort) {
            switch ($sort) {
                case 'mahasiswa':
                    $penilaian = $penilaian->sortBy('nama_mahasiswa');
                    break;
                case 'nilai_internal':
                    $penilaian = $penilaian->sortByDesc('nilai_dospem_internal');
                    break;
                case 'nilai':
                    $penilaian = $penilaian->sortByDesc('nilai_akhir');
                    break;
                case 'grade':
                    $penilaian = $penilaian->sortBy('grade');
                    break;
            }
        }

        return view('PenilaianDospem.penilaian_dospem', compact('penilaian', 'search', 'sort'));
    }

    /**
     * Menampilkan form untuk membuat data penilaian baru.
     */
    public function create()
    {
        // Ambil hanya mahasiswa yang memiliki dosen pembimbing
        $mahasiswas = Mahasiswa::whereNotNull('id_pembimbing')->with('dosen')->get();
        return view('PenilaianDospem.create', compact('mahasiswas'));
    }

    /**
     * Menyimpan data penilaian baru ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa|unique:penilaian_dospem,id_mahasiswa',
            'id_pembimbing' => 'required|exists:dosen_pembimbing,id_pembimbing',
            'judul' => 'required|string|max:255',
            'penguasaan_teori' => 'required|integer|min:0|max:100',
            'analisis_pemecahan_masalah' => 'required|integer|min:0|max:100',
            'keaktifan_bimbingan' => 'required|integer|min:0|max:100',
            'penulisan_laporan' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ], [
            'id_mahasiswa.required' => 'Anda harus memilih mahasiswa dari daftar.',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid.',
            'id_mahasiswa.unique' => 'Penilaian untuk mahasiswa ini sudah ada.',
            'id_pembimbing.required' => 'Mahasiswa yang dipilih belum memiliki Dosen Pembimbing.',
        ]);

        $mahasiswa = Mahasiswa::find($request->id_mahasiswa);
        if (!$mahasiswa) {
            return back()->withErrors(['id_mahasiswa' => 'Mahasiswa yang dipilih tidak valid.'])->withInput();
        }

        PenilaianDospem::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_pembimbing' => $request->id_pembimbing,
            'nama_mahasiswa' => $mahasiswa->nama, // Ambil nama dari DB
            'judul' => $request->judul,
            'penguasaan_teori' => $request->penguasaan_teori,
            'analisis_pemecahan_masalah' => $request->analisis_pemecahan_masalah,
            'keaktifan_bimbingan' => $request->keaktifan_bimbingan,
            'penulisan_laporan' => $request->penulisan_laporan,
            'sikap' => $request->sikap,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil disimpan!');
    }

    /**
     * Menampilkan form untuk mengedit data penilaian.
     */
    public function edit($id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);
        // Untuk form edit, kita mungkin tidak perlu daftar mahasiswa lagi
        // kecuali jika Anda ingin memperbolehkan mengubah penilaian ke mahasiswa lain
        return view('PenilaianDospem.edit', compact('penilaian'));
    }
    
    /**
     * Memperbarui data penilaian di dalam database.
     */
    public function update(Request $request, $id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);

        $request->validate([
            // Saat update, kita tidak perlu validasi id_mahasiswa atau id_pembimbing karena tidak diubah
            'judul' => 'required|string|max:255',
            'penguasaan_teori' => 'required|integer|min:0|max:100',
            'analisis_pemecahan_masalah' => 'required|integer|min:0|max:100',
            'keaktifan_bimbingan' => 'required|integer|min:0|max:100',
            'penulisan_laporan' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $penilaian->update([
            // Nama mahasiswa tidak perlu di-update karena sudah terikat dengan ID
            'nama_mahasiswa' => $request->input('nama_mahasiswa', $penilaian->nama_mahasiswa),
            'judul' => $request->judul,
            'penguasaan_teori' => $request->penguasaan_teori,
            'analisis_pemecahan_masalah' => $request->analisis_pemecahan_masalah,
            'keaktifan_bimbingan' => $request->keaktifan_bimbingan,
            'penulisan_laporan' => $request->penulisan_laporan,
            'sikap' => $request->sikap,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $penilaian = PenilaianDospem::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil dihapus!');
    }
}