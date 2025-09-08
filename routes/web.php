<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    // return view('login');
    return view('home');
});

Route::get('/', function () {
    // return view('login');
    return view('index');
});

Route::get('/home', function () {
    // return view('home');
    return view('home');
});

Route::get('/about', function () {
    // return view('home');
    return view('about');
});

Route::get('/menu', function () {
    // return view('home');
    return view('menu');
});