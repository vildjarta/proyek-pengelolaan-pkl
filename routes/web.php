<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\PerusahaanController;
=======
use App\Http\Controllers\JadwalBimbinganController;

Route::resource('jadwal', JadwalBimbinganController::class);
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)

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
<<<<<<< HEAD

Route::resource('perusahaan', PerusahaanController::class);
=======
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
