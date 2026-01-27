<?php
/**
 * Quick test script to check if reviews are being stored and retrieved
 * Access via: http://localhost/shop_system/test_reviews.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h1>Review System Test</h1>";
echo "<style>body{font-family:Arial;padding:20px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#27ae60;color:white}</style>";

// Test 1: Check if reviews table exists and has data
echo "<h2>1. Reviews in Database</h2>";
try {
    $reviews = \App\Models\Review::with(['user', 'product', 'order'])->get();
    echo "<p><strong>Total Reviews:</strong> " . $reviews->count() . "</p>";
    
    if ($reviews->count() > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>User</th><th>Product</th><th>Order</th><th>Rating</th><th>Comment</th><th>Date</th></tr>";
        foreach ($reviews as $review) {
            echo "<tr>";
            echo "<td>" . $review->id . "</td>";
            echo "<td>" . ($review->user ? $review->user->name : 'N/A') . "</td>";
            echo "<td>" . ($review->product ? $review->product->name : 'N/A') . "</td>";
            echo "<td>#" . $review->order_id . "</td>";
            echo "<td>" . str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) . " (" . $review->rating . ")</td>";
            echo "<td>" . ($review->comment ?: '<em>No comment</em>') . "</td>";
            echo "<td>" . $review->created_at->format('M d, Y H:i') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange'>⚠️ No reviews found in database yet.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 2: Check product reviews
echo "<h2>2. Products with Reviews</h2>";
try {
    $products = \App\Models\Product::withCount('reviews')->having('reviews_count', '>', 0)->get();
    echo "<p><strong>Products with Reviews:</strong> " . $products->count() . "</p>";
    
    if ($products->count() > 0) {
        echo "<table>";
        echo "<tr><th>Product ID</th><th>Product Name</th><th>Review Count</th><th>Average Rating</th></tr>";
        foreach ($products as $product) {
            $avgRating = $product->reviews()->avg('rating') ?? 0;
            echo "<tr>";
            echo "<td>" . $product->id . "</td>";
            echo "<td>" . $product->name . "</td>";
            echo "<td>" . $product->reviews_count . "</td>";
            echo "<td>" . number_format($avgRating, 1) . " " . str_repeat('★', round($avgRating)) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange'>⚠️ No products have reviews yet.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 3: Check completed orders
echo "<h2>3. Completed Orders (Eligible for Reviews)</h2>";
try {
    $completedOrders = \App\Models\Order::with('user')
                                        ->whereIn('payment_status', ['completed', 'complete', 'delivered'])
                                        ->get();
    echo "<p><strong>Completed Orders:</strong> " . $completedOrders->count() . "</p>";
    
    if ($completedOrders->count() > 0) {
        echo "<table>";
        echo "<tr><th>Order ID</th><th>User</th><th>Status</th><th>Date</th></tr>";
        foreach ($completedOrders->take(10) as $order) {
            echo "<tr>";
            echo "<td>#" . $order->id . "</td>";
            echo "<td>" . ($order->user ? $order->user->name : 'N/A') . "</td>";
            echo "<td>" . $order->payment_status . "</td>";
            echo "<td>" . $order->placed_on . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        if ($completedOrders->count() > 10) {
            echo "<p><em>Showing first 10 of " . $completedOrders->count() . " completed orders</em></p>";
        }
    } else {
        echo "<p style='color:orange'>⚠️ No completed orders yet. Mark some orders as 'completed' in the admin panel.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Make sure you have completed orders (status: 'completed' or 'delivered')</li>";
echo "<li>Log in as a user who placed a completed order</li>";
echo "<li>Go to 'My Orders' → View order details</li>";
echo "<li>Click the 'Review' button next to any product</li>";
echo "<li>Submit a review with rating and optional comment</li>";
echo "<li>View the product detail page to see your review</li>";
echo "</ol>";
