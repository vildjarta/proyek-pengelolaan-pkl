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
            $query->where('nama', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('NIP', 'LIKE', '%' . $request->search . '%');
        }

        $data = $query->get();

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
            'no_hp' => ['nullable','string','max:30'],
            'nim'   => 'nullable|array',
            'nim.*' => 'nullable|exists:mahasiswa,nim',
        ]);

        // 1) pastikan ada record di tabel dosen (master)
        $dosen = Dosen::where('nip', $request->NIP)->first();

        if (!$dosen) {
            $dosen = Dosen::create([
                'nip' => $request->NIP,
                'nama' => $request->nama,
                'email' => $request->email ?? null,
                'no_hp' => $request->no_hp ?? null,
            ]);
        } else {
            // sinkron data dosen master apabila perlu
            $dosen->update([
                'nama' => $request->nama,
                'email' => $request->email ?? $dosen->email,
                'no_hp' => $request->no_hp ?? $dosen->no_hp,
            ]);
        }

        // 2) buat record dosen_pembimbing dan simpan id_dosen (FK)
        $dosenPembimbing = DataDosenPembimbing::create([
            'id_dosen' => $dosen->id_dosen,
            'NIP' => $request->NIP,
            'nama' => $request->nama,
            'email' => $request->email ?? null,
            'no_hp' => $request->no_hp ?? null,
            'id_user' => $request->input('id_user', null),
        ]);

        // 3) pasang relasi mahasiswa -> id_pembimbing
        if ($request->filled('nim')) {
            foreach ($request->nim as $nim) {
                if ($nim) {
                    Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $dosenPembimbing->id_pembimbing]);
                }
            }
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', 'Data dosen pembimbing berhasil ditambahkan.');
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
            'no_hp' => ['nullable','string','max:30'],
            'nim'   => 'nullable|array',
            'nim.*' => 'nullable|exists:mahasiswa,nim',
        ]);

        // sinkron/mencari dosen master
        $dosen = Dosen::where('nip', $request->NIP)->first();
        if (!$dosen) {
            $dosen = Dosen::create([
                'nip' => $request->NIP,
                'nama' => $request->nama,
                'email' => $request->email ?? null,
                'no_hp' => $request->no_hp ?? null,
            ]);
        } else {
            $dosen->update([
                'nama' => $request->nama,
                'email' => $request->email ?? $dosen->email,
                'no_hp' => $request->no_hp ?? $dosen->no_hp,
            ]);
        }

        // update dosen_pembimbing (simpan id_dosen)
        $item->update([
            'id_dosen' => $dosen->id_dosen,
            'NIP' => $request->NIP,
            'nama' => $request->nama,
            'email' => $request->email ?? null,
            'no_hp' => $request->no_hp ?? null,
        ]);

        // reset mahasiswa yang sebelumnya, lalu pasang yg baru
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)->update(['id_pembimbing' => null]);

        if ($request->filled('nim')) {
            foreach ($request->nim as $nim) {
                if ($nim) {
                    Mahasiswa::where('nim', $nim)->update(['id_pembimbing' => $item->id_pembimbing]);
                }
            }
        }

        return redirect()->route('datadosenpembimbing.index')
            ->with('success', 'Data dosen pembimbing berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);
        Mahasiswa::where('id_pembimbing', $item->id_pembimbing)->update(['id_pembimbing' => null]);
        $item->delete();

        return redirect()->route('datadosenpembimbing.index')->with('success', 'Data dosen pembimbing berhasil dihapus.');
    }

    // optional AJAX cek nip
    public function checkNip(Request $request)
    {
        $nip = $request->query('NIP') ?? $request->NIP ?? null;
        if (!$nip) return response()->json(['exists' => false]);
        $exists = DataDosenPembimbing::where('NIP', $nip)->exists();
        return response()->json(['exists' => $exists]);
    }
}
