<?php

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

Route::get('/', [ProductController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:Penjual'])->group(function () {
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'adminIndex'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/store', [TransactionController::class, 'store'])->name('store');
        Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    });
});

Route::middleware(['auth', 'role:Pembeli'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}/delete', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/orders', [TransactionController::class, 'indexOrders'])->name('orders');
    Route::get('/orders/{id}', [TransactionController::class, 'detailOrders'])->name('orders.detail');

    Route::get('/checkout/cart', [TransactionController::class, 'checkoutCart'])->name('checkout.cart');
    Route::post('/checkout/cart/store', [TransactionController::class, 'checkoutCartStore'])->name('checkout.cart.store');

    Route::get('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/{id}', [TransactionController::class, 'productCheckout'])->name('checkout.product');
    Route::post('/checkout/{id}', [TransactionController::class, 'productStore'])->name('checkout.product.store');

    Route::get('/invoice/{id}', [TransactionController::class, 'productInvoice'])->name('checkout.product.invoice');
    Route::get('/checkout/{id}/pay', [TransactionController::class, 'payNow'])->name('checkout.product.pay');

    Route::prefix('user/transactions')->name('user.transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'userIndex'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'userShow'])->name('show');
    });

});

Route::middleware(['auth', 'role:Penjual'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [TransactionController::class, 'adminIndex'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'adminShow'])->name('show');
    });

    Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('admin.transactions.index');

    Route::get('/transactions/{id}', [TransactionController::class, 'adminShow'])->name('admin.transactions.show');

    Route::post('/transactions/{id}/confirm-payment', [TransactionController::class, 'adminConfirmPayment'])->name('admin.transactions.confirm');

    Route::post('/transactions/{id}/cancel', [TransactionController::class, 'adminCancelTransaction'])->name('admin.transactions.cancel');

    Route::post('/transactions/{id}/ship', [TransactionController::class, 'adminShipOrder'])->name('admin.transactions.ship');

    Route::post('/transactions/{id}/finish', [TransactionController::class, 'adminFinishOrder'])->name('admin.transactions.finish');
    Route::post('/admin/transaction/{id}/shipment/save', [TransactionController::class, 'adminShipmentSave'])->name('admin.shipments.save');
});

Route::middleware(['auth', 'role:Penjual'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'adminIndex'])->name('adminIndex');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/show', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [ProductController::class, 'delete'])->name('delete');
        Route::get('/trash', [ProductController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::get('/admin/users', [AuthController::class, 'usersPage'])->name('admin.users');

    // routes/web.php
    Route::prefix('admin/accounting')->name('accounting.')->group(function () {
        Route::get('/', [AccountingController::class, 'index'])->name('index');
        Route::get('/print', [AccountingController::class, 'print'])->name('print');
    });

});
