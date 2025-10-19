<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\OrderDetailController;

// Public routes
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Protected routes (Sanctum token)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);

    
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::patch('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::patch('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::patch('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::patch('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // Order Details
    Route::get('/order-details', [OrderDetailController::class, 'index']);
    Route::get('/order-details/{id}', [OrderDetailController::class, 'show']);
    Route::post('/order-details', [OrderDetailController::class, 'store']);
    Route::patch('/order-details/{id}', [OrderDetailController::class, 'update']);
    Route::delete('/order-details/{id}', [OrderDetailController::class, 'destroy']);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::patch('/payments/{id}', [PaymentController::class, 'update']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index']); 
    Route::get('/reports/excel', [ReportController::class, 'exportExcel']); 
    Route::get('/reports/pdf', [ReportController::class, 'exportPDF']);    
});

// Whatsapp Service
Route::post('/send-wa', [WhatsAppController::class, 'send']);
Route::post('/wa-qr', [WhatsAppController::class, 'receiveQR']);
Route::get('/wa-qr', [WhatsAppController::class, 'getQR']);
Route::get('/wa-status', [WhatsAppController::class, 'status']);