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
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SellerAnalyticsController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// User Authentication routes (buyers)
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

// Seller Authentication routes
Route::get('/seller/login', [UserAuthController::class, 'showSellerLoginForm'])->name('seller.login');
Route::post('/seller/login', [UserAuthController::class, 'sellerLogin'])->name('seller.login.post');
Route::get('/seller/register', [UserAuthController::class, 'showSellerRegisterForm'])->name('seller.register');
Route::post('/seller/register', [UserAuthController::class, 'sellerRegister'])->name('seller.register.post');

// User routes (placeholders for now)
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/shop', [UserShopController::class, 'index'])->name('shop');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/search', function () { return view('search'); })->name('search');
Route::get('/wishlist', function () { return view('wishlist'); })->name('wishlist');

Route::middleware(['auth:web'])->group(function () {
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/order/{id}/details', [OrderController::class, 'show'])->name('order.details');
    Route::post('/order/{id}/received', [OrderController::class, 'markReceived'])->name('order.received');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/cart/drawer', [CartController::class, 'drawer'])->name('cart.drawer');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // User Profile routes (edit & change password)
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Checkout & Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/order/thank-you/{orderId}', [OrderController::class, 'thankYou'])->name('order.thankyou');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Report seller or product
    Route::get('/report', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
});

// Chat routes (user side - requires authentication)
Route::middleware('auth:web')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.getMessages');
});

// Notification unread count (public AJAX - checks auth itself)
Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');

// Voucher validation at checkout (AJAX, no login required to validate)
Route::post('/voucher/validate', [VoucherController::class, 'validate'])->name('voucher.validate');

// Admin routes (public)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// Admin routes (protected)
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/sellers', [AdminController::class, 'sellers'])->name('admin.sellers');
    Route::get('/sellers/{id}', [AdminController::class, 'showSeller'])->name('admin.sellers.show');
    Route::get('/settings/branding', [AdminSettingsController::class, 'branding'])->name('admin.settings.branding');
    Route::post('/settings/logo', [AdminSettingsController::class, 'updateLogo'])->name('admin.settings.logo.update');
    Route::post('/settings/hero-bg', [AdminSettingsController::class, 'updateHeroBg'])->name('admin.settings.hero_bg.update');
    Route::post('/settings/seasonal-banner', [AdminSettingsController::class, 'updateSeasonalBanner'])->name('admin.settings.seasonal_banner.update');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::delete('/messages/{id}', [AdminController::class, 'deleteMessage'])->name('admin.messages.delete');
    Route::post('/messages/{id}/mark-read', [AdminController::class, 'markMessageAsRead'])->name('admin.messages.markRead');
    Route::post('/messages/bulk-delete', [AdminController::class, 'bulkDeleteMessages'])->name('admin.messages.bulkDelete');
    Route::get('/messages/export', [AdminController::class, 'exportMessages'])->name('admin.messages.export');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    
    // Admin Product Management routes
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/products/{id}/delete', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    
    // Admin Chat routes
    Route::get('/chats', [AdminChatController::class, 'index'])->name('admin.chats.index');
    Route::get('/chats/{userId}', [AdminChatController::class, 'show'])->name('admin.chats.show');
    Route::post('/chats/{userId}/reply', [AdminChatController::class, 'reply'])->name('admin.chats.reply');
    Route::get('/chats/{userId}/messages', [AdminChatController::class, 'getMessages'])->name('admin.chats.getMessages');
    Route::delete('/chats/{userId}', [AdminChatController::class, 'destroy'])->name('admin.chats.destroy');

    // Admin Seller Chat routes
    Route::get('/seller-chats', [\App\Http\Controllers\Admin\SellerChatController::class, 'index'])->name('admin.seller-chats.index');
    Route::get('/seller-chats/{sellerId}', [\App\Http\Controllers\Admin\SellerChatController::class, 'show'])->name('admin.seller-chats.show');
    Route::post('/seller-chats/{sellerId}/reply', [\App\Http\Controllers\Admin\SellerChatController::class, 'reply'])->name('admin.seller-chats.reply');
    Route::get('/seller-chats/{sellerId}/messages', [\App\Http\Controllers\Admin\SellerChatController::class, 'getMessages'])->name('admin.seller-chats.getMessages');
    Route::get('/seller-chats/{sellerId}/files/{file}/view', [\App\Http\Controllers\Admin\SellerChatController::class, 'viewFile'])->name('admin.seller-chats.files.view');
    Route::get('/seller-chats/{sellerId}/files/{file}/download', [\App\Http\Controllers\Admin\SellerChatController::class, 'downloadFile'])->name('admin.seller-chats.files.download');

    // Seller subscription management routes
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('admin.subscriptions');
    Route::post('/sellers/{sellerId}/subscription/notify', [\App\Http\Controllers\SellerSubscriptionController::class, 'toggleNotification'])->name('admin.seller.notify');
    Route::post('/sellers/{sellerId}/subscription/mark-paid', [\App\Http\Controllers\SellerSubscriptionController::class, 'markAsPaid'])->name('admin.seller.mark-paid');
    Route::post('/sellers/{sellerId}/subscription/disable', [\App\Http\Controllers\SellerSubscriptionController::class, 'disableSeller'])->name('admin.seller.disable');
    Route::post('/sellers/{sellerId}/subscription/unsuspend', [\App\Http\Controllers\SellerSubscriptionController::class, 'unsuspendSeller'])->name('admin.seller.unsuspend');

    // Admin: Manage Categories
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::post('/categories/{id}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('admin.categories.toggle');

    // Admin: Sales & System Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/export/sales', [\App\Http\Controllers\Admin\ReportsController::class, 'exportSales'])->name('admin.reports.export.sales');
    Route::get('/reports/export/payments', [\App\Http\Controllers\Admin\ReportsController::class, 'exportPayments'])->name('admin.reports.export.payments');

    // Admin: Monitor Reported Products / Sellers / Users
    Route::get('/reported', [\App\Http\Controllers\Admin\ReportedController::class, 'index'])->name('admin.reported.index');
    Route::get('/reported/{id}', [\App\Http\Controllers\Admin\ReportedController::class, 'show'])->name('admin.reported.show');
    Route::put('/reported/{id}', [\App\Http\Controllers\Admin\ReportedController::class, 'update'])->name('admin.reported.update');
    Route::delete('/reported/{id}', [\App\Http\Controllers\Admin\ReportedController::class, 'destroy'])->name('admin.reported.destroy');

    // Admin: Seasonal item discounts
    Route::get('/discounts', [AdminDiscountController::class, 'index'])->name('admin.discounts.index');
    Route::post('/discounts', [AdminDiscountController::class, 'store'])->name('admin.discounts.store');
    Route::put('/discounts/{id}', [AdminDiscountController::class, 'update'])->name('admin.discounts.update');
    Route::delete('/discounts/{id}', [AdminDiscountController::class, 'destroy'])->name('admin.discounts.destroy');
    Route::post('/discounts/{id}/toggle', [AdminDiscountController::class, 'toggle'])->name('admin.discounts.toggle');
    Route::post('/discounts/apply-to-product', [AdminDiscountController::class, 'applyToProduct'])->name('admin.discounts.applyToProduct');
});

// Review routes
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/product/{productId}', [App\Http\Controllers\ReviewController::class, 'getProductReviews'])->name('reviews.product');

// Product Detail route
Route::get('/product/{id}', [App\Http\Controllers\ProductDetailController::class, 'show'])->name('product.detail');

// Seller portal (approved sellers)
// Routes that require active subscription
Route::prefix('seller')->middleware(['auth:seller', 'check.seller.subscription'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::get('/products', [\App\Http\Controllers\SellerController::class, 'products'])->name('seller.products.index');
    Route::post('/products', [\App\Http\Controllers\SellerController::class, 'storeProduct'])->name('seller.products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\SellerController::class, 'editProduct'])->name('seller.products.edit');
    Route::post('/products/{id}', [\App\Http\Controllers\SellerController::class, 'updateProduct'])->name('seller.products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\SellerController::class, 'destroyProduct'])->name('seller.products.destroy');
    Route::post('/products/{id}/toggle-featured', [\App\Http\Controllers\SellerController::class, 'toggleFeatured'])->name('seller.products.toggleFeatured');
    Route::post('/products/{id}/toggle-active', [\App\Http\Controllers\SellerController::class, 'toggleActive'])->name('seller.products.toggleActive');

    Route::get('/orders', [\App\Http\Controllers\SellerController::class, 'orders'])->name('seller.orders.index');
    Route::post('/orders/{order}/ship', [\App\Http\Controllers\SellerController::class, 'markShipped'])->name('seller.orders.ship');
    Route::post('/orders/{order}/deliver', [\App\Http\Controllers\SellerController::class, 'markDelivered'])->name('seller.orders.deliver');

    // Sales Analytics
    Route::get('/analytics', [SellerAnalyticsController::class, 'index'])->name('seller.analytics.index');
    Route::get('/analytics/export', [SellerAnalyticsController::class, 'export'])->name('seller.analytics.export');

    // Discounts
    Route::get('/discounts', [DiscountController::class, 'index'])->name('seller.discounts.index');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('seller.discounts.store');
    Route::put('/discounts/{id}', [DiscountController::class, 'update'])->name('seller.discounts.update');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->name('seller.discounts.destroy');
    Route::post('/discounts/{id}/toggle', [DiscountController::class, 'toggle'])->name('seller.discounts.toggle');
    Route::post('/discounts/apply-to-product', [DiscountController::class, 'applyToProduct'])->name('seller.discounts.applyToProduct');

    // Vouchers
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('seller.vouchers.index');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('seller.vouchers.store');
    Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('seller.vouchers.update');
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->name('seller.vouchers.destroy');
    Route::post('/vouchers/{id}/toggle', [VoucherController::class, 'toggle'])->name('seller.vouchers.toggle');

    // Subscription routes
    Route::get('/subscription', [\App\Http\Controllers\SellerSubscriptionController::class, 'show'])->name('seller.subscription.show');
    Route::post('/subscription', [\App\Http\Controllers\SellerSubscriptionController::class, 'store'])->name('seller.subscription.store');
    Route::put('/subscription/{subscriptionId}', [\App\Http\Controllers\SellerSubscriptionController::class, 'update'])->name('seller.subscription.update');
});

// Routes accessible even with expired subscription (settings and wallet)
Route::prefix('seller')->middleware('auth:seller')->group(function () {
    // Wallet routes (allow payment even when expired)
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('seller.wallet.index');
    Route::get('/wallet/deposit', [\App\Http\Controllers\WalletController::class, 'showDepositForm'])->name('seller.wallet.deposit.form');
    Route::post('/wallet/deposit', [\App\Http\Controllers\WalletController::class, 'deposit'])->name('seller.wallet.deposit');
    Route::get('/wallet/pay-rent', [\App\Http\Controllers\WalletController::class, 'showPayRentForm'])->name('seller.wallet.pay-rent.form');
    Route::post('/wallet/pay-rent', [\App\Http\Controllers\WalletController::class, 'payRent'])->name('seller.wallet.pay-rent');
    Route::get('/wallet/payment-receipt/{payment}', [\App\Http\Controllers\WalletController::class, 'showPaymentReceipt'])->name('seller.wallet.payment-receipt');
    Route::get('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'showWithdrawalForm'])->name('seller.wallet.withdraw.form');
    Route::post('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'withdraw'])->name('seller.wallet.withdraw');

    // Settings route (allow access even when expired)
    Route::get('/settings', [\App\Http\Controllers\SellerController::class, 'settings'])->name('seller.settings');
    Route::put('/settings', [\App\Http\Controllers\SellerController::class, 'updateSettings'])->name('seller.settings.update');

    // Violations page (for suspended sellers to view suspension details)
    Route::get('/violations', [\App\Http\Controllers\SellerController::class, 'violations'])->name('seller.violations');
    Route::post('/support-message', [\App\Http\Controllers\SellerController::class, 'sendSupportMessage'])->name('seller.support.send');

    // Seller live chat with admin (accessible even when suspended)
    Route::get('/chat', [\App\Http\Controllers\SellerChatController::class, 'index'])->name('seller.chat');
    Route::post('/chat/send', [\App\Http\Controllers\SellerChatController::class, 'send'])->name('seller.chat.send');
    Route::get('/chat/messages', [\App\Http\Controllers\SellerChatController::class, 'getMessages'])->name('seller.chat.messages');
    Route::get('/chat/files/{file}/view', [\App\Http\Controllers\SellerChatController::class, 'viewFile'])->name('seller.chat.files.view');
    Route::get('/chat/files/{file}/download', [\App\Http\Controllers\SellerChatController::class, 'downloadFile'])->name('seller.chat.files.download');
    
    // Temporary debug route
    Route::get('/debug-wallet', function() {
        $seller = auth('seller')->user();
        
        if (!$seller) {
            return response()->json(['error' => 'Not logged in as seller']);
        }
        
        $wallet = $seller->wallet;
        $subscription = $seller->sellerSubscriptions()->latest()->first();
        
        return response()->json([
            'seller' => [
                'id' => $seller->id,
                'name' => $seller->name,
                'email' => $seller->email,
                'subscription_status' => $seller->subscription_status,
                'subscription_end_date' => $seller->subscription_end_date,
            ],
            'wallet' => $wallet ? [
                'id' => $wallet->id,
                'balance' => $wallet->balance,
                'total_deposited' => $wallet->total_deposited,
            ] : 'NO WALLET FOUND',
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'amount' => $subscription->amount,
                'status' => $subscription->status,
                'end_date' => $subscription->end_date,
            ] : 'NO SUBSCRIPTION FOUND',
            'can_pay' => $wallet && $subscription && $wallet->balance >= $subscription->amount,
        ]);
    });
});
