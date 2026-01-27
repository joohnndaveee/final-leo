<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to leave a review'
            ], 401);
        }

        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Verify the order belongs to the user and is completed
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->whereIn('payment_status', ['completed', 'complete', 'delivered'])
                      ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can only review products from your completed orders'
            ], 403);
        }

        // Verify the product was in this order
        $orderItem = \App\Models\OrderItem::where('order_id', $request->order_id)
                                          ->where('product_id', $request->product_id)
                                          ->first();

        if (!$orderItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'This product was not in your order'
            ], 403);
        }

        // Check if user already reviewed this product for this order
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('product_id', $request->product_id)
                                ->where('order_id', $request->order_id)
                                ->first();

        if ($existingReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already reviewed this product'
            ], 409);
        }

        // Create the review
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thank you for your feedback!',
            'review' => $review
        ]);
    }

    /**
     * Get reviews for a specific product
     */
    public function getProductReviews($productId)
    {
        $product = Product::findOrFail($productId);
        
        $reviews = Review::where('product_id', $productId)
                         ->with('user')
                         ->orderBy('created_at', 'desc')
                         ->get();

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews
        ]);
    }
}
