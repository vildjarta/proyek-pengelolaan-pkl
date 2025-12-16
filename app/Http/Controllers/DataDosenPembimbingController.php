<?php

namespace App\Http\Controllers;

use App\Models\DataDosenPembimbing;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataDosenPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $query = DataDosenPembimbing::with('mahasiswa', 'user');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'LIKE', "%{$q}%")
                    ->orWhere('NIP', 'LIKE', "%{$q}%");
            });
        }

        $data = $query->orderBy('nama')->get();
        return view('datadosenpembimbing.datadosenpembimbing', compact('data'));
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('datadosenpembimbing.tambahdatadosenpembimbing', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required|string|max:100',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string|max:30',
            'nim'   => 'nullable|array',
            'nim.*' => 'exists:mahasiswa,nim',
        ]);

        // ===================== DOSEN =====================
        $dosen = Dosen::firstOrCreate(
            ['nip' => $request->NIP],
            [
                'nama'  => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
            ]
        );

        $dosen->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        // ===================== USER (LOGIN DOSEN) =====================
        if (!$dosen->id_user && $dosen->email) {
            $user = User::firstOrCreate(
                ['email' => $dosen->email],
                [
                    'name'     => $dosen->nama,
                    'password' => Hash::make(Str::random(16)),
                    'role'     => 'dosen_pembimbing',
                ]
            );

            $dosen->update(['id_user' => $user->id]);
        }

        // ===================== DOSEN PEMBIMBING =====================
        $pembimbing = DataDosenPembimbing::create([
            'id_dosen' => $dosen->id_dosen,
            'NIP'      => $request->NIP,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'id_user'  => $dosen->id_user,
        ]);

        // ===================== MAHASISWA =====================
        if ($request->filled('nim')) {
            Mahasiswa::whereIn('nim', $request->nim)
                ->update(['id_pembimbing' => $pembimbing->id_pembimbing]);
        }

        return redirect()
            ->route('datadosenpembimbing.index')
            ->with('success', 'Data dosen pembimbing berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('datadosenpembimbing.edit', $id);
    }

// ... namespace dan use tetap sama
    public function edit($id)
    {
        $item = DataDosenPembimbing::with('mahasiswa')->findOrFail($id);
        
        // --- LOGIKA TAMBAHAN: CEK KEPEMILIKAN DATA ---
        $user = auth()->user();
        
        // Jika user adalah 'dosen_pembimbing' DAN id_user pada data tidak sama dengan id user yang login
        if ($user->role == 'dosen_pembimbing' && $item->id_user != $user->id) {
            return abort(403, 'Anda tidak memiliki akses untuk mengedit data dosen lain.');
        }
        // ---------------------------------------------

        $mahasiswa = Mahasiswa::orderBy('nama')->get();

        return view('datadosenpembimbing.editdatadosenpembimbing', compact('item', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        // --- LOGIKA TAMBAHAN: CEK KEPEMILIKAN DATA (Sama seperti edit) ---
        $user = auth()->user();
        if ($user->role == 'dosen_pembimbing' && $item->id_user != $user->id) {
            return abort(403, 'Anda tidak memiliki akses untuk mengubah data dosen lain.');
        }
        // -----------------------------------------------------------------

        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required|string|max:100',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string|max:30',
            'nim'   => 'nullable|array',
            'nim.*' => 'exists:mahasiswa,nim',
        ]);

        // ... (kode update selanjutnya tetap sama seperti file asli Anda)
        
        $dosen = Dosen::firstOrCreate(['nip' => $request->NIP]);
        $dosen->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        $item->update([
            'id_dosen' => $dosen->id_dosen,
            'NIP'      => $request->NIP,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ]);

        // reset mahasiswa lama
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        if ($request->filled('nim')) {
            Mahasiswa::whereIn('nim', $request->nim)
                ->update(['id_pembimbing' => $item->id_pembimbing]);
        }

        return redirect()
            ->route('datadosenpembimbing.index')
            ->with('success', 'Data dosen pembimbing berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        $item->delete();

        return redirect()
            ->route('datadosenpembimbing.index')
            ->with('success', 'Data dosen pembimbing berhasil dihapus.');
    }

    public function suggest(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') return response()->json([]);

        return Dosen::where('nip', 'LIKE', "%{$q}%")
            ->orWhere('nama', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get(['nip', 'nama', 'email', 'no_hp']);
    }

    public function cekByNip($nip)
    {
        $dosen = Dosen::where('nip', $nip)->first();

        if (! $dosen) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'nama'   => $dosen->nama,
            'email'  => $dosen->email,
            'no_hp'  => $dosen->no_hp,
        ]);
    }
}
