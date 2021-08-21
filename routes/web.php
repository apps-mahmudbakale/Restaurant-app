<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::get('orders/view/{invoice}', [OrderController::class,'show']);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::get('/cart/empty', [CartController::class, 'empty']);
    Route::post('/cart/search', [CartController::class, 'search']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::get('/cart/list', [CartController::class, 'getList']);
    Route::get('/cart/total', [CartController::class, 'getCartTotal']);
    Route::post('/cart/filter', [CartController::class, 'filterMeal']);
    Route::post('/cart/save', [CartController::class, 'saveCart']);
    Route::post('/cart/print', [CartController::class, 'saveCartPrint']);
    Route::post('/cart/plus', [CartController::class, 'plus']);
    Route::post('/cart/minus', [CartController::class, 'minus']);
    Route::get('/cart/invoice/{invoice}', [CartController::class, 'getInvoice']);
    Route::get('/cart/payment/{invoice}', [CartController::class, 'PayInvoice'])->name('invoice.pay');
    Route::get('/cart/payments', [CartController::class, 'getPayments'])->name('payments');
    Route::post('/cart/savePayments', [CartController::class, 'storePayment'])->name('store.payment');
    Route::get('/cart/Reports', [CartController::class, 'Reports'])->name('reports');
    Route::get('/cart/Ques', [CartController::class, 'Ques'])->name('order.ques');
});
