<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


Route::get('/', [ProductController::class, 'index'])
    ->name('landing');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.post');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


Route::middleware(['auth', 'role:Pembeli'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])
        ->name('transactions.show');
});


Route::middleware(['auth', 'role:Penjual'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [ProductController::class, 'delete'])->name('delete');
    });

    Route::get('/users', [AuthController::class, 'index'])
        ->name('users.index');

    Route::get('/orders', [TransactionController::class, 'adminIndex'])
        ->name('orders');
    Route::get('/orders/{id}', [TransactionController::class, 'adminShow'])
        ->name('orders.show');
});
