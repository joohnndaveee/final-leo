<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'seller_id', 'type', 'title', 'message',
        'is_read', 'related_id', 'related_type',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Send an order notification to a user.
     */
    public static function notifyUser(int $userId, string $type, string $title, string $message, ?int $relatedId = null, ?string $relatedType = null): self
    {
        return static::create([
            'user_id'      => $userId,
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'is_read'      => false,
            'related_id'   => $relatedId,
            'related_type' => $relatedType,
        ]);
    }

    /**
     * Send a notification to a seller.
     */
    public static function notifySeller(int $sellerId, string $type, string $title, string $message, ?int $relatedId = null, ?string $relatedType = null): self
    {
        return static::create([
            'seller_id'    => $sellerId,
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'is_read'      => false,
            'related_id'   => $relatedId,
            'related_type' => $relatedType,
        ]);
    }
}
