<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    public $timestamps = true;

    protected $fillable = [
        'seller_id', 'name', 'description', 'type', 'value',
        'min_price', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'value'      => 'decimal:2',
        'min_price'  => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function isActive(): bool
    {
        $now = now();
        if (!$this->is_active) return false;
        if ($this->start_date && $now->lt($this->start_date)) return false;
        if ($this->end_date   && $now->gt($this->end_date))   return false;
        return true;
    }

    public function computePrice(float $originalPrice): float
    {
        if ($this->min_price && $originalPrice < (float) $this->min_price) {
            return max(0, $originalPrice);
        }

        if ($this->type === 'percentage') {
            return max(0, $originalPrice - ($originalPrice * ($this->value / 100)));
        }
        return max(0, $originalPrice - $this->value);
    }
}
