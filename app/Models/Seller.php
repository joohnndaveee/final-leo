<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{
    use HasFactory;

    protected $table = 'sellers';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_name',
        'gcash_number_used',
        'shop_description',
        'shop_logo',
        'status',
        'approved_at',
        'approved_notified',
        'subscription_status',
        'subscription_end_date',
        'monthly_rent',
        'last_payment_date',
        'payment_notification_sent',
        'suspension_reason',
        'suspension_notes',
        'suspended_by',
        'suspended_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'approved_notified' => 'boolean',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'subscription_end_date' => 'datetime',
        'last_payment_date' => 'datetime',
        'payment_notification_sent' => 'boolean',
        'suspended_at' => 'datetime',
    ];

    /**
     * Products owned by the seller.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    /**
     * Subscriptions for this seller
     */
    public function sellerSubscriptions()
    {
        return $this->hasMany(SellerSubscription::class);
    }

    /**
     * Payments for this seller
     */
    public function sellerPayments()
    {
        return $this->hasMany(SellerPayment::class);
    }

    /**
     * Wallet for this seller
     */
    public function wallet()
    {
        return $this->hasOne(SellerWallet::class);
    }

    /**
     * Wallet transactions for this seller
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Chat messages with admin
     */
    public function sellerChats()
    {
        return $this->hasMany(SellerChat::class);
    }

    /**
     * Check if seller is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if seller is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if seller is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if seller is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if subscription is disabled (suspended or expired)
     */
    public function isSubscriptionDisabled(): bool
    {
        return in_array($this->subscription_status, ['inactive', 'suspended', 'expired']);
    }

    /**
     * Check if subscription is expiring soon (within 7 days)
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        if (!$this->subscription_end_date) {
            return false;
        }

        $daysUntilExpiry = now()->diffInDays($this->subscription_end_date, false);
        return $daysUntilExpiry >= 0 && $daysUntilExpiry <= 7;
    }

    /**
     * Get the current active subscription
     */
    public function currentSubscription()
    {
        return $this->sellerSubscriptions()->latest()->first();
    }

    /**
     * Get wallet balance
     */
    public function getWalletBalance(): float
    {
        $wallet = $this->wallet;
        return $wallet ? (float) $wallet->balance : 0.0;
    }
}
