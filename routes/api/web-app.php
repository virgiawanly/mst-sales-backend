<?php

use App\Http\Controllers\WebApp\Barang\BarangController;
use App\Http\Controllers\WebApp\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::apiResource('barang', BarangController::class);
Route::apiResource('customer', CustomerController::class);
