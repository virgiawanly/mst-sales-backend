<?php

use App\Http\Controllers\WebApp\Auth\LoginController;
use App\Http\Controllers\WebApp\Auth\UserController;
use App\Http\Controllers\WebApp\Barang\BarangController;
use App\Http\Controllers\WebApp\Customer\CustomerController;
use App\Http\Controllers\WebApp\Sales\SalesController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [UserController::class, 'getUserProfile']);
    });

    Route::apiResource('barang', BarangController::class);
    Route::apiResource('customer', CustomerController::class);

    Route::prefix('sales')->group(function () {
        Route::get('code', [SalesController::class, 'getSalesCode']);
    });

    Route::apiResource('sales', SalesController::class);
});
