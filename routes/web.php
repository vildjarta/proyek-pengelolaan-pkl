<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingDanReviewController;
use App\Http\Controllers\DataDosenPembimbingController;
use App\Http\Controllers\MahasiswaController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// 🔑 ============================
//        HALAMAN UMUM
// ============================
Route::view('/', 'login')->name('login');
Route::view('/registrasi', 'registrasi')->name('registrasi');
Route::view('/home', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/menu', 'menu')->name('menu');
Route::view('/profile', 'profile.profile')->name('profile');


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


// 🎓 ============================
//   DATA DOSEN PEMBIMBING (CRUD)
// ============================
Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);

// 🔍 AJAX untuk cari mahasiswa berdasarkan NIM
Route::get('/cek-nim/{nim}', [MahasiswaController::class, 'cekNim']);
