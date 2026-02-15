<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'name',
        'number',
        'email',
        'method',
        'address',
        'total_products',
        'total_price',
        'placed_on',
        'payment_status',
        'status',
        'payment_reference',
        'shipping_method',
        'shipping_fee',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    /**
     * Get the user that owns this order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for this order
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
