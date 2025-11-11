<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // <-- TAMBAHKAN INI
use Illuminate\Validation\Rule;
use App\Models\User; 

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil (Data Diri).
     */
    public function edit()
    {
        // --- SOLUSI BARU: "Cari user pertama, ATAU Buat user ini JIKA KOSONG" ---
        $user = User::firstOrCreate(
            ['id' => 1], // Cari user dengan id=1
            [   // Data ini akan dipakai untuk MEMBUAT user baru jika id=1 tidak ada
                'name' => 'User Tes',
                'email' => 'tes@example.com',
                'password' => Hash::make('password123') // Wajib ada password
            ]
        );
        // --- AKHIR SOLUSI BARU ---

        return view('profile.profile', compact('user'));
    }

    /**
     * Update data diri pengguna.
     */
    public function update(Request $request)
    {
        // --- SOLUSI SEMENTARA: Ambil user pertama untuk di-update ---
        // Karena fungsi edit() di atas PASTI sudah membuat user, ini akan selalu berhasil.
        $user = User::first(); 
        
        // Kita bisa hapus cek "if (!$user)" karena sekarang user pasti ada.

        // Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        // Handle Upload Foto Profil
        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar != 'avatars/default.png' && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}