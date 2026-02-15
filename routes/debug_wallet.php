<?php

// Temporary debug route - add this to routes/web.php
Route::get('/seller/debug-wallet', function() {
    $seller = auth('seller')->user();
    
    if (!$seller) {
        return 'Not logged in as seller';
    }
    
    $wallet = $seller->wallet;
    $subscription = $seller->sellerSubscriptions()->latest()->first();
    
    return [
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
    ];
})->middleware('auth:seller');
