<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingDanReviewController;

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
