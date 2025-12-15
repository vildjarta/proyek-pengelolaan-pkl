<?php

namespace App\Http\Controllers;

use App\Models\DataDosenPembimbing;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DataDosenPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $query = DataDosenPembimbing::with('mahasiswa');

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
            'nim.*' => 'nullable|exists:mahasiswa,nim',
        ]);

        $dosen = Dosen::firstOrCreate(
            ['nip' => $request->NIP],
            ['nama' => $request->nama, 'email' => $request->email, 'no_hp' => $request->no_hp]
        );

        $dosen->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        $dosenPembimbing = DataDosenPembimbing::create([
            'id_dosen' => $dosen->id_dosen ?? null,
            'NIP'      => $request->NIP,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'id_user'  => $request->input('id_user', null),
        ]);

        if ($request->filled('nim')) {
            foreach ($request->nim as $nim) {
                Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $dosenPembimbing->id_pembimbing]);
            }
        }

        return redirect()->route('datadosenpembimbing.index')->with('success', 'Data dosen pembimbing berhasil ditambahkan.');
    }

    // bila user mengakses GET /datadosenpembimbing/{id}, redirect ke edit (hindari 404)
    public function show($id)
    {
        return redirect()->route('datadosenpembimbing.edit', $id);
    }

    public function edit($id)
    {
        $item = DataDosenPembimbing::with('mahasiswa')->findOrFail($id);
        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('datadosenpembimbing.editdatadosenpembimbing', compact('item', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        $request->validate([
            'NIP'   => 'required|digits:18',
            'nama'  => 'required|string|max:100',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string|max:30',
            'nim'   => 'nullable|array',
            'nim.*' => 'nullable|exists:mahasiswa,nim',
        ]);

        $dosen = Dosen::firstOrCreate(['nip' => $request->NIP]);
        $dosen->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        $item->update([
            'id_dosen' => $dosen->id_dosen ?? null,
            'NIP'      => $request->NIP,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ]);

        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)->update(['id_pembimbing' => null]);

        if ($request->filled('nim')) {
            foreach ($request->nim as $nim) {
                Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $item->id_pembimbing]);
            }
        }

        return redirect()->route('datadosenpembimbing.index')->with('success', 'Data dosen pembimbing berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        $item->delete();

        return redirect()->route('datadosenpembimbing.index')->with('success', 'Data dosen pembimbing berhasil dihapus.');
    }

    public function suggest(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') return response()->json([]);

        $results = Dosen::where('nip', 'LIKE', "%{$q}%")
            ->orWhere('nama', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get(['nip', 'nama', 'email', 'no_hp']);

        return response()->json($results);
    }

    public function cekByNip($nip)
    {
        $dosen = Dosen::where('nip', $nip)->first();

        if (! $dosen) return response()->json(['exists' => false]);

        return response()->json([
            'exists' => true,
            'nama'   => $dosen->nama,
            'email'  => $dosen->email,
            'no_hp'  => $dosen->no_hp,
        ]);
    }
}
