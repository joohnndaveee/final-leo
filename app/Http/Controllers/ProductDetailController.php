<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ProductDetailController extends Controller
{
    /**
     * Display product details with reviews
     */
    public function show($id)
    {
        $product = Product::with(['seller', 'discount'])->findOrFail($id);
        
        // Get reviews with user information
        $reviews = Review::where('product_id', $id)
                         ->with('user')
                         ->orderBy('created_at', 'desc')
                         ->get();

        // Load order items for each review to get quantity
        foreach ($reviews as $review) {
            $review->orderItem = \App\Models\OrderItem::where('order_id', $review->order_id)
                                                      ->where('product_id', $review->product_id)
                                                      ->first();
        }

        // Calculate ratings
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();
        
        // Calculate star distribution
        $starDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $starDistribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 1)
            ];
        }

        return view('product-detail', compact('product', 'reviews', 'averageRating', 'totalReviews', 'starDistribution'));
    }
}
