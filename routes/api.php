<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\StoreController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\UniversityController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('universities', UniversityController::class);
    Route::apiResource('stores', StoreController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);}
);
