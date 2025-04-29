<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\HomeController;
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
Route::apiResource('universities', UniversityController::class)->only(['index', 'show']);
Route::apiResource('stores', StoreController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::get('home', [HomeController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('universities', UniversityController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('stores', StoreController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('wanted-products', WantedProductController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

}
);
