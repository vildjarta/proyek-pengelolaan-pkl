<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianPerusahaanController;
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

/*
|--------------------------------------------------------------------------
| Web Routes (bersih & terstruktur)
|--------------------------------------------------------------------------
|
| File ini berisi rute web aplikasi. Pastikan hanya ada SATU deklarasi
| Route::resource('datadosenpembimbing', ...) seperti di bawah.
|
*/

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
Route::post('logout', function () {
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

    // Jadwal (role: mahasiswa, dosen_pembimbing, admin, koordinator)
    Route::middleware(['role:mahasiswa,dosen_pembimbing,admin,koordinator'])->group(function () {
        Route::resource('jadwal', JadwalBimbinganController::class);
    });

    // Transkrip (role: mahasiswa, admin, koordinator)
    Route::middleware(['role:mahasiswa,admin,koordinator'])->group(function () {
        Route::resource('transkrip', TranscriptController::class);

        // Transkrip Analyze
        Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
        Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
        Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');
    });

    // Penilaian pembimbing (role: dosen_pembimbing, admin, koordinator)
    Route::middleware(['role:dosen_pembimbing,admin,koordinator'])->group(function () {
        Route::resource('penilaian', PenilaianDospemController::class);
    });

    // Penilaian penguji (role: dosen_penguji, admin, koordinator)
    Route::middleware(['role:dosen_penguji,admin,koordinator'])->group(function () {
        Route::resource('penilaian-penguji', PenilaianPengujiController::class);
    });

    // Admin & Koordinator: Data master
    Route::middleware(['role:admin,koordinator'])->group(function () {
        Route::resource('perusahaan', PerusahaanController::class);
        Route::resource('/kriteria', KriteriaController::class);
        Route::resource('/penilaian_perusahaan', PenilaianPerusahaanController::class);
        Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);
        Route::resource('mahasiswa', MahasiswaController::class);

        Route::resource('dosen_penguji', DosenPengujiController::class);
        Route::get('/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');

        Route::resource('nilai', NilaiController::class);
        // Optional: resource for general dosen management if needed
        Route::resource('dosen', DosenController::class);
    });

    // Rating & review (gabungan roles)
    Route::middleware(['role:mahasiswa,dosen_pembimbing,admin,koordinator'])->group(function () {
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

        // resource routes (hindari bentrok dengan create/index/show yang kita handle di atas)
        Route::resource('ratingdanreview', RatingDanReviewController::class)
            ->except(['show', 'index', 'create']);
    });

    /*
    |--------------------------------------------------------------------
    | AJAX endpoints (otentikasi diperlukan)
    |--------------------------------------------------------------------
    */
    // Mahasiswa
    Route::get('/cek-nim-suggest', [MahasiswaController::class, 'suggestNIM'])->name('ajax.mahasiswa.suggest');
    Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNIM'])->name('ajax.mahasiswa.byNim');

    // Dosen (autocomplete / lookup)
    Route::get('/cek-dosen-suggest', [DataDosenPembimbingController::class, 'suggest'])->name('ajax.dosen.suggest');
    Route::get('/cek-dosen/{nip}', [DataDosenPembimbingController::class, 'cekByNip'])->name('ajax.dosen.byNip');
});

/*
|--------------------------------------------------------------------------
| (Optional) Debug or helper routes
|--------------------------------------------------------------------------
| Jangan biarkan route debug ini production — hapus jika sudah tidak diperlukan.
*/
// Route::get('/debug-clear-all', function () {
//     \Artisan::call('route:clear');
//     \Artisan::call('view:clear');
//     \Artisan::call('cache:clear');
//     return 'cleared';
// });
