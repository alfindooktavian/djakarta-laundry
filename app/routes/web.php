<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service; // <— tambahkan ini untuk mengakses model Service

// Halaman utama (menampilkan services dari database)
Route::get('/', function () {
    // Ambil layanan terbaru dari database
    $services = Service::orderBy('id', 'desc')->take(6)->get();
    return view('welcome', compact('services'));
});

// Halaman dashboard
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');

// Halaman login
Route::get('/login', function () {
    return view('login.login');
})->name('login');

// Halaman user
Route::get('/user', function () {
    return view('user.user');
})->name('user');

// Halaman service (admin kelola layanan)
Route::get('/service', function () {
    return view('service.service');
})->name('service');

// Halaman administrator
Route::get('/administrator', function () {
    return view('administrator.administrator');
})->name('administrator');

// Halaman customer
Route::get('/customer', function () {
    return view('customer.customer');
})->name('customer');

// Halaman chat
Route::get('/chat', function () {
    return view('chat.chat');
})->name('chat');

// Halaman report
Route::get('/report', function () {
    return view('report.report');
})->name('report');

// Halaman order
Route::get('/order', function () {
    return view('order.order');
})->name('order');