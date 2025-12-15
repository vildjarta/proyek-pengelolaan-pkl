<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::orderBy('nama')->get();
        return view('Dosen.datadosen', compact('dosen'));
    }

    public function create()
    {
        return view('Dosen.tambahdatadosen');
    }

    public function store(Request $request)
    {
        // pastikan key 'no_hp' ada (fallback dari 'nomor_hp' jika client mengirim nama lama)
        $noHpValue = $request->input('no_hp', $request->input('nomor_hp', null));
        $request->merge(['no_hp' => $noHpValue]);

        $validated = $request->validate([
            'nip'   => 'required|digits:18|unique:dosen,nip',
            'nama'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:dosen,email',
            'no_hp' => 'nullable|string|max:50',
        ], [
            'nip.digits' => 'NIP harus 18 digit.',
            'nip.required' => 'NIP wajib diisi.',
        ]);

        // create or find user account for this dosen
        $user = null;
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        if (!$user) {
            $generatedEmail = $validated['email'] ?? ('dosen+' . $validated['nip'] . '@local');
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $generatedEmail,
                'password' => Hash::make(Str::random(16)),
                'role' => 'dosen',
            ]);
        } else {
            // ensure role & name are in sync
            $user->update(['name' => $validated['nama'], 'role' => 'dosen']);
        }

        Dosen::create([
            'id_user' => $user ? $user->id : null,
            'nip'   => $validated['nip'],
            'nama'  => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
        ]);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan');
    }

    public function edit($id)
    {
        // $id di-route adalah id_dosen; Model punya primaryKey id_dosen, jadi findOrFail bekerja
        $dosen = Dosen::findOrFail($id);
        return view('Dosen.editdatadosen', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $noHpValue = $request->input('no_hp', $request->input('nomor_hp', null));
        $request->merge(['no_hp' => $noHpValue]);

        // IMPORTANT: karena primary key di DB bernama `id_dosen`, ketika menggunakan rule unique dan ignore current id
        // kita harus menyebutkan nama kolom primary key ('id_dosen') di akhir rule.
        $validated = $request->validate([
            // ignore current record by id_dosen column
            'nip'   => 'required|digits:18|unique:dosen,nip,' . $dosen->id_dosen . ',id_dosen',
            'nama'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:dosen,email,' . $dosen->id_dosen . ',id_dosen',
            'no_hp' => 'nullable|string|max:50',
        ], [
            'nip.digits' => 'NIP harus 18 digit.',
            'nip.required' => 'NIP wajib diisi.',
        ]);

        // keep or create linked user account when updating
        $user = null;
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        if (!$user && !empty($validated['email'])) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(16)),
                'role' => 'dosen',
            ]);
        } elseif ($user) {
            $user->update(['name' => $validated['nama'], 'role' => 'dosen']);
        }

        $dosen->update([
            'id_user' => $user ? $user->id : $dosen->id_user,
            'nip'   => $validated['nip'],
            'nama'  => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
        ]);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus');
    }

    public function suggestNIP(Request $request)
    {
        $q = $request->query('q', '');
        if (trim($q) === '') return response()->json([]);

        $list = Dosen::select('nip', 'nama', 'email', 'no_hp')
            ->where('nip', 'LIKE', $q . '%')
            ->orWhere('nama', 'LIKE', '%' . $q . '%')
            ->orderBy('nip')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'nip' => $r->nip,
                'nama' => $r->nama,
                'email' => $r->email,
                'no_hp' => $r->no_hp,
            ]);

        return response()->json($list);
    }

    public function cekNIP($nip)
    {
        $d = Dosen::where('nip', $nip)->first();
        if ($d) {
            return response()->json([
                'exists' => true,
                'nip' => $d->nip,
                'nama' => $d->nama,
                'email' => $d->email,
                'no_hp' => $d->no_hp,
            ]);
        }
        return response()->json(['exists' => false]);
    }
}
