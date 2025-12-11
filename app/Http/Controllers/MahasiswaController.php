<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan semua data mahasiswa.
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('mahasiswa.daftar-mahasiswa', compact('mahasiswa'));
    }

    /**
     * Menampilkan form tambah mahasiswa.
     */
    public function create()
    {
        $perusahaan = \App\Models\Perusahaan::orderBy('nama')->get();
        $users = \App\Models\User::orderBy('email')->get();
        return view('mahasiswa.tambah-mahasiswa', compact('perusahaan', 'users'));
    }

    /**
     * Menyimpan data mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nim'        => 'required|regex:/^\d{10,12}$/|unique:mahasiswa,nim',
            'nama'       => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'user_id'    => 'nullable|exists:users,id',
            'email'      => 'required|email|unique:mahasiswa,email',
            'no_hp'      => 'nullable|regex:/^\d{10,15}$/',
            'prodi'      => 'required|string|max:50|regex:/^[A-Za-z\s]+$/',
            'angkatan'   => 'required|integer|digits:4|min:2009|max:' . (date('Y') + 1),
            'ipk'        => 'nullable|numeric|between:0,4.00',
            'perusahaan' => 'nullable|string|max:100',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex'    => 'NIM harus terdiri dari 10 sampai 12 digit angka.',
            'nim.unique'   => 'NIM ini sudah terdaftar.',
            'nama.required'=> 'Nama wajib diisi.',
            'nama.regex'   => 'Nama hanya boleh berisi huruf dan spasi.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'email.required'=> 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid. Pastikan ada tanda @.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'no_hp.regex'  => 'Nomor HP harus terdiri dari 10 sampai 15 digit angka.',
            'prodi.required'=> 'Prodi wajib diisi.',
            'prodi.regex'  => 'Prodi hanya boleh berisi huruf dan spasi.',
            'angkatan.required'=> 'Angkatan wajib diisi.',
            'angkatan.integer' => 'Angkatan harus berupa angka tahun.',
            'angkatan.digits'  => 'Angkatan harus 4 digit angka.',
            'angkatan.min'     => 'Tahun angkatan minimal 2009.',
            'ipk.numeric'      => 'IPK harus berupa angka.',
            'ipk.between'      => 'IPK harus antara 0.00 sampai 4.00.',
            'perusahaan.string'=> 'Nama perusahaan harus berupa teks.',
            'perusahaan.max'   => 'Nama perusahaan maksimal 100 karakter.',
        ]);

        // Jika user_id dipilih, ambil email dari user tersebut
        $data = $request->only([
            'nim','nama','email','no_hp','prodi','angkatan','ipk','perusahaan','user_id'
        ]);
        
        if ($request->user_id) {
            $user = \App\Models\User::find($request->user_id);
            if ($user) {
                $data['email'] = $user->email;
            }
        }

        // Simpan data
        Mahasiswa::create($data);

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    /**
     * Menampilkan detail mahasiswa.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Menampilkan form edit mahasiswa.
     */
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $perusahaan = \App\Models\Perusahaan::orderBy('nama')->get();
        $users = \App\Models\User::orderBy('email')->get();
        return view('mahasiswa.edit-mahasiswa', compact('mahasiswa', 'perusahaan', 'users'));
    }

    /**
     * Mengupdate data mahasiswa.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Validasi
        $request->validate([
            'nim'        => 'required|regex:/^\d{10,12}$/|unique:mahasiswa,nim,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'nama'       => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'user_id'    => 'nullable|exists:users,id',
            'email'      => 'required|email|unique:mahasiswa,email,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'no_hp'      => 'nullable|regex:/^\d{10,15}$/',
            'prodi'      => 'required|string|max:50|regex:/^[A-Za-z\s]+$/',
            'angkatan'   => 'required|integer|digits:4|min:2009|max:' . (date('Y') + 1),
            'ipk'        => 'nullable|numeric|between:0,4.00',
            'perusahaan' => 'nullable|string|max:100',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex'    => 'NIM harus terdiri dari 10 sampai 12 digit angka.',
            'nim.unique'   => 'NIM ini sudah terdaftar.',
            'nama.required'=> 'Nama wajib diisi.',
            'nama.regex'   => 'Nama hanya boleh berisi huruf dan spasi.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'email.required'=> 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid. Pastikan ada tanda @.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'no_hp.regex'  => 'Nomor HP harus terdiri dari 10 sampai 15 digit angka.',
            'prodi.required'=> 'Prodi wajib diisi.',
            'prodi.regex'  => 'Prodi hanya boleh berisi huruf dan spasi.',
            'angkatan.required'=> 'Angkatan wajib diisi.',
            'angkatan.integer' => 'Angkatan harus berupa angka tahun.',
            'angkatan.digits'  => 'Angkatan harus 4 digit angka.',
            'angkatan.min'     => 'Tahun angkatan minimal 2009.',
            'ipk.numeric'      => 'IPK harus berupa angka.',
            'ipk.between'      => 'IPK harus antara 0.00 sampai 4.00.',
            'perusahaan.string'=> 'Nama perusahaan harus berupa teks.',
            'perusahaan.max'   => 'Nama perusahaan maksimal 100 karakter.',
        ]);

        // Jika user_id dipilih, ambil email dari user tersebut
        $data = $request->only([
            'nim','nama','email','no_hp','prodi','angkatan','ipk','perusahaan','user_id'
        ]);
        
        if ($request->user_id) {
            $user = \App\Models\User::find($request->user_id);
            if ($user) {
                $data['email'] = $user->email;
            }
        }

        // Update data
        $mahasiswa->update($data);

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil diupdate');
    }

    /**
     * Menghapus data mahasiswa.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil dihapus');
    }

    /**
     * 🔍 AJAX: Cek NIM mahasiswa dan kirimkan nama ke form dosen pembimbing
     * Route: GET /cek-nim/{nim}
     */
    public function cekNIM($nim)
    {
        // pastikan query pakai nama kolom yang benar (nim)
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            return response()->json([
                'exists' => true,
                'nama_mahasiswa' => $mahasiswa->nama,
                'nim' => $mahasiswa->nim,
                'id_mahasiswa' => $mahasiswa->id_mahasiswa ?? null,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * 🔎 AJAX: Suggest/Autocomplete NIM
     * Route: GET /cek-nim-suggest?q=...
     */
    public function suggestNIM(Request $request)
    {
        $q = (string) $request->query('q', '');
        $q = trim($q);
        if ($q === '') {
            return response()->json([]);
        }

        $results = Mahasiswa::select('nim','nama')
            ->where('nim', 'LIKE', $q . '%')
            ->orWhere('nama', 'LIKE', '%' . $q . '%')
            ->orderBy('nim')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}