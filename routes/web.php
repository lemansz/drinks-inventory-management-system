<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\RestockHistoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('guest')->group(function(){
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:login');
    Route::post('/forgot-password', [SettingController::class, 'forgotPassword'])->name('password.forgot')->middleware('throttle:password-reset');;
});



Route::middleware(['auth'])->group(function(){
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/download', [ReportController::class, 'downloadReport'])->name('reports.download');
    

    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::resource('products', ProductController::class);
    
    Route::get('/search-products', [SaleController::class, 'search'])->name('sales.search');
    Route::resource('sales', SaleController::class);

    Route::get('/sales/{sale}/receipt', [SaleController::class, 'generateReceipt'])->name('sales.receipt');


    Route::get('/restocks', [RestockController::class, 'index'])->name('restock.index');
    Route::get('/restocks/search', [RestockController::class, 'search'])->name('restock.search');
    Route::get('/restocks/{product}', [RestockController::class, 'create'])->name('restock.create');
    Route::post('/restocks/{product}', [RestockController::class, 'store'])->name('restock.store');

    Route::get('/settings', [SettingController::class, 'create'])->name('settings.create');
    Route::post('/settings/password', [SettingController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/settings/update-password', [SettingController::class, 'updatePassword'])->name('password.update');
    Route::post('/settings/email', [SettingController::class, 'updateEmail'])->name('email.update');
    Route::post('/settings/preference', [SettingController::class, 'inventoryPreference'])->name('settings.inventory.preference');

    Route::get('/restock-history', [RestockHistoryController::class, 'index'])->name('restock-history.index');
    Route::get('/restock-history/{log}', [RestockHistoryController::class, 'show'])->name('restock-history.show');
});