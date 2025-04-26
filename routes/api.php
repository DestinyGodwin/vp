<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\StoreController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\UniversityController;
use App\Http\Controllers\v1\WantedProductController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('universities', UniversityController::class);
    Route::apiResource('stores', StoreController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('wanted-products', WantedProductController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

}
);
