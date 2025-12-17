<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\HomeController;
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
use App\Http\Controllers\AHPController;
use App\Http\Controllers\SAWController;
use App\Http\Controllers\ManageUserController;



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

    // Home dashboard (controller provides dynamic data)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
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

    // Transkrip (role: mahasiswa, koordinator, staff, superadmin)
    // Staff perlu akses untuk mengelola nilai/kelayakan
    Route::middleware(['role:mahasiswa,koordinator,ketua_prodi'])->group(function () {
        // Transkrip Analyze PDF - must be defined BEFORE resource route
        Route::get('/transkrip/analyze-pdf', [TranscriptController::class, 'analyzePdfView'])->name('transkrip.analyzePdfView');
        Route::post('/transkrip/upload-pdf', [TranscriptController::class, 'uploadPdf'])->name('transkrip.uploadPdf');

        Route::resource('transkrip', TranscriptController::class);
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

    // penilaian perusahaan (role: koordinator,perusahaan)
    Route::middleware(['role:koordinator,perusahaan'])->group(function () {
        Route::resource('penilaian-perusahaan', PenilaianPerusahaanController::class);
    });

    // data dosen pembimbing (role: koordinator , staff, mahasiswa,dosen pembimbing )
    Route::middleware(['role:dosen_pembimbing,koordinator,staff,mahasiswa'])->group(function () {
        Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);
    });

    // data mahasiswa (role: koordinator , staff, ketua prodi )
    Route::middleware(['role:koordinator,staff,ketua_prodi'])->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
    });

    // data dosen penguji (role: koordinator , staff, dosen penguji, mahasiswa )
    Route::middleware(['role:koordinator,staff,dosen_penguji,mahasiswa'])->group(function () {
        Route::resource('dosen_penguji', DosenPengujiController::class);
        Route::get('/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');
    });

    // Penilaian perusahaan (role: koordinator,perusahaan,staff,ketua_prodi,mahasiswa,dosen_penguji,dosen_pembimbing)
    Route::middleware(['role:koordinator,perusahaan,staff,ketua_prodi,mahasiswa,dosen_penguji,dosen_pembimbing'])->group(function () {
        Route::resource('perusahaan', PerusahaanController::class);
    });


    // nilai akhir (role: koordinator , staff, dosen penguji, mahasiswa )
    Route::middleware(['role:koordinator,dosen_pembimbing,dosen_penguji,mahasiswa'])->group(function () {
        Route::resource('nilai', NilaiController::class);
        Route::get('/api/nilai/get-penilaian/{nim}', [NilaiController::class, 'getNilaiData'])->name('nilai.get-penilaian');
    });

    // data dosen dan manajemen user (role: koordinator)
    Route::middleware(['role:koordinator'])->group(function () {
        Route::resource('dosen', DosenController::class);
        Route::resource('manage-users', ManageUserController::class);
    });

    // Rating & review (gabungan roles)
    Route::middleware(['role:mahasiswa,dosen_pembimbing,dosen_penguji,ketua_prodi,koordinator,staff,perusahaan'])->group(function () {
        // Halaman ranking utama
        Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])->name('ratingperusahaan');

        // Detail rating per perusahaan (id numeric)
        Route::get('/ratingperusahaan/{id_perusahaan}', [RatingDanReviewController::class, 'index'])
            ->name('lihatratingdanreview')
            ->whereNumber('id_perusahaan');
    });

    // Actions that modify data (create/store/edit/update/destroy) are restricted
    // to students (`mahasiswa`) and `koordinator` only.
    Route::middleware(['role:mahasiswa,koordinator'])->group(function () {
        // membuat review untuk perusahaan tertentu (form)
        Route::get('/ratingdanreview/tambah/{id_perusahaan}', [RatingDanReviewController::class, 'create'])
            ->name('tambahratingdanreview')
            ->whereNumber('id_perusahaan');

        // fallback jika akses tanpa id
        Route::get('/ratingdanreview/tambah', function () {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Silakan pilih perusahaan terlebih dahulu untuk menambahkan review.');
        })->name('tambahratingdanreview.fallback');

        // resource routes (store, edit, update, destroy)
        Route::resource('ratingdanreview', RatingDanReviewController::class)
            ->except(['show', 'index', 'create']);

        // Koordinator-only action: delete all ratings
        Route::post('/ratingdanreview/destroy-all', [RatingDanReviewController::class, 'destroyAll'])
            ->name('ratingdanreview.destroyAll');
    });

    // data ahp dan saw (role: koordinator)
    Route::middleware(['role:koordinator'])->group(function () {
        Route::resource('ahp', AHPController::class);
        Route::resource('saw', SAWController::class);
    });

    // AJAX endpoints
    Route::get('/cek-nim-suggest', [MahasiswaController::class, 'suggestNIM'])->name('ajax.mahasiswa.suggest');
    Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNIM'])->name('ajax.mahasiswa.byNim');
    Route::get('/cek-dosen-suggest', [DataDosenPembimbingController::class, 'suggest'])->name('ajax.dosen.suggest');
    Route::get('/cek-dosen/{nip}', [DataDosenPembimbingController::class, 'cekByNip'])->name('ajax.dosen.byNip');
});

/*
|--------------------------------------------------------------------------
| (Optional) Debug or helper routes
|--------------------------------------------------------------------------
| Jangan biarkan route debug ini production — hapus jika sudah tidak diperlukan.
|*/
// Route::get('/debug-clear-all', function () {
//     \Artisan::call('route:clear');
//     \Artisan::call('view:clear');
//     \Artisan::call('cache:clear');
//     return 'cleared';
// });
