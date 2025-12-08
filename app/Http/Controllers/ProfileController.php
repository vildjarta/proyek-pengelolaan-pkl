<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Simpan previous URL SEMENTARA (hanya jika belum ada di session)
        // agar tombol Kembali selalu mengarah ke halaman asal user masuk ke edit profile.
        // Kita hindari menyimpan kalau previous adalah halaman edit profile itu sendiri (loop).
        if (! session()->has('profile_prev')) {
            $previous = url()->previous(); // full URL sebelumnya
            $profileEditUrl = route('profile.edit');

            // Pastikan previous ada dan bukan halaman edit profile sendiri
            if ($previous && ! Str::contains($previous, $profileEditUrl)) {
                // Hanya simpan jika same origin (safety) atau Anda ingin mengizinkan external
                try {
                    $prevOrigin = (new \Illuminate\Support\Str); // dummy to avoid unused
                    $previousUrlObj = new \GuzzleHttp\Psr7\Uri($previous);
                    $sameOrigin = ($previousUrlObj->getHost() === request()->getHost());
                } catch (\Throwable $e) {
                    // jika parsing gagal, fallback ke menyimpan (tidak ideal) — tapi kita cek simpler:
                    $sameOrigin = Str::startsWith($previous, request()->getSchemeAndHttpHost());
                }

                if ($sameOrigin) {
                    session(['profile_prev' => $previous]);
                } else {
                    // jika bukan same origin, jangan simpan — fallback akan digunakan.
                }
            }
        }

        // Ambil previousUrl dari session (bisa null)
        $previousUrl = session('profile_prev', null);

        return view('profile.profile', compact('user', 'previousUrl'));
    }

    /**
     * Update data diri pengguna.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Update data dasar (tanpa mengubah email)
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        // Handle upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus foto lama bila bukan default
            if ($user->avatar && $user->avatar !== 'avatars/default.png' && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan file baru dengan nama unik otomatis
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // Redirect kembali ke form edit (kita tidak menghapus session profile_prev di sini
        // sehingga tombol Kembali masih mengarah ke halaman asal)
        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
