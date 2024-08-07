<?php

use App\Http\Controllers\Mobile\Auth\LoginController;
use App\Http\Controllers\Mobile\Auth\UserController;
use App\Http\Controllers\Mobile\Barang\BarangController;
use App\Http\Controllers\Mobile\Customer\CustomerController;
use App\Http\Controllers\Mobile\Dashboard\DashboardController;
use App\Http\Controllers\Mobile\Sales\SalesController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [UserController::class, 'getUserProfile']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('analytics', [DashboardController::class, 'getAnalytics']);
    });

    Route::apiResource('barang', BarangController::class);
    Route::apiResource('customer', CustomerController::class);

    Route::prefix('sales')->group(function () {
        Route::get('code', [SalesController::class, 'getSalesCode']);
    });

    Route::apiResource('sales', SalesController::class);
});
