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

        // Jika ada query pencarian via query param 'search' (untuk form GET)
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%');
        }

        $data = $query->get();

        return view('datadosenpembimbing.datadosenpembimbing', compact('data'));
    }

    /** 📝 Form tambah dosen pembimbing */
    public function create()
    {
        $mahasiswa = Mahasiswa::all(); // Untuk dropdown/multi-select mahasiswa
        return view('datadosenpembimbing.tambahdatadosenpembimbing', compact('mahasiswa'));
    }

    /** 💾 Simpan data dosen baru */
    public function store(Request $request)
    {
        $request->validate([
            'NIP'   => 'required|digits:18|unique:dosen_pembimbing,NIP',
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:dosen_pembimbing,email',
            'no_hp' => ['nullable', 'regex:/^[0-9+\-\s]{7,20}$/'], // nomor HP sederhana (boleh +, -, spasi)
            'nim'   => 'nullable|array',
            'nim.*' => 'nullable|exists:mahasiswa,nim',
        ], [
            'NIP.required' => 'NIP wajib diisi.',
            'NIP.digits'   => 'NIP harus 18 digit.',
            'NIP.unique'   => 'NIP sudah terdaftar.',
            'email.required'=> 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.regex'  => 'Nomor HP hanya boleh angka, spasi, plus atau strip (7-20 karakter).',
        ]);

        // Simpan dosen baru
        $dosen = DataDosenPembimbing::create([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        // Hubungkan mahasiswa (jika ada)
        if ($request->filled('nim')) {
            foreach ($request->nim as $nim) {
                Mahasiswa::where('nim', $nim)->update([
                    'id_pembimbing' => $dosen->id_pembimbing
                ]);
            }
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
            'NIP'   => 'required|digits:18|unique:dosen_pembimbing,NIP,' . $item->id_pembimbing . ',id_pembimbing',
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:dosen_pembimbing,email,' . $item->id_pembimbing . ',id_pembimbing',
            'no_hp' => ['nullable', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'nim'   => 'nullable|array',
            'nim.*' => 'nullable|exists:mahasiswa,nim',
        ], [
            'NIP.required' => 'NIP wajib diisi.',
            'NIP.digits'   => 'NIP harus 18 digit.',
            'email.required'=> 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid.',
            'no_hp.regex'  => 'Nomor HP hanya boleh angka, spasi, plus atau strip (7-20 karakter).',
        ]);

        // Update data dosen
        $item->update([
            'NIP'   => $request->NIP,
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        // Lepas semua mahasiswa lama
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        // Hubungkan mahasiswa baru (jika ada)
        if ($request->filled('nim')) {
            foreach ($request->nim as $nim) {
                Mahasiswa::where('nim', $nim)
                    ->update(['id_pembimbing' => $item->id_pembimbing]);
            }
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '✅ Data dosen pembimbing berhasil diperbarui.');
    }

    /** ❌ Hapus dosen pembimbing */
    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        // Lepas hubungan mahasiswa
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        // Hapus dosen
        $item->delete();

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', '🗑️ Data dosen pembimbing berhasil dihapus.');
    }

    /** 🔎 Cek apakah NIP dosen sudah ada (opsional AJAX) */
    public function checkNip(Request $request)
    {
        $exists = DataDosenPembimbing::where('NIP', $request->NIP)->exists();
        return response()->json(['exists' => $exists]);
    }
}
