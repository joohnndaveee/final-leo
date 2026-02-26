<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = true;

    protected $fillable = [
        'seller_id', 'category_id', 'name', 'details', 'price',
        'sale_price', 'pieces',
        'type', 'image_01', 'image_02', 'image_03',
        'size', 'color', 'stock',
        'is_featured', 'is_active', 'discount_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
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
     * Category this product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Active discount applied to this product.
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Get the effective selling price (after discount if any).
     */
    public function effectivePrice(): float
    {
        $regularPrice = (float) $this->price;

        $salePrice = $this->sale_price;
        $hasValidSale = $salePrice !== null
            && (float) $salePrice > 0
            && (float) $salePrice < $regularPrice;

        $bestPrice = $hasValidSale ? (float) $salePrice : $regularPrice;

        $discount = $this->discount_id ? ($this->discount ?? $this->discount()->first()) : null;
        if ($discount && $discount->isActive()) {
            // Apply discount against the regular price (do not stack on top of sale_price).
            $bestPrice = min($bestPrice, $discount->computePrice($regularPrice));
        }

        return max(0, $bestPrice);
    }

    /**
     * Auto-disable if stock is 0.
     */
    public function autoDisableIfOutOfStock(): void
    {
        if ((int) $this->stock <= 0 && $this->is_active) {
            $this->update(['is_active' => false]);
        } elseif ((int) $this->stock > 0 && !$this->is_active) {
            $this->update(['is_active' => true]);
        }
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
