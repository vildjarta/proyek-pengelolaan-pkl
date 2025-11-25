<?php

use Illuminate\Support\Facades\Route;

// Import Semua Controller
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

/*
|--------------------------------------------------------------------------
| Rute Otentikasi (Login, Logout, Google SSO)
|--------------------------------------------------------------------------
*/

// 1. Halaman Login (Tamu only)
Route::middleware('guest')->group(function () {
    Route::view('/', 'login')->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
    
    // Google SSO
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// 2. Logout
Route::post('logout', function(){
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Aplikasi (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // === RUTE UMUM (Semua User Login) ===
    Route::view('/home', 'home')->name('home');
    Route::view('/about', 'about')->name('about');
    Route::view('/menu', 'menu')->name('menu');
    
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


    // ==========================================================================
    // 📅 RUTE JADWAL BIMBINGAN
    // ==========================================================================
    Route::middleware(['role:mahasiswa,dosen_pembimbing,admin,koordinator'])->group(function () {
        Route::resource('jadwal', JadwalBimbinganController::class);
    });


    // === RUTE TRANSKRIP (Mahasiswa & Admin/Koordinator) ===
    Route::middleware(['role:mahasiswa,admin,koordinator'])->group(function () {
        // Transkrip
        Route::resource('transkrip', TranscriptController::class);
        
        // Transkrip Analyze
        Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
        Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
        Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');
    });

    // === RUTE PENILAIAN PEMBIMBING (Dospem & Admin/Koordinator) ===
    Route::middleware(['role:dosen_pembimbing,admin,koordinator'])->group(function () {
        Route::resource('penilaian', PenilaianDospemController::class);
    });

    // === RUTE PENILAIAN PENGUJI (Penguji & Admin/Koordinator) ===
    Route::middleware(['role:dosen_penguji,admin,koordinator'])->group(function () {
        Route::resource('penilaian-penguji', PenilaianPengujiController::class);
    });

    // === RUTE ADMIN & KOORDINATOR (Data Master) ===
    Route::middleware(['role:admin,koordinator'])->group(function () {
        Route::resource('perusahaan', PerusahaanController::class);
        Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);
        Route::resource('mahasiswa', MahasiswaController::class);
        
        // Dosen Penguji & Search
        Route::resource('dosen_penguji', DosenPengujiController::class);
        Route::get('/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');
        
        // Nilai
        Route::resource('nilai', NilaiController::class);
    });

    // === RUTE RATING & REVIEW (Akses Gabungan) ===
    Route::middleware(['role:mahasiswa,dosen_pembimbing,admin,koordinator'])->group(function () {
        // Halaman Ranking/Utama Rating
        Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])->name('ratingperusahaan');
        
        // PERBAIKAN: Mendefinisikan rute 'tambahratingdanreview' secara manual
        // Mengarah ke method create (halaman form)
        Route::get('/ratingdanreview/tambah', [RatingDanReviewController::class, 'create'])->name('tambahratingdanreview');
        
        // Rute Resource standar (store, update, destroy, dll)
        Route::resource('ratingdanreview', RatingDanReviewController::class)->except(['show', 'index']);
        
        // Halaman detail per perusahaan
        Route::get('/ratingperusahaan/{id_perusahaan}', [RatingDanReviewController::class, 'index'])->name('lihatratingdanreview');
    });

    // AJAX Cek NIM
    Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNIM']);
});