<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;

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
Route::get('/shop', [UserShopController::class, 'index'])->name('shop');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/search', function () { return view('search'); })->name('search');
Route::get('/wishlist', function () { return view('wishlist'); })->name('wishlist');

// Order routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/order/{id}/details', [OrderController::class, 'show'])->name('order.details');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// User Profile routes
Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

// Checkout & Order routes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order/thank-you/{orderId}', [OrderController::class, 'thankYou'])->name('order.thankyou');

// Chat routes (user side - requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.getMessages');
});

// Admin routes
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/orders', [AdminController::class, 'adminOrders'])->name('admin.orders');
Route::get('/admin/orders/{id}/details', [AdminController::class, 'showOrderDetails'])->name('admin.order.details');
Route::post('/admin/orders/{id}/update-status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.updateStatus');
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
Route::get('/admin/messages', [AdminController::class, 'messages'])->name('admin.messages');
Route::delete('/admin/messages/{id}', [AdminController::class, 'deleteMessage'])->name('admin.messages.delete');
Route::post('/admin/messages/{id}/mark-read', [AdminController::class, 'markMessageAsRead'])->name('admin.messages.markRead');
Route::post('/admin/messages/bulk-delete', [AdminController::class, 'bulkDeleteMessages'])->name('admin.messages.bulkDelete');
Route::get('/admin/messages/export', [AdminController::class, 'exportMessages'])->name('admin.messages.export');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin Product Management routes
Route::prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/products/{id}/delete', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

// Admin Chat routes
Route::prefix('admin')->group(function () {
    Route::get('/chats', [AdminChatController::class, 'index'])->name('admin.chats.index');
    Route::get('/chats/{userId}', [AdminChatController::class, 'show'])->name('admin.chats.show');
    Route::post('/chats/{userId}/reply', [AdminChatController::class, 'reply'])->name('admin.chats.reply');
    Route::get('/chats/{userId}/messages', [AdminChatController::class, 'getMessages'])->name('admin.chats.getMessages');
    Route::delete('/chats/{userId}', [AdminChatController::class, 'destroy'])->name('admin.chats.destroy');
});

// Review routes
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/product/{productId}', [App\Http\Controllers\ReviewController::class, 'getProductReviews'])->name('reviews.product');

// Product Detail route
Route::get('/product/{id}', [App\Http\Controllers\ProductDetailController::class, 'show'])->name('product.detail');
