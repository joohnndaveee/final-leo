<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';
    public $timestamps = false;

    protected $fillable = [
        'seller_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_id',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the seller for this transaction
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get type badge color
     */
    public function getTypeBadgeColor(): string
    {
        return match($this->type) {
            'deposit' => 'success',
            'withdrawal' => 'warning',
            'rent_payment' => 'danger',
            'refund' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get type label
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'rent_payment' => 'Rent Payment',
            'refund' => 'Refund',
            default => 'Transaction',
        };
    }
}
