<?php

namespace App\Http\Controllers;

use App\Models\DataDosenPembimbing;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DataDosenPembimbingController extends Controller
{
    /**
     * 📋 Tampilkan semua dosen pembimbing
     */
    public function index()
    {
        $data = DataDosenPembimbing::with('mahasiswa')->get();
        return view('datadosenpembimbing.datadosenpembimbing', compact('data'));
    }

    /**
     * 📝 Form tambah dosen pembimbing
     */
    public function create()
    {
        return view('datadosenpembimbing.tambahdatadosenpembimbing');
    }

    /**
     * 💾 Simpan data dosen pembimbing baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required',
            'email' => 'required|email',
            'nim'   => 'required|array|min:1',
            'nim.*' => 'required|exists:mahasiswa,nim',
        ]);

        $dosen = DataDosenPembimbing::create([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        // Update mahasiswa: set id_pembimbing sesuai dosen yang baru dibuat
        foreach ($request->nim as $nim) {
            Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $dosen->id_pembimbing]);
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', 'Data dosen pembimbing berhasil ditambahkan.');
    }

    /**
     * ✏️ Form edit dosen pembimbing
     */
    public function edit($id)
    {
        $item = DataDosenPembimbing::with('mahasiswa')->findOrFail($id);
        $mahasiswa = Mahasiswa::all(); // Ambil semua mahasiswa untuk pilihan di form edit
        return view('datadosenpembimbing.editdatadosenpembimbing', compact('item', 'mahasiswa'));
    }

    /**
     * 🔄 Update data dosen pembimbing
     */
    public function update(Request $request, $id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required',
            'email' => 'required|email',
            'nim'   => 'required|array|min:1',
            'nim.*' => 'required|exists:mahasiswa,nim',
        ]);

        $item->update([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        // Reset semua mahasiswa yang sebelumnya di bawah dosen ini
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)->update(['id_pembimbing' => null]);

        // Set mahasiswa baru ke dosen pembimbing ini
        foreach ($request->nim as $nim) {
            Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $item->id_pembimbing]);
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '✅ Data dosen pembimbing berhasil diperbarui.');
    }

    /**
     * ❌ Hapus dosen pembimbing
     */
    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        // Hapus relasi mahasiswa
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        $item->delete();

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '🗑️ Data dosen pembimbing berhasil dihapus.');
    }

    /**
     * 🔍 Cek apakah NIM mahasiswa ada di database (AJAX)
     */
    public function cekNIM($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            return response()->json([
                'exists' => true,
                'nama_mahasiswa' => $mahasiswa->nama_mahasiswa
            ]);
        }

        return response()->json(['exists' => false]);
    }
}