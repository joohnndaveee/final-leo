<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'name',
        'details',
        'price',
        'type',
        'image_01',
        'image_02',
        'image_03',
        'size',
        'color',
        'stock'
    ];

    // Ensure guarded is empty
    protected $guarded = [];

    /**
     * Get reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Seller that owns the product.
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    /**
     * Get average rating for this product
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function reviewsCount()
    {
        return $this->reviews()->count();
    }
}