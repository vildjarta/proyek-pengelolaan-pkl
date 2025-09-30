<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MahasiswaController;

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
    return view('profile');
});

// Halaman daftar jadwal
Route::get('/daftar-jadwal', function () {
    return view('daftar-jadwal');
});

// Resource untuk Jadwal (CRUD otomatis)
Route::resource('jadwal', JadwalController::class);

// Resource untuk Mahasiswa (CRUD otomatis)
Route::resource('mahasiswa', MahasiswaController::class);
