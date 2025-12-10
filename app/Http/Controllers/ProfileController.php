<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit()
    {
        // Mengambil data pengguna yang SEDANG LOGIN (SSO/Google)
        $user = Auth::user();
        
        return view('profile.profile', compact('user'));
    }

    /**
     * Update data diri pengguna.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Ambil user yang sedang login

        // Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email KITA HAPUS dari validasi update, karena tidak boleh diubah
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Update data dasar (TANPA EMAIL)
        $user->name = $request->name;
        // $user->email = $request->email; <--- Baris ini dihapus agar email tidak berubah
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        // Handle Upload Foto Profil
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika bukan default
            if ($user->avatar && $user->avatar != 'avatars/default.png' && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}