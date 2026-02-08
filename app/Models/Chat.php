<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'message',
        'sender_type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the chat message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Optional order that the message is about.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
