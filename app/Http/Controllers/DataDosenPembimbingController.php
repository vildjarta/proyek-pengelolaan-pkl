<?php

namespace App\Http\Controllers;

use App\Models\DataDosenPembimbing;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DataDosenPembimbingController extends Controller
{
    /**
     * 📋 Tampilkan semua dosen pembimbing (dengan fitur pencarian)
     */
    public function index(Request $request)
    {
        $query = DataDosenPembimbing::with('mahasiswa');

        // 🔍 Fitur pencarian nama dosen
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%');
        }

        $data = $query->get();

        return view('datadosenpembimbing.datadosenpembimbing', compact('data'));
    }

    /**
     * 📝 Form tambah dosen pembimbing
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all(); // Untuk dropdown pemilihan mahasiswa
        return view('datadosenpembimbing.tambahdatadosenpembimbing', compact('mahasiswa'));
    }

    /**
     * 💾 Simpan data dosen pembimbing baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'NIP'   => 'required|digits:18|unique:data_dosen_pembimbing,NIP',
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:data_dosen_pembimbing,email',
            'nim'   => 'required|array|min:1',
            'nim.*' => 'required|exists:mahasiswa,nim',
        ]);

        // Simpan data dosen baru
        $dosen = DataDosenPembimbing::create([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        // Hubungkan mahasiswa ke dosen pembimbing
        foreach ($request->nim as $nim) {
            Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $dosen->id_pembimbing]);
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '✅ Data dosen pembimbing berhasil ditambahkan.');
    }

    /**
     * ✏️ Form edit dosen pembimbing
     */
    public function edit($id)
    {
        $item = DataDosenPembimbing::with('mahasiswa')->findOrFail($id);
        $mahasiswa = Mahasiswa::all(); // Semua mahasiswa untuk pilihan edit
        return view('datadosenpembimbing.editdatadosenpembimbing', compact('item', 'mahasiswa'));
    }

    /**
     * 🔄 Update data dosen pembimbing
     */
    public function update(Request $request, $id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        $request->validate([
            'NIP'   => 'required|digits:18|unique:data_dosen_pembimbing,NIP,' . $item->id_pembimbing . ',id_pembimbing',
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:data_dosen_pembimbing,email,' . $item->id_pembimbing . ',id_pembimbing',
            'nim'   => 'required|array|min:1',
            'nim.*' => 'required|exists:mahasiswa,nim',
        ]);

        // Update data dosen
        $item->update([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        // Reset mahasiswa sebelumnya
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)->update(['id_pembimbing' => null]);

        // Hubungkan mahasiswa baru
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

        // Lepas hubungan dengan mahasiswa
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
                'nama' => $mahasiswa->nama,
            ]);
        }

        return response()->json(['exists' => false]);
    }
}
