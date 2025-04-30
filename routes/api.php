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

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
// Route::post('/reset-password', [AuthController::class, 'resetPassword']);
// Route::apiResource('universities', UniversityController::class)->only(['index', 'show']);
// Route::apiResource('stores', StoreController::class)->only(['index', 'show']);
// Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
// Route::apiResource('products', ProductController::class)->only(['index', 'show']);
// Route::get('home', [HomeController::class, 'index']);
// Route::get('/home/products', [HomeController::class, 'index']);
// Route::get('/home/foods', [HomeController::class, 'foods']);


// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('universities', UniversityController::class)->only(['store', 'update', 'destroy']);
//     Route::apiResource('stores', StoreController::class)->only(['store', 'update', 'destroy']);
//     Route::apiResource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
//     Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
//     Route::apiResource('wanted-products', WantedProductController::class);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/user', [AuthController::class, 'user']);
//     Route::post('/change-password', [AuthController::class, 'changePassword']);

// }
// );


Route::prefix('v1')->group(function () {
    
    // Public Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    // Public Resource Routes
    Route::apiResources([
        'universities' => UniversityController::class,
        'stores' => StoreController::class,
        'categories' => CategoryController::class,
        'products' => ProductController::class,
    ], ['only' => ['index', 'show']]);

    // Public Home Routes
    Route::prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'index']);
        Route::get('/products', [HomeController::class, 'index']);
        Route::get('/foods', [HomeController::class, 'foods']);
    });

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::apiResources([
            'universities' => UniversityController::class,
            'stores' => StoreController::class,
            'categories' => CategoryController::class,
            'products' => ProductController::class,
        ], ['only' => ['store', 'update', 'destroy']]);

        Route::apiResource('wanted-products', WantedProductController::class);

        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });

    });

});
