<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================================================================
// 🌍 PUBLIC ROUTES
// =====================================================================

// Landing (daftar produk)
Route::get('/', [ProductController::class, 'index'])->name('landing');


// =====================================================================
// 🔒 AUTH ROUTES (Guest Only)
// =====================================================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


// =====================================================================
// 👤 USER ROUTES (Auth Only)
// =====================================================================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
