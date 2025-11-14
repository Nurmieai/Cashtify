<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| 🌍 LANDING PAGE (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('landing');


/*
|--------------------------------------------------------------------------
| 🔒 AUTH ROUTES (GUEST ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


/*
|--------------------------------------------------------------------------
| 👤 PROFILE + LOGOUT (SEMUA USER LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| 🛒 PEMBELI SAJA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Pembeli'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
});


/*
|--------------------------------------------------------------------------
| 🛍️ PENJUAL / ADMIN SAJA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Penjual'])->group(function () {

    // Dashboard penjual/admin
    Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');   // ← INI route dashboard YANG BENAR, BUKAN livewire.admin.dashboard

    // CRUD Produk
    Route::get('/products/manage', [ProductController::class, 'manage'])->name('products.manage');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}/delete', [ProductController::class, 'delete'])->name('products.delete');

    // Transaksi masuk
    Route::get('/orders', [TransactionController::class, 'adminIndex'])->name('orders');
});
