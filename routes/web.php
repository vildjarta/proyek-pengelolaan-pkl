<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingDanReviewController;

// ----------------------
// Rute Halaman Publik
// ----------------------
Route::get('/', function () {
    return view('login');
});

Route::get('/registrasi', function () {
    return view('registrasi');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/menu', function () {
    return view('menu');
});

Route::get('/profile', function () {
    return view('profile');
});

// ----------------------
// Grup Rute Rating & Review
// ----------------------
Route::controller(RatingDanReviewController::class)->group(function () {
    // Halaman ranking perusahaan
    Route::get('/ratingperusahaan', 'showRanking')->name('ratingperusahaan');

    // Daftar rating dan review
    Route::get('/lihatratingdanreview', 'index')->name('lihatratingdanreview');

    // Form tambah rating & review
    Route::get('/ratingdanreview', 'create')->name('ratingdanreview.create');

    // Simpan rating & review
    Route::post('/ratingdanreview', 'store')->name('ratingdanreview.store');

    // Edit rating & review
    Route::get('/ratingdanreview/{id}/edit', 'edit')->name('ratingdanreview.edit');

    // Update rating & review
    Route::put('/ratingdanreview/{id}', 'update')->name('ratingdanreview.update');

    // Hapus rating & review
    Route::delete('/ratingdanreview/{id}', 'destroy')->name('ratingdanreview.destroy');
});
