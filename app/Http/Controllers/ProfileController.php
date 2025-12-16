<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /* ======================================================
     | FORM EDIT PROFIL
     ====================================================== */
    public function edit(Request $request)
    {
        $user = Auth::user();

        // Simpan URL sebelumnya (agar tombol kembali berfungsi)
        if (!session()->has('profile_prev')) {
            $previous = url()->previous();
            $current = route('profile.edit');

            if ($previous && !Str::contains($previous, $current)) {
                session(['profile_prev' => $previous]);
            }
        }

        $previousUrl = session('profile_prev');

        return view('profile.profile', compact('user', 'previousUrl'));
    }

    /* ======================================================
     | UPDATE PROFIL
     ====================================================== */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI DATA UMUM
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar' => ['nullable', 'image', 'max:8192'],
            'new_password' => ['nullable', 'min:6', 'confirmed'], 
        ]);

        // 2. LOGIKA PASSWORD (SOLUSI INTI)
        if ($request->filled('new_password')) {
            
            // Cek: Apakah ini user login biasa (BUKAN user Google)?
            // Kita asumsikan user Google pasti punya kolom 'google_id' yang terisi.
            if (empty($user->google_id)) {
                
                // Jika user biasa (tidak punya google_id), WAJIB masukkan password lama
                if (!$request->filled('current_password')) {
                    return back()->withErrors(['current_password' => 'Harap masukkan kata sandi saat ini untuk konfirmasi.']);
                }

                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
                }
            }
            
            // Jika user Google (google_id ada isinya), kode di atas dilewati (BYPASS).
            // Sistem langsung menyimpan password baru tanpa tanya password lama.
            $user->password = Hash::make($request->new_password);
        }

        // 3. UPDATE DATA LAINNYA
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        // 4. LOGIKA AVATAR (CROP & UPLOAD)
        $avatarCropped = $request->input('avatar_cropped', null);
        if ($avatarCropped) {
            try {
                // Proses base64 image
                $avatarCropped = trim($avatarCropped);
                if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $avatarCropped, $matches)) {
                    $b64 = str_replace(' ', '+', $matches[2]);
                    $decoded = base64_decode($b64);
                    if ($decoded !== false) {
                        $filename = 'avatars/' . Str::random(32) . '.jpg';
                        Storage::disk('public')->put($filename, $decoded);
                        
                        // Hapus avatar lama jika bukan default
                        if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                            Storage::disk('public')->delete($user->avatar);
                        }
                        $user->avatar = $filename;
                    }
                }
            } catch (\Throwable $e) {
                // Silent fail
            }
        } elseif ($request->hasFile('avatar')) {
            // Fallback upload biasa
            $path = $request->file('avatar')->store('avatars', 'public');
            if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
        }

        // 5. SIMPAN KE DATABASE
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui! Sekarang Anda bisa login menggunakan password baru.');
    }
}