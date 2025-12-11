<?php

namespace App\Http\Controllers;

// PASTIKAN ANDA MENG-IMPORT KELAS INI
use App\Models\User; // Ganti dengan model User Anda jika perlu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite; // <-- SANGAT PENTING
use Illuminate\Support\Str; // <-- KITA TAMBAHKAN INI untuk cek email

class GoogleController extends Controller
{
    /**
     * Mengalihkan pengguna ke halaman otentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Mendapatkan informasi pengguna dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data pengguna dari Google
            $googleUser = Socialite::driver('google')->user();

            // --- LOGIKA BARU UNTUK MENEMUKAN/MEMBUAT USER ---

            // 1. Cek dulu berdasarkan google_id (prioritas utama)
            $user = User::where('google_id', $googleUser->id)->first();

            // 2. Jika tidak ketemu, cek berdasarkan email
            // Ini untuk "mendeteksi akun yang sudah ada" (dibuat via login form)
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Akun ada, tapi belum ada google_id. Update google_id-nya.
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            // 3. Jika user masih tidak ada, kita buat user baru DENGAN ROLE
            if (!$user) {
                $email = $googleUser->email;
                $role = null;

                // 4. Logika Penentuan Peran (Role)
                // (PERUBAHAN DI SINI: Menyesuaikan domain ke @politala.ac.id)
                if (Str::endsWith($email, '@mhs.politala.ac.id')) {
                    $role = 'mahasiswa';
                } elseif (Str::endsWith($email, '@politala.ac.id')) {
                    // Ini untuk 5 aktor Anda selain mahasiswa
                    // Kita set default 'dosen_pembimbing' (atau 'dosen')
                    // Anda bisa tambahkan logika lebih lanjut jika emailnya
                    // perlu dicek ke tabel dosen/admin untuk peran spesifik.
                    $role = 'dosen_pembimbing';
                }

                // 5. Tolak email publik (e.g., @gmail.com)
                if (is_null($role)) {
                    return redirect('/')->with('error', 'Login Google gagal. Akun Anda harus menggunakan email resmi institusi.');
                }

                // Buat pengguna baru DENGAN ROLE
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $email,
                    'google_id' => $googleUser->id,
                    'role' => $role, // <-- INI DIA PENYESUAIANNYA
                    'password' => bcrypt(uniqid()), // Buat password acak
                ]);
            }

            // --- AKHIR LOGIKA BARU ---

            // Login-kan pengguna yang ditemukan atau baru dibuat
            Auth::login($user);

            // Redirect ke halaman home setelah berhasil login
            return redirect('/home');
        } catch (\Exception $e) {
            // dd($e); // Aktifkan ini hanya untuk debugging
            // Jika ada error, kembali ke halaman login
            return redirect('/')->with('error', 'Login gagal. Silakan coba lagi.');
        }
    }
}
