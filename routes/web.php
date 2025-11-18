<?php

use App\Http\Controllers\AccountingController as ControllersAccountingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AccountingController;


// Landing page (Public)
Route::get('/', [ProductController::class, 'index'])
    ->name('landing');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| Authenticated (ALL USERS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| Buyer Routes — Pembeli
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| Seller Routes — Penjual (Admin Panel)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Penjual'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');

    // Produk
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [ProductController::class, 'delete'])->name('delete');
    });

    // User Management
    Route::get('/users', [AuthController::class, 'index'])
        ->name('users.index');

    // Order / Transaction Admin
    Route::get('/orders', [TransactionController::class, 'adminIndex'])
        ->name('orders');
    Route::get('/orders/{id}', [TransactionController::class, 'adminShow'])
        ->name('orders.show');


    /*
    |--------------------------------------------------------------------------
    | Posting (PostController)
    |--------------------------------------------------------------------------
    */
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PostController::class, 'delete'])->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | Lokasi (LocationController)
    |--------------------------------------------------------------------------
    */
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::post('/store', [LocationController::class, 'store'])->name('store');
        Route::put('/{id}/update', [LocationController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [LocationController::class, 'delete'])->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | Akuntansi (AccountingController)
    |--------------------------------------------------------------------------
    */
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/', [AccountingController::class, 'index'])->name('index');
    });

});
