<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSellerChat extends Model
{
    protected $table = 'user_seller_chats';

    protected $fillable = ['user_id', 'seller_id', 'message', 'sender_type', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
