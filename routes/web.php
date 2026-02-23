<?php

use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Purchase\PurchaseController;
use App\Http\Controllers\Admin\Sale\SaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        return view('admin.layouts.master');
    })->name('dashboard');


    Route::resource('products', ProductController::class);

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases-create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases-store', [PurchaseController::class, 'store'])->name('purchases.store');

     Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
     Route::get('/sales-create', [SaleController::class, 'create'])->name('sales.create');
     Route::post('/sales-store', [SaleController::class, 'store'])->name('sales.store');



    Route::get('/customer-search', [SaleController::class, 'search'])->name('customer.search');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
