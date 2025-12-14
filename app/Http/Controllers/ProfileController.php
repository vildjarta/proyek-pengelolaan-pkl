<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        
        // Logika session URL previous
        if (! session()->has('profile_prev')) {
            $previous = url()->previous();
            $profileEditUrl = route('profile.edit');
            if ($previous && ! Str::contains($previous, $profileEditUrl)) {
                session(['profile_prev' => $previous]);
            }
        }
        $previousUrl = session('profile_prev', null);

        return view('profile.profile', compact('user', 'previousUrl'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI DATA (Hapus validasi current_password dari sini)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar' => ['nullable', 'image', 'max:8192'],
            
            // HANYA validasi format password baru. 
            // Jangan minta current_password di sini agar user Google tidak kena blokir.
            'new_password' => ['nullable', 'min:6', 'confirmed'], 
        ]);

        // 2. LOGIKA PASSWORD
        if ($request->filled('new_password')) {
            
            // PERBAIKAN DISINI:
            // Cek password lama HANYA JIKA user punya password DAN BUKAN user Google (google_id kosong)
            // Pastikan nama kolom 'google_id' sesuai dengan database Anda (bisa google_id, social_id, dsb)
            if (!empty($user->password) && empty($user->google_id)) {
                
                // Jika user biasa, wajib cek password lama
                if (!$request->filled('current_password')) {
                    return back()->withErrors(['current_password' => 'Harap masukkan kata sandi saat ini untuk konfirmasi.']);
                }

                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
                }
            }

            // User Google akan langsung loncat ke sini (bypass pengecekan sandi lama)
            $user->password = Hash::make($request->new_password);
        }

        // 3. Update Data Lainnya
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        // 4. Logika Avatar
        $avatarCropped = $request->input('avatar_cropped', null);
        if ($avatarCropped) {
            try {
                $avatarCropped = trim($avatarCropped);
                if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $avatarCropped, $matches)) {
                    $b64 = str_replace(' ', '+', $matches[2]);
                    $decoded = base64_decode($b64);
                    if ($decoded !== false) {
                        $filename = 'avatars/' . Str::random(32) . '.jpg';
                        Storage::disk('public')->put($filename, $decoded);
                        
                        if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                            Storage::disk('public')->delete($user->avatar);
                        }
                        $user->avatar = $filename;
                    }
                }
            } catch (\Throwable $e) {
                // Silent fail or log
            }
        } elseif ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
        }

        // 5. Simpan
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil dan kata sandi berhasil diperbarui!');
    }
}