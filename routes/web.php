<?php

use Illuminate\Support\Facades\Route;

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