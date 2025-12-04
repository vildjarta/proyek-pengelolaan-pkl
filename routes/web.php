<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\JadwalBimbinganController;
use App\Http\Controllers\PenilaianDospemController;
use App\Http\Controllers\PenilaianPengujiController;
use App\Http\Controllers\RatingDanReviewController;
use App\Http\Controllers\DataDosenPembimbingController;
use App\Http\Controllers\DosenPengujiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ManageUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Super Admin (KHUSUS KOORDINATOR)
|--------------------------------------------------------------------------
| Hanya Koordinator yang boleh mengelola User (Tambah/Edit/Hapus Akun).
*/
Route::middleware(['auth', 'role:koordinator'])->group(function () {
    Route::resource('manage-users', ManageUserController::class);
});

/*
|--------------------------------------------------------------------------
| Guest routes (unauthenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::view('/', 'login')->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

    // Google SSO
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Logout (authenticated)
Route::post('logout', function(){
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated routes (all logged-in users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Static pages
    Route::view('/home', 'home')->name('home');
    Route::view('/about', 'about')->name('about');
    Route::view('/menu', 'menu')->name('menu');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /* * PERUBAHAN:
     * - 'admin' dihapus (digantikan koordinator).
     * - 'staff' ditambahkan ke rute manajemen (Jadwal, Transkrip, Data Master).
     */

    // Jadwal (role: mahasiswa, dosen_pembimbing, koordinator, staff)
    Route::middleware(['role:mahasiswa,dosen_pembimbing,koordinator,staff'])->group(function () {
        Route::resource('jadwal', JadwalBimbinganController::class);
    });

    // Transkrip (role: mahasiswa, koordinator, staff)
    // Staff perlu akses untuk mengelola nilai/kelayakan
    Route::middleware(['role:mahasiswa,koordinator,staff'])->group(function () {
        Route::resource('transkrip', TranscriptController::class);

        // Transkrip Analyze
        Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
        Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
        Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');
    });

    // Penilaian pembimbing (role: dosen_pembimbing, koordinator, staff)
    // Staff ditambahkan untuk rekapitulasi nilai jika perlu
    Route::middleware(['role:dosen_pembimbing,koordinator,staff'])->group(function () {
        Route::resource('penilaian', PenilaianDospemController::class);
    });

    // Penilaian penguji (role: dosen_penguji, koordinator, staff)
    Route::middleware(['role:dosen_penguji,koordinator,staff'])->group(function () {
        Route::resource('penilaian-penguji', PenilaianPengujiController::class);
    });

    // Data Master (Dikelola oleh Koordinator & Staff)
    // Menggantikan 'role:admin,koordinator' menjadi 'role:koordinator,staff'
    Route::middleware(['role:koordinator,staff'])->group(function () {
        Route::resource('perusahaan', PerusahaanController::class);
        Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);
        Route::resource('mahasiswa', MahasiswaController::class);

        Route::resource('dosen_penguji', DosenPengujiController::class);
        Route::get('/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');

        Route::resource('nilai', NilaiController::class);
        Route::resource('dosen', DosenController::class);
    });

    // Rating & review (gabungan roles)
    // 'admin' dihapus, 'staff' ditambahkan
    Route::middleware(['role:mahasiswa,dosen_pembimbing,koordinator,staff'])->group(function () {
        // Halaman ranking utama
        Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])->name('ratingperusahaan');

        // Detail rating per perusahaan (id numeric)
        Route::get('/ratingperusahaan/{id_perusahaan}', [RatingDanReviewController::class, 'index'])
            ->name('lihatratingdanreview')
            ->whereNumber('id_perusahaan');

        // membuat review untuk perusahaan tertentu
        Route::get('/ratingdanreview/tambah/{id_perusahaan}', [RatingDanReviewController::class, 'create'])
            ->name('tambahratingdanreview')
            ->whereNumber('id_perusahaan');

        // fallback jika akses tanpa id
        Route::get('/ratingdanreview/tambah', function () {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Silakan pilih perusahaan terlebih dahulu untuk menambahkan review.');
        })->name('tambahratingdanreview.fallback');

        // resource routes
        Route::resource('ratingdanreview', RatingDanReviewController::class)
            ->except(['show', 'index', 'create']);
    });

    // AJAX endpoints
    Route::get('/cek-nim-suggest', [MahasiswaController::class, 'suggestNIM'])->name('ajax.mahasiswa.suggest');
    Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNIM'])->name('ajax.mahasiswa.byNim');
    Route::get('/cek-dosen-suggest', [DataDosenPembimbingController::class, 'suggest'])->name('ajax.dosen.suggest');
    Route::get('/cek-dosen/{nip}', [DataDosenPembimbingController::class, 'cekByNip'])->name('ajax.dosen.byNip');
});
