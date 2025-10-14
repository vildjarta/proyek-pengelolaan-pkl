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

Route::resource('jadwal', JadwalBimbinganController::class);

// Route dasar menggunakan Route::view untuk efisiensi
Route::view('/', 'login')->name('login');
Route::view('/registrasi', 'registrasi')->name('registrasi');
Route::view('/home', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/menu', 'menu')->name('menu');
Route::view('/profile', 'profile.profile')->name('profile');

// Halaman daftar jadwal (jika ini halaman statis)
Route::view('/daftar-jadwal', 'daftar-jadwal')->name('daftar-jadwal');

// ⭐ RATING & REVIEW PERUSAHAAN
Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])->name('ratingperusahaan');
// Menggunakan resource controller yang lebih rapi
Route::resource('ratingdanreview', RatingDanReviewController::class)->names([
    'index'   => 'lihatratingdanreview',
    'create'  => 'tambahratingdanreview',
    'store'   => 'ratingdanreview.store',
    'edit'    => 'ratingdanreview.edit',
    'update'  => 'ratingdanreview.update',
    'destroy' => 'ratingdanreview.destroy',
]);


// CRUD Transkrip (Kelayakan PKL)
Route::resource('transkrip', TranscriptController::class);
Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');

// CRUD Nilai Mahasiswa
Route::resource('nilai', NilaiController::class);

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
});

// Resource untuk perusahaan (CRUD otomatis)
Route::resource('/perusahaan', PerusahaanController::class);

// ⭐ ============================
//    RATING & REVIEW PERUSAHAAN
// ============================
Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])->name('ratingperusahaan');
Route::get('/ratingdanreview/perusahaan/{id_perusahaan}', [RatingDanReviewController::class, 'index'])->name('lihatratingdanreview');
Route::get('/ratingdanreview/tambah/{id_perusahaan}', [RatingDanReviewController::class, 'create'])->name('tambahratingdanreview');
Route::post('/ratingdanreview/store', [RatingDanReviewController::class, 'store'])->name('ratingdanreview.store');
Route::get('/ratingdanreview/edit/{id_review}', [RatingDanReviewController::class, 'edit'])->name('ratingdanreview.edit');
Route::put('/ratingdanreview/update/{id_review}', [RatingDanReviewController::class, 'update'])->name('ratingdanreview.update');
Route::delete('/ratingdanreview/delete/{id_review}', [RatingDanReviewController::class, 'destroy'])->name('ratingdanreview.destroy');


// 🎓 DATA DOSEN PEMBIMBING (CRUD)
//   DATA DOSEN PEMBIMBING (CRUD)
Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);


// 🗓️ JADWAL BIMBINGAN (CRUD) - Ini adalah route yang benar
Route::resource('jadwal', JadwalBimbinganController::class);


Route::resource('perusahaan', PerusahaanController::class);

Route::resource('penilaian', PenilaianDospemController::class);

// 🧑‍🎓 DATA MAHASISWA (CRUD)
Route::resource('mahasiswa', MahasiswaController::class);
// 🔍 AJAX untuk cari mahasiswa berdasarkan NIM
Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNim']);

Route::resource('penilaian', PenilaianPengujiController::class);


// 🏢 DATA PERUSAHAAN (CRUD)
Route::resource('perusahaan', PerusahaanController::class);


// 💯 PENILAIAN
Route::resource('penilaian', PenilaianDospemController::class);
Route::resource('penilaian-penguji', PenilaianPengujiController::class);
Route::resource('nilai', NilaiController::class); // CRUD Nilai Mahasiswa


// 👨‍🏫 DOSEN PENGUJI
Route::resource('dosen_penguji', DosenPengujiController::class);
// Resource untuk pengujian (CRUD otomatis)
Route::resource('/dosen_penguji', DosenPengujiController::class);

// route('get', '/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');
Route::get('/dosen_penguji/search', [DosenPengujiController::class, 'search'])->name('dosen_penguji.search');

// 🔍 AJAX untuk cari mahasiswa berdasarkan NIM
Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNim']);


// 📜 TRANSKRIP
Route::resource('transkrip', TranscriptController::class);
Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');
