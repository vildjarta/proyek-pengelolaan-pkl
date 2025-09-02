<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return view('login');
});

Route::get('/data', function () {
    // return view('welcome');
    return view('data');
});
