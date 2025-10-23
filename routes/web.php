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

// Halaman registrasi akun baru
Route::view('/registrasi', 'registrasi')->name('registrasi');

// Halaman utama (setelah login)
Route::view('/home', 'home')->name('home');

// Halaman tentang aplikasi
Route::view('/about', 'about')->name('about');

// Halaman menu utama pengguna
Route::view('/menu', 'menu')->name('menu');

// Halaman profil pengguna
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

// RATING & REVIEW PERUSAHAAN
// Menampilkan halaman ranking semua perusahaan berdasarkan rating
Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])
    ->name('ratingperusahaan');

// Menampilkan detail rating dan review untuk 1 perusahaan tertentu
Route::get('/ratingperusahaan/{id_perusahaan}', [RatingDanReviewController::class, 'index'])
    ->name('lihatratingdanreview');

// Menampilkan form tambah review baru untuk perusahaan tertentu
Route::get('/ratingperusahaan/tambah/{id_perusahaan}', [RatingDanReviewController::class, 'create'])
    ->name('tambahratingdanreview');

// Menyimpan data review baru ke database
Route::post('/ratingperusahaan/store', [RatingDanReviewController::class, 'store'])
    ->name('ratingdanreview.store');

// Menampilkan form edit review berdasarkan id review
Route::get('/ratingperusahaan/edit/{id_review}', [RatingDanReviewController::class, 'edit'])
    ->name('ratingdanreview.edit');

// Memperbarui data review yang telah diedit
Route::put('/ratingperusahaan/update/{id_review}', [RatingDanReviewController::class, 'update'])
    ->name('ratingdanreview.update');

// Menghapus review dari database
Route::delete('/ratingperusahaan/delete/{id_review}', [RatingDanReviewController::class, 'destroy'])
    ->name('ratingdanreview.destroy');


// DATA DOSEN PEMBIMBING (CRUD)
// 🎓 DATA DOSEN PEMBIMBING (CRUD)
//   DATA DOSEN PEMBIMBING (CRUD)
Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);


// 🗓️ JADWAL BIMBINGAN (CRUD) - Ini adalah route yang benar
Route::resource('jadwal', JadwalBimbinganController::class);


Route::resource('perusahaan', PerusahaanController::class);

Route::resource('penilaian', PenilaianDospemController::class);

// 🧑‍🎓 DATA MAHASISWA (CRUD)
Route::resource('mahasiswa', MahasiswaController::class);


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


// 🔍 AJAX: Cek NIM mahasiswa untuk form dosen pembimbing
Route::get('/cek-nim/{nim}', [App\Http\Controllers\MahasiswaController::class, 'cekNIM']);



// 📜 TRANSKRIP
Route::resource('transkrip', TranscriptController::class);
Route::get('/transkrip-analyze', [TranscriptController::class, 'analyzeTranscript'])->name('transkrip.analyze.page');
Route::post('/transkrip/analyze', [TranscriptController::class, 'analyze'])->name('transkrip.analyze');
Route::post('/transkrip/save-multiple', [TranscriptController::class, 'saveMultiple'])->name('transkrip.save.multiple');
