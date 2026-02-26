<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'name', 'number', 'email', 'method',
        'address', 'total_products', 'total_price', 'placed_on',
        'payment_status', 'status', 'payment_reference',
        'shipping_method', 'shipping_fee', 'tracking_number',
        'shipped_at', 'delivered_at', 'cancelled_at',
        'voucher_id', 'voucher_discount',
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

    /**
     * Delivery tracking events.
     */
    public function tracking()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('created_at');
    }

    /**
     * Voucher applied to this order.
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * User notifications for this order.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_id')
                    ->where('related_type', 'order');
    }
}
