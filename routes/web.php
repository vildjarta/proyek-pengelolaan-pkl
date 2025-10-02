<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingDanReviewController;
use App\Http\Controllers\DataDosenPembimbingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔑 Public Pages
Route::view('/', 'login');
Route::view('/registrasi', 'registrasi');
Route::view('/home', 'home');
Route::view('/about', 'about');
Route::view('/menu', 'menu');
Route::view('/profile', 'profile.profile');

// ⭐ Halaman Ranking Perusahaan
Route::get('/ratingperusahaan', [RatingDanReviewController::class, 'showRanking'])
    ->name('ratingperusahaan');

// ✅ Tambah Rating & Review (dengan id_perusahaan & nama_perusahaan)
Route::get('/ratingdanreview/tambah/{id_perusahaan}/{nama_perusahaan}', 
    [RatingDanReviewController::class, 'create']
)->name('tambahratingdanreview');

// ✅ CRUD Rating & Review
Route::resource('ratingdanreview', RatingDanReviewController::class)
    ->except(['create', 'show'])
    ->names([
        'index' => 'lihatratingdanreview',
    ]);

// ✅ CRUD Data Dosen Pembimbing
Route::resource('datadosenpembimbing', DataDosenPembimbingController::class);
