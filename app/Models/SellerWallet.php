<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerWallet extends Model
{
    protected $table = 'seller_wallets';
    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'balance',
        'total_deposited',
        'total_withdrawn',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_deposited' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    /**
     * Get the seller for this wallet
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get transactions for this wallet
     */
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'seller_id', 'seller_id');
    }

    /**
     * Add funds to wallet
     */
    public function deposit(float $amount, string $description = null, ?int $referenceId = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->total_deposited += $amount;
        $this->save();

        return WalletTransaction::create([
            'seller_id' => $this->seller_id,
            'type' => 'deposit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Deduct funds from wallet for payment
     */
    public function payRent(float $amount, string $description = null, ?int $referenceId = null): bool
    {
        if ($this->balance < $amount) {
            return false;
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->total_withdrawn += $amount;
        $this->save();

        WalletTransaction::create([
            'seller_id' => $this->seller_id,
            'type' => 'rent_payment',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference_id' => $referenceId,
        ]);

        return true;
    }

    /**
     * Check if wallet has enough balance for rent
     */
    public function hasEnoughBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Withdraw funds from wallet
     */
    public function withdraw(float $amount, string $description = null): WalletTransaction
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->total_withdrawn += $amount;
        $this->save();

        return WalletTransaction::create([
            'seller_id' => $this->seller_id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
        ]);
    }
}
