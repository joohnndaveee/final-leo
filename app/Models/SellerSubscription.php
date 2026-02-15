<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    protected $table = 'seller_subscriptions';
    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'subscription_type',
        'amount',
        'start_date',
        'end_date',
        'status',
        'auto_renew',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'auto_renew' => 'boolean',
    ];

    /**
     * Get the seller for this subscription
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get payments for this subscription
     */
    public function payments()
    {
        return $this->hasMany(SellerPayment::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now()->toDateString();
    }

    /**
     * Check if subscription is expiring soon (within 7 days)
     */
    public function isExpiringSoon(): bool
    {
        $daysUntilExpiry = now()->diffInDays($this->end_date);
        return $daysUntilExpiry <= 7 && $daysUntilExpiry > 0;
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired(): bool
    {
        return $this->end_date < now()->toDateString();
    }
}
