<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.dashboard'); 
});

Route::get('/login', function () {
    return view('login.login'); 
});

Route::get('/user', function () {
    return view('user.user'); 
});

Route::get('/customer', function () {
    return view('customer.customer'); 
});

