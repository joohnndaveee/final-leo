<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerChatFile extends Model
{
    protected $table = 'seller_chat_files';
    public $timestamps = true;

    protected $fillable = [
        'seller_chat_id',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chat()
    {
        return $this->belongsTo(SellerChat::class, 'seller_chat_id');
    }
}

