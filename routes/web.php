<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\JadwalBimbinganController;

Route::resource('jadwal', JadwalBimbinganController::class);

use App\Http\Controllers\RatingDanReviewController;
use App\Http\Controllers\DataDosenPembimbingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Daftar semua route aplikasi
|
*/

// 🔑 Public Pages
Route::view('/', 'login');
Route::view('/registrasi', 'registrasi');
Route::view('/home', 'home');
Route::view('/about', 'about');
Route::view('/menu', 'menu');
Route::view('/profile', 'profile');

// ⭐ Halaman Ranking Perusahaan
Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])
    ->name('ratingperusahaan');

// ✅ CRUD Rating & Review
Route::resource('ratingdanreview', RatingDanReviewController::class)->names([
    'index' => 'lihatratingdanreview',     // alias index
    'create' => 'tambahratingdanreview',   // alias create
]);
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PenilaianPengujiController;
use App\Http\Controllers\TranscriptController;

// return control('transkrip');
Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript.index');
Route::post('/transcript/analyze', [TranscriptController::class, 'analyze'])->name('transcript.analyze');
Route::get('/transkrip', [TranscriptController::class, 'index']);
Route::post('/transkrip/save', [TranscriptController::class, 'save']);
Route::get('/transkrip_result', [TranscriptController::class, 'results'])->name('transkrip_result');

// return control('perusahaan');
Route::resource('/perusahaan', PerusahaanController::class);

// Halaman utama -> login
Route::get('/', function () {
    return view('login');
});


// Halaman registrasi
Route::get('/registrasi', function () {
    return view('registrasi');
});

// Halaman home
Route::get('/home', function () {
    return view('home');
});


// Halaman about
Route::get('/about', function () {
    return view('about');
});

// Halaman menu
Route::get('/menu', function () {
    return view('menu');
});

// Halaman profile
Route::get('/profile', function () {
    return view('profile.profile');
    // folder.profile
    return view('profile');
});


Route::resource('perusahaan', PerusahaanController::class);


Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);


// Halaman daftar jadwal
Route::get('/daftar-jadwal', function () {
    return view('daftar-jadwal');
});

// Resource untuk Jadwal (CRUD otomatis)
Route::resource('jadwal', JadwalController::class);

Route::get('/transkrip', function () {
    // return view('menu');
    return view('transkrip.transkrip');
});

// Resource untuk Mahasiswa (CRUD otomatis)
Route::resource('mahasiswa', MahasiswaController::class);

Route::resource('penilaian', PenilaianPengujiController::class);
