<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PublicProductController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MerchantOrderController;
use App\Http\Controllers\Api\MerchantProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MerchantReportController;

/**
 * Public API Routes
 * Routes ini dapat diakses tanpa autentikasi
 */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/**
 * Protected API Routes
 * Routes ini memerlukan token Sanctum untuk autentikasi
 */
Route::middleware('auth:sanctum')->group(function () {
    // Routes untuk autentikasi dan profil user
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    // Routes untuk data merchant dan produk
    Route::get('/merchants', [MerchantController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [PublicProductController::class, 'search']);
    Route::get('/categories', [CategoryController::class, 'index']);

    // Routes untuk banner
    Route::get('/banners', [BannerController::class, 'index']);

    // Routes untuk manajemen order/transaksi
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Routes khusus untuk merchant
    Route::get('/merchant/profile', [MerchantController::class, 'show']);
    Route::post('/merchant/update', [MerchantController::class, 'update']);
    Route::get('/merchant/orders', [MerchantOrderController::class, 'index']);
    Route::post('/merchant/orders/{id}/status', [MerchantOrderController::class, 'updateStatus']);
    Route::get('/merchant/report', [MerchantReportController::class, 'index']);

    // Routes untuk CRUD produk merchant
    Route::apiResource('merchant/products', MerchantProductController::class);
});
