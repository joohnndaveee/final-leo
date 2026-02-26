<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    protected $table = 'order_tracking';
    public $timestamps = false;

    const UPDATED_AT = null;

    protected $fillable = [
        'order_id', 'status', 'title', 'description', 'location', 'created_by',
    ];

    protected $casts = ['created_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Log a tracking event for an order.
     */
    public static function log(int $orderId, string $status, string $title, ?string $description = null, ?string $location = null, ?int $createdBy = null): self
    {
        return static::create([
            'order_id'    => $orderId,
            'status'      => $status,
            'title'       => $title,
            'description' => $description,
            'location'    => $location,
            'created_by'  => $createdBy,
        ]);
    }

    public static function iconFor(string $status): string
    {
        return match($status) {
            'order_placed'       => 'fa-shopping-cart',
            'confirmed'         => 'fa-check-circle',
            'packed'            => 'fa-box',
            'shipped'           => 'fa-truck',
            'out_for_delivery'  => 'fa-map-marker-alt',
            'delivered'         => 'fa-home',
            'cancelled'         => 'fa-times-circle',
            default             => 'fa-circle',
        };
    }
}
