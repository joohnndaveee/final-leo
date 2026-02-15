<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerPayment extends Model
{
    protected $table = 'seller_payments';
    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'subscription_id',
        'amount',
        'payment_method',
        'payment_status',
        'reference_number',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Get the seller for this payment
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get the subscription for this payment
     */
    public function subscription()
    {
        return $this->belongsTo(SellerSubscription::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}
