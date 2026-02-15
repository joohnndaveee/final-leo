<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerChat extends Model
{
    protected $fillable = [
        'seller_id',
        'message',
        'sender_type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
