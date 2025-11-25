<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\JadwalBimbinganController;
use App\Http\Controllers\PenilaianDospemController;
use App\Http\Controllers\RatingDanReviewController;
use App\Http\Controllers\DataDosenPembimbingController;
use App\Http\Controllers\DosenPengujiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PenilaianPengujiController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleDriveController;
use App\Http\Controllers\GoogleController;

// ========== ROUTE LOGIN GOOGLE (SSO) ==========
Route::get('/login', [GoogleController::class, 'redirectToGoogle'])->name('login'); // <--- tambahkan baris ini
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ========== ROUTE GOOGLE DRIVE ==========
Route::get('/drive/files', [GoogleDriveController::class, 'driveFiles'])
    ->middleware('auth')
    ->name('drive.files');

// ========== LOGOUT ==========
Route::post('logout', function(){
    auth()->logout();
    return redirect('/');
})->name('logout');

Route::resource('jadwal', JadwalBimbinganController::class);

// ========================================
// AUTH & BASIC PAGES
// ========================================
Route::view('/', 'login')->name('login');
Route::view('/registrasi', 'registrasi')->name('registrasi');
Route::view('/home', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/menu', 'menu')->name('menu');

// ========================================
// PROFILE
// ========================================
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

// ========================================
// MAHASISWA (CRUD)
// ========================================
Route::resource('mahasiswa', MahasiswaController::class);
Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNIM']);
Route::get('/cek-nim-suggest', [MahasiswaController::class, 'suggestNIM']);

// ========================================
// DOSEN (CRUD)
// ========================================
Route::resource('dosen', DosenController::class);
Route::get('/cek-dosen-suggest', [DosenController::class, 'suggestNIP']);
Route::get('/cek-dosen/{nip}', [DosenController::class, 'cekNIP']);

// ========================================
// DATA DOSEN PEMBIMBING (CRUD)
// ========================================
Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);
Route::get('/cek-nip', [DataDosenPembimbingController::class, 'checkNip'])->name('datadosenpembimbing.checkNip');

// ========================================
// DOSEN PENGUJI (CRUD)
// ========================================
Route::resource('dosen_penguji', DosenPengujiController::class);
Route::get('/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');

// ========================================
// PERUSAHAAN (CRUD)
// ========================================
Route::resource('perusahaan', PerusahaanController::class);

// ========================================
// RATING & REVIEW PERUSAHAAN
// ========================================
Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])->name('ratingperusahaan');
Route::get('/ratingperusahaan/{id_perusahaan}', [RatingDanReviewController::class, 'index'])->name('lihatratingdanreview');
Route::get('/ratingperusahaan/tambah/{id_perusahaan}', [RatingDanReviewController::class, 'create'])->name('tambahratingdanreview');
Route::post('/ratingperusahaan/store', [RatingDanReviewController::class, 'store'])->name('ratingdanreview.store');
Route::get('/ratingperusahaan/edit/{id_review}', [RatingDanReviewController::class, 'edit'])->name('ratingdanreview.edit');
Route::put('/ratingperusahaan/update/{id_review}', [RatingDanReviewController::class, 'update'])->name('ratingdanreview.update');
Route::delete('/ratingperusahaan/delete/{id_review}', [RatingDanReviewController::class, 'destroy'])->name('ratingdanreview.destroy');

// ========================================
// JADWAL BIMBINGAN (CRUD)
// ========================================
Route::resource('jadwal', JadwalBimbinganController::class);

// ========================================
// PENILAIAN DOSEN PEMBIMBING (CRUD)
// ========================================
Route::resource('penilaian', PenilaianDospemController::class);

// ========================================
// PENILAIAN PENGUJI (CRUD)
// ========================================
Route::resource('penilaian-penguji', PenilaianPengujiController::class);

// ========================================
// NILAI MAHASISWA (CRUD)
// ========================================
Route::resource('nilai', NilaiController::class);

// ========================================
// TRANSKRIP (CRUD & ANALYSIS)
// ========================================
Route::resource('transkrip', TranscriptController::class);
Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');

