<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\dosen_penguji;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class DosenPengujiController extends Controller
{
    // Menampilkan daftar dosen penguji
    public function index()
    {
        $dosenPenguji = dosen_penguji::leftjoin('mahasiswa', 'mahasiswa.id_mahasiswa', '=', 'dosen_penguji.id_mahasiswa')
            ->leftjoin('dosen', 'dosen.id_dosen', '=', 'dosen_penguji.id_dosen')
            ->select(
                'dosen_penguji.id_penguji',  // <--- PASTIKAN INI ADA
                'dosen_penguji.id_dosen',
                'dosen_penguji.id_mahasiswa',
                'mahasiswa.nama as nama_mahasiswa',
                'dosen.nama as nama_dosen',
                'dosen.nip',
                'dosen.email',
                'dosen.no_hp',
                'dosen.id_user'
            )
            ->get();

        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Sesuaikan query pencarian agar join tetap jalan (jika diperlukan) atau gunakan model relations
        // disini saya gunakan query builder yang sama dengan index tapi difilter
        $dosenPenguji = dosen_penguji::leftjoin('mahasiswa', 'mahasiswa.id_mahasiswa', '=', 'dosen_penguji.id_mahasiswa')
            ->leftjoin('dosen', 'dosen.id_dosen', '=', 'dosen_penguji.id_dosen')
            ->select('dosen_penguji.*', 'mahasiswa.nama as nama_mahasiswa', 'dosen.nama as nama_dosen', 'dosen.nip', 'dosen.email', 'dosen.no_hp', 'dosen.id_user')
            ->where('dosen.nama', 'like', "%{$query}%")
            ->orWhere('dosen.nip', 'like', "%{$query}%")
            ->orWhere('dosen.email', 'like', "%{$query}%")
            ->get();

        return view('dosen_penguji.dosen_penguji', compact('dosenPenguji', 'query'));
    }

    // Tampilkan form tambah dosen penguji
    public function create()
    {
        // LOGIKA KEAMANAN: Staff dan Mahasiswa DILARANG akses halaman ini
        if (in_array(Auth::user()->role, ['staff', 'mahasiswa'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $dosen = Dosen::all();
        $Mahasiswa = Mahasiswa::all();
        return view('dosen_penguji.create', compact('Mahasiswa', 'dosen'));
    }

    // Proses simpan dosen penguji baru
    public function store(Request $request)
    {
        // LOGIKA KEAMANAN
        if (in_array(Auth::user()->role, ['staff', 'mahasiswa'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dosen'    => 'required|exists:dosen,id_dosen',
        ]);

        // Opsional: Jika Dosen Penguji menambah data, pastikan dia hanya bisa memilih dirinya sendiri (tergantung kebijakan kampus)
        // Disini kita biarkan terbuka asalkan bukan staff/mahasiswa

        dosen_penguji::create($validated);

        return redirect()->route('dosen_penguji.index')
            ->with('success', 'Data dosen penguji berhasil ditambahkan!');
    }

    // Tampilkan form edit dosen penguji
    public function edit($id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);

        // Ambil data dosen terkait untuk pengecekan user
        $dosen = Dosen::find($dosenPenguji->id_dosen);

        $user = Auth::user();

        // 1. Staff dan Mahasiswa DILARANG
        if (in_array($user->role, ['staff', 'mahasiswa'])) {
            abort(403, 'Anda hanya memiliki akses lihat (Read Only).');
        }

        // 2. Dosen Penguji hanya boleh edit DATANYA SENDIRI
        // (Kecuali Koordinator boleh edit semua)
        if ($user->role == 'dosen_penguji' || $user->role == 'dosen') { // jaga-jaga nama rolenya dosen
            if ($dosen && $dosen->id_user != $user->id) {
                abort(403, 'Anda tidak bisa mengedit data penguji lain.');
            }
        }

        $mahasiswa = Mahasiswa::all();
        $listDosen = Dosen::all();

        return view('dosen_penguji.edit', compact(
            'dosenPenguji',
            'mahasiswa',
            'listDosen'
        ));
    }

    // Proses update dosen penguji
    public function update(Request $request, $id)
    {
        $dosenPenguji = dosen_penguji::findOrFail($id);
        $dosen = Dosen::find($dosenPenguji->id_dosen);
        $user = Auth::user();

        // KEAMANAN (Sama seperti Edit)
        if (in_array($user->role, ['staff', 'mahasiswa'])) {
            abort(403, 'Akses ditolak.');
        }
        if (($user->role == 'dosen_penguji' || $user->role == 'dosen') && $dosen && $dosen->id_user != $user->id) {
            abort(403, 'Anda tidak bisa mengubah data penguji lain.');
        }

        $validated = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dosen'    => 'required|exists:dosen,id_dosen',
        ]);

        $dosenPenguji->update($validated);

        return redirect()->route('dosen_penguji.index')
            ->with('success', 'Data dosen penguji berhasil diperbarui!');
    }

    // Proses hapus dosen penguji
    // Proses hapus dosen penguji
    public function destroy($id)
    {
        // LOGIKA KEAMANAN BARU:
        // HANYA KOORDINATOR yang boleh menghapus.
        // Dosen Penguji (meskipun datanya sendiri) TIDAK BOLEH menghapus.
        if (Auth::user()->role !== 'koordinator') {
            return abort(403, 'Akses ditolak. Hanya Koordinator yang memiliki hak menghapus data.');
        }

        $dosenPenguji = dosen_penguji::findOrFail($id);
        $dosenPenguji->delete();

        return redirect()->route('dosen_penguji.index')
            ->with('success', 'Data dosen penguji berhasil dihapus!');
    }
}
