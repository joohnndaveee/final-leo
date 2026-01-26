<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'details',
        'price',
        'type',
        'image_01',
        'image_02',
        'image_03',
        'size',
        'color'
    ];

    // Ensure guarded is empty
    protected $guarded = [];
}