<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    public $timestamps = true;

    protected $fillable = [
        'seller_id', 'code', 'description', 'type', 'value',
        'min_order_amount', 'max_discount_amount',
        'usage_limit', 'used_count', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'start_date'         => 'datetime',
        'end_date'           => 'datetime',
        'value'              => 'decimal:2',
        'min_order_amount'   => 'decimal:2',
        'max_discount_amount'=> 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function isValid(): bool
    {
        $now = now();
        if (!$this->is_active) return false;
        if ($this->start_date && $now->lt($this->start_date)) return false;
        if ($this->end_date   && $now->gt($this->end_date))   return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function computeDiscount(float $orderTotal): float
    {
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) return 0;

        $discount = $this->type === 'percentage'
            ? $orderTotal * ($this->value / 100)
            : $this->value;

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return min($discount, $orderTotal);
    }
}
