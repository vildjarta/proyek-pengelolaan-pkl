<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(Request $request)
    {
        $user = Auth::user();

        // Simpan previous URL SEMENTARA (hanya jika belum ada di session)
        if (! session()->has('profile_prev')) {
            $previous = url()->previous();
            $profileEditUrl = route('profile.edit');

            if ($previous && ! Str::contains($previous, $profileEditUrl)) {
                try {
                    // coba parse URL (safely)
                    $previousUrlObj = new \GuzzleHttp\Psr7\Uri($previous);
                    $sameOrigin = ($previousUrlObj->getHost() === request()->getHost());
                } catch (\Throwable $e) {
                    $sameOrigin = Str::startsWith($previous, request()->getSchemeAndHttpHost());
                }

                if ($sameOrigin) {
                    session(['profile_prev' => $previous]);
                }
            }
        }

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

        // Validasi input dasar (file avatar biasa tetap divalidasi)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:8192'], // max dalam KB (8MB)
        ]);

        // Update fields dasar
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        // Debug log: cek apakah payload membawa avatar
        Log::debug("ProfileController@update - avatar_cropped present? " . ($request->filled('avatar_cropped') ? 'yes' : 'no') . ", hasFile(avatar)? " . ($request->hasFile('avatar') ? 'yes' : 'no'));

        // Tangani avatar yang dikirim sebagai dataURL (avatar_cropped) atau file upload biasa
        $avatarCropped = $request->input('avatar_cropped', null);

        if ($avatarCropped) {
            // PROSES base64 data URL
            try {
                $avatarCropped = trim($avatarCropped);

                // regex untuk menangkap mime dan data base64 (multi-line safe)
                if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $avatarCropped, $matches)) {
                    $mime = $matches[1];       // contoh: image/jpeg
                    $b64 = $matches[2];

                    // perbaiki kemungkinan spasi yang mengganti '+'
                    $b64 = str_replace(' ', '+', $b64);

                    $decoded = base64_decode($b64);
                    if ($decoded === false) {
                        Log::error("Avatar decode failed for user_id={$user->id}");
                        return back()->withErrors(['avatar' => 'Gagal memproses gambar.']);
                    }

                    // batasi ukuran hasil decode (mis: 6 MB)
                    $maxBytes = 6 * 1024 * 1024;
                    if (strlen($decoded) > $maxBytes) {
                        Log::warning("Avatar too large after decode for user_id={$user->id}. bytes=" . strlen($decoded));
                        return back()->withErrors(['avatar' => 'Ukuran gambar terlalu besar (maks 6 MB).']);
                    }

                    // tentukan ekstensi dari mime
                    $ext = explode('/', $mime)[1] ?? 'jpg';
                    $ext = strtolower($ext);
                    if ($ext === 'jpeg') $ext = 'jpg';
                    // allowed ext fallback
                    if (! in_array($ext, ['jpg','png','gif','webp'])) {
                        $ext = 'jpg';
                    }

                    // hapus avatar lama bukan default
                    if ($user->avatar && $user->avatar !== 'avatars/default.png' && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }

                    $filename = 'avatars/' . Str::random(32) . '.' . $ext;
                    Storage::disk('public')->put($filename, $decoded);
                    $user->avatar = $filename;
                } else {
                    Log::error("Avatar dataURL didn't match regex for user_id={$user->id}. snippet=" . substr($avatarCropped, 0, 80));
                    return back()->withErrors(['avatar' => 'Format gambar tidak dikenali.']);
                }
            } catch (\Throwable $e) {
                Log::error("Avatar crop save error for user_id={$user->id}: " . $e->getMessage());
                return back()->withErrors(['avatar' => 'Gagal menyimpan gambar.']);
            }
        } elseif ($request->hasFile('avatar')) {
            // PROSES file upload biasa
            try {
                if ($user->avatar && $user->avatar !== 'avatars/default.png' && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            } catch (\Throwable $e) {
                Log::error("Avatar upload error for user_id={$user->id}: " . $e->getMessage());
                return back()->withErrors(['avatar' => 'Gagal mengunggah gambar.']);
            }
        } else {
            // tidak mengubah avatar (tidak ada action)
            Log::debug("No avatar change for user_id={$user->id}");
        }

        // Simpan user
        try {
            $user->save();
        } catch (\Throwable $e) {
            Log::error("Failed to save user profile for user_id={$user->id}: " . $e->getMessage());
            return back()->withErrors(['msg' => 'Gagal menyimpan data profil. Silakan coba lagi.']);
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
