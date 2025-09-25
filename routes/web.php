<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\TranscriptController;

Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript.index');
Route::post('/transcript/analyze', [TranscriptController::class, 'analyze'])->name('transcript.analyze');
Route::get('/transkrip', [TranscriptController::class, 'index']);
Route::post('/transkrip/save', [TranscriptController::class, 'save']);
Route::get('/transkrip_result', [TranscriptController::class, 'results'])->name('transkrip_result');

Route::get('/', function () {
    return view('login');
});

Route::get('/registrasi', function () {
    // return view('registrasi');
    return view('registrasi');
});

Route::get('/home', function () {
    // return view('home');
    return view('home');
});

Route::get('/about', function () {
    // return view('about');
    return view('about');
});

Route::get('/menu', function () {
    // return view('menu');
    return view('menu');
});

Route::get('/profile', function () {
    // return view('menu');
    return view('profile');
});

Route::resource('perusahaan', PerusahaanController::class);

Route::get('/transkrip', function () {
    // return view('menu');
    return view('transkrip');
});
