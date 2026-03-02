<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store'])->name('login');
Route::post('/forgot-password', [SettingController::class, 'forgotPassword'])->name('password.forgot');

Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/create', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products/edit/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/record-sale/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/record-sale', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/search-products', [SaleController::class, 'searchProducts'])->name('sales.search-products');

    Route::get('/restock', [RestockController::class, 'index'])->name('restock.index');
    Route::get('/restock/search', [RestockController::class, 'search'])->name('restock.search');
    Route::get('/restock/{product}', [RestockController::class, 'create'])->name('restock.create');
    Route::post('/restock/{product}', [RestockController::class, 'store'])->name('restock.store');

    Route::get('/settings', [SettingController::class, 'create'])->name('settings.create');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('password.update');
    Route::post('/settings/email', [SettingController::class, 'updateEmail'])->name('email.update');

    Route::post('/settings/here', [SettingController::class, 'inventoryPreference'])->name('settings.inventory.preference');
});