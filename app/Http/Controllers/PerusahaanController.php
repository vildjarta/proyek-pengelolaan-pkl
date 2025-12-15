<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
    // Menampilkan daftar perusahaan
    public function index()
    {
        $perusahaans = Perusahaan::all();
        return view('perusahaan.perusahaan', compact('perusahaans'));
    }

    // Tampilkan form tambah perusahaan
    public function create()
    {
        return view('perusahaan.create');
    }

    // Proses simpan perusahaan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'nullable|email',
            'alamat'        => 'required|string|max:255',
            'status'        => 'required|string|max:50',
            'bidang_usaha'  => 'required|string|max:100',
            'fasilitas'     => 'required|string|max:100',
            'level_legalitas' => 'required|string|max:100',
            'jumlah_mahasiswa' => 'required|string|max:100',
            'hari_operasi'   => 'required|string|max:100',
        ]);

        // create or find user for this perusahaan (optional email)
        $user = null;
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        if (!$user) {
            $generatedEmail = $validated['email'] ?? ('perusahaan+' . Str::slug($validated['nama']) . '@local');
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $generatedEmail,
                'password' => Hash::make(Str::random(16)),
                'role' => 'perusahaan',
            ]);
        } else {
            $user->update(['name' => $validated['nama'], 'role' => 'perusahaan']);
        }

        $perusahaanData = $validated;
        $perusahaanData['id_user'] = $user ? $user->id : null;
        Perusahaan::create($perusahaanData);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil ditambahkan!');
    }

    // Tampilkan form edit perusahaan
    public function edit($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('perusahaan.edit', compact('perusahaan'));
    }
    public function show($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('perusahaan.show', compact('perusahaan'));
    }

    // Proses update perusahaan
    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'nullable|email',
            'alamat'        => 'required|string|max:255',
            'status'        => 'required|string|max:50',
            'bidang_usaha'  => 'required|string|max:100',
            'fasilitas'     => 'required|string|max:100',
            'level_legalitas' => 'required|string|max:100',
            'jumlah_mahasiswa' => 'required|string|max:100',
            'hari_operasi'   => 'required|string|max:100',
        ]);

        // keep user in sync if email/name changed
        $user = null;
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        if (!$user && !empty($validated['email'])) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(16)),
                'role' => 'perusahaan',
            ]);
        } elseif ($user) {
            $user->update(['name' => $validated['nama'], 'role' => 'perusahaan']);
        }

        $validated['id_user'] = $user ? $user->id : $perusahaan->id_user;
        $perusahaan->update($validated);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui!');
    }

    // Proses hapus perusahaan
    public function destroy($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus!');
    }
}
