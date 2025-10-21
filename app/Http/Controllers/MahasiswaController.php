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
        return view('mahasiswa.tambah-mahasiswa');
    }

    /**
     * Menyimpan data mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nim'      => 'required|regex:/^\d{10,12}$/|unique:mahasiswa,nim',
            'nama'     => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'email'    => 'required|email|unique:mahasiswa,email',
            'no_hp'    => 'nullable|regex:/^\d{10,15}$/',
            'prodi'    => 'required|string|max:50|regex:/^[A-Za-z\s]+$/',
            'angkatan' => 'required|integer|digits:4|min:1990|max:' . (date('Y') + 1),
            'ipk'      => 'nullable|numeric|between:0,4.00',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex'    => 'NIM harus terdiri dari 10 sampai 12 digit angka.',
            'nim.unique'   => 'NIM ini sudah terdaftar.',
            'nama.required'=> 'Nama wajib diisi.',
            'nama.regex'   => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required'=> 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid. Pastikan ada tanda @.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'no_hp.regex'  => 'Nomor HP harus terdiri dari 10 sampai 15 digit angka.',
            'prodi.required'=> 'Prodi wajib diisi.',
            'prodi.regex'  => 'Prodi hanya boleh berisi huruf dan spasi.',
            'angkatan.required'=> 'Angkatan wajib diisi.',
            'angkatan.integer' => 'Angkatan harus berupa angka tahun.',
            'angkatan.digits'  => 'Angkatan harus 4 digit angka.',
            'ipk.numeric'      => 'IPK harus berupa angka.',
            'ipk.between'      => 'IPK harus antara 0.00 sampai 4.00.',
        ]);

        // Simpan data
        Mahasiswa::create($request->only([
            'nim','nama','email','no_hp','prodi','angkatan','ipk'
        ]));

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
        return view('mahasiswa.edit-mahasiswa', compact('mahasiswa'));
    }

    /**
     * Mengupdate data mahasiswa.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Validasi
        $request->validate([
            'nim'      => 'required|regex:/^\d{10,12}$/|unique:mahasiswa,nim,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'nama'     => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'email'    => 'required|email|unique:mahasiswa,email,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'no_hp'    => 'nullable|regex:/^\d{10,15}$/',
            'prodi'    => 'required|string|max:50|regex:/^[A-Za-z\s]+$/',
            'angkatan' => 'required|integer|digits:4|min:1990|max:' . (date('Y') + 1),
            'ipk'      => 'nullable|numeric|between:0,4.00',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex'    => 'NIM harus terdiri dari 10 sampai 12 digit angka.',
            'nim.unique'   => 'NIM ini sudah terdaftar.',
            'nama.required'=> 'Nama wajib diisi.',
            'nama.regex'   => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required'=> 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid. Pastikan ada tanda @.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'no_hp.regex'  => 'Nomor HP harus terdiri dari 10 sampai 15 digit angka.',
            'prodi.required'=> 'Prodi wajib diisi.',
            'prodi.regex'  => 'Prodi hanya boleh berisi huruf dan spasi.',
            'angkatan.required'=> 'Angkatan wajib diisi.',
            'angkatan.integer' => 'Angkatan harus berupa angka tahun.',
            'angkatan.digits'  => 'Angkatan harus 4 digit angka.',
            'ipk.numeric'      => 'IPK harus berupa angka.',
            'ipk.between'      => 'IPK harus antara 0.00 sampai 4.00.',
        ]);

        // Update data
        $mahasiswa->update($request->only([
            'nim','nama','email','no_hp','prodi','angkatan','ipk'
        ]));

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
 */
public function cekNIM($nim)
{
    $mahasiswa = \App\Models\Mahasiswa::where('NIM', $nim)->first();

    if ($mahasiswa) {
        return response()->json([
            'exists' => true,
            'nama_mahasiswa' => $mahasiswa->nama,
        ]);
    } else {
        return response()->json(['exists' => false]);
    }
}


}


