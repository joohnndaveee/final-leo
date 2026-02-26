<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    protected $table = 'voucher_usages';
    public $timestamps = false;

    protected $fillable = [
        'voucher_id', 'user_id', 'order_id', 'discount_amount',
    ];

    public $created_at = true;

    const UPDATED_AT = null;

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'created_at'      => 'datetime',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
