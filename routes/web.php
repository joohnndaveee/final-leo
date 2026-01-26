<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\HomeController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// User Authentication routes
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

// User routes (placeholders for now)
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/shop', function () { return view('shop'); })->name('shop');
Route::get('/orders', function () { return view('orders'); })->name('orders');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/search', function () { return view('search'); })->name('search');
Route::get('/wishlist', function () { return view('wishlist'); })->name('wishlist');
Route::get('/cart', function () { return view('cart'); })->name('cart');
Route::get('/profile/update', function () { return view('profile.update'); })->name('profile.update');

// Admin routes
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin Product Management routes
Route::prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/products/{id}/delete', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});
