<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /* ======================================================
     | FORM EDIT PROFIL
     ====================================================== */
    public function edit(Request $request)
    {
        $user = Auth::user();

        // Simpan URL sebelumnya (selain halaman edit profil)
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

        // ================= VALIDASI =================
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender'       => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar'       => ['nullable', 'image', 'max:8192'],

            // password baru (opsional)
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ]);

        /* ======================================================
         | LOGIKA PASSWORD
         | - User biasa: wajib isi current_password
         | - User Google (password NULL): boleh langsung set
         ====================================================== */
        if ($request->filled('new_password')) {

            // Jika user punya password lama → wajib konfirmasi
            if (!empty($user->password)) {

                if (!$request->filled('current_password')) {
                    return back()->withErrors([
                        'current_password' => 'Kata sandi saat ini wajib diisi.'
                    ]);
                }

                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors([
                        'current_password' => 'Kata sandi saat ini tidak sesuai.'
                    ]);
                }
            }

            $user->password = Hash::make($request->new_password);
        }

        /* ======================================================
         | UPDATE DATA DASAR
         ====================================================== */
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        /* ======================================================
         | AVATAR (CROPPED BASE64 / FILE UPLOAD)
         ====================================================== */
        $avatarCropped = $request->input('avatar_cropped');

        try {
            if ($avatarCropped) {

                if (preg_match('/^data:image\/(\w+);base64,/', $avatarCropped, $type)) {
                    $data = substr($avatarCropped, strpos($avatarCropped, ',') + 1);
                    $data = base64_decode($data);

                    if ($data !== false) {
                        $filename = 'avatars/' . Str::uuid() . '.jpg';
                        Storage::disk('public')->put($filename, $data);

                        // hapus avatar lama
                        if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                            Storage::disk('public')->delete($user->avatar);
                        }

                        $user->avatar = $filename;
                    }
                }

            } elseif ($request->hasFile('avatar')) {

                $path = $request->file('avatar')->store('avatars', 'public');

                if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                    Storage::disk('public')->delete($user->avatar);
                }

                $user->avatar = $path;
            }

        } catch (\Throwable $e) {
            Log::warning('Avatar upload failed: ' . $e->getMessage());
        }

        /* ======================================================
         | SIMPAN
         ====================================================== */
        $user->save();

        // Redirect ke halaman asal jika ada
        $redirectTo = session()->pull('profile_prev', route('profile.edit'));

        return redirect($redirectTo)
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
