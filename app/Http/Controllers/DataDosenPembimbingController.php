<?php

namespace App\Http\Controllers;

use App\Models\DataDosenPembimbing;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DataDosenPembimbingController extends Controller
{
    /** 📋 Tampilkan semua dosen pembimbing */
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

    /** 📝 Form tambah dosen pembimbing */
    public function create()
    {
        $mahasiswa = Mahasiswa::all(); // Untuk dropdown mahasiswa
        return view('datadosenpembimbing.tambahdatadosenpembimbing', compact('mahasiswa'));
    }

    /** 💾 Simpan data dosen baru */
    public function store(Request $request)
    {
        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required|string|max:100',
            'email' => 'required|email',
            'nim'   => 'required|array|min:1',
            'nim.*' => 'required|exists:mahasiswa,nim',
        ]);

        // Simpan dosen
        $dosen = DataDosenPembimbing::create([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        // Hubungkan mahasiswa ke dosen pembimbing
        foreach ($request->nim as $nim) {
            Mahasiswa::where('nim', $nim)->update([
                'id_pembimbing' => $dosen->id_pembimbing
            ]);
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '✅ Data dosen pembimbing berhasil ditambahkan.');
    }

    /** ✏️ Form edit dosen pembimbing */
    public function edit($id)
    {
        $item = DataDosenPembimbing::with('mahasiswa')->findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('datadosenpembimbing.editdatadosenpembimbing', compact('item', 'mahasiswa'));
    }

    /** 🔄 Update data dosen */
    public function update(Request $request, $id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required|string|max:100',
            'email' => 'required|email',
            'nim'   => 'required|array|min:1',
            'nim.*' => 'required|exists:mahasiswa,nim',
        ]);

        $item->update([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        // Lepas semua mahasiswa lama
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        // Tambah mahasiswa baru
        foreach ($request->nim as $nim) {
            Mahasiswa::where('nim', $nim)
                ->update(['id_pembimbing' => $item->id_pembimbing]);
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '✅ Data dosen pembimbing berhasil diperbarui.');
    }

    /** ❌ Hapus dosen pembimbing */
    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        $item->delete();

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '🗑️ Data dosen pembimbing berhasil dihapus.');
    }

    /** 🔍 Cek NIM mahasiswa */
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
