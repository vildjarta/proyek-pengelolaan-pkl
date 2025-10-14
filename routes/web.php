<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingDanReviewController;
use App\Http\Controllers\DataDosenPembimbingController;
use App\Http\Controllers\MahasiswaController;


// Halaman login
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
Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);


// AJAX CEK NIM MAHASISWA
Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNim'])
    ->name('cekNim');
