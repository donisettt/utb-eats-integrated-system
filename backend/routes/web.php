<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\WebAuthController;

/**
 * Routes untuk guest (pengguna yang belum login)
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.submit');
});

/**
 * Routes yang memerlukan autentikasi
 */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    /**
     * Routes untuk admin panel
     * Semua route di dalam group ini memiliki prefix 'admin' dan nama route diawali 'admin.'
     */
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // Routes untuk manajemen mitra/merchant
            Route::get('/merchants', [AdminController::class, 'merchants'])->name('merchants');
            Route::get('/merchants/create', [AdminController::class, 'createMerchant'])->name('merchants.create');
            Route::post('/merchants', [AdminController::class, 'storeMerchant'])->name('merchants.store');
            Route::get('/merchants/{id}/edit', [AdminController::class, 'editMerchant'])->name('merchants.edit');
            Route::put('/merchants/{id}', [AdminController::class, 'updateMerchant'])->name('merchants.update');
            Route::delete('/merchants/{id}', [AdminController::class, 'destroyMerchant'])->name('merchants.destroy');

            // Routes untuk manajemen mahasiswa/dosen/staff
            Route::get('/students', [AdminController::class, 'students'])->name('students');
            Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
            Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
            Route::get('/students/{id}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
            Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('students.update');
            Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('students.destroy');

            // Routes untuk manajemen iuran mitra
            Route::get('/fees', [FeeController::class, 'index'])->name('fees');
            Route::get('/fees/create', [FeeController::class, 'create'])->name('fees.create');
            Route::post('/fees', [FeeController::class, 'store'])->name('fees.store');
            Route::post('/fees/{id}/pay', [FeeController::class, 'markAsPaid'])->name('fees.pay');
            Route::delete('/fees/{id}', [FeeController::class, 'destroy'])->name('fees.destroy');

            // Routes untuk manajemen banner
            Route::get('/banners', [BannerController::class, 'index'])->name('banners');
            Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
            Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
            Route::post('/banners/{id}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');
            Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');

            // Routes untuk laporan
            Route::get('/reports', [ReportController::class, 'index'])->name('reports');
            Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
            Route::get('/reports/fees', [ReportController::class, 'fees'])->name('reports.fees');
            Route::get('/reports/fees/print', [ReportController::class, 'printFees'])->name('reports.fees.print');

            // Routes untuk manajemen produk/menu
            Route::get('/products', [ProductController::class, 'index'])->name('products');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        });

    // Routes untuk profil user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
