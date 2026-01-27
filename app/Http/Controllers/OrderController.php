<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Show the checkout page
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to proceed to checkout!');
        }

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('warning', 'Your cart is empty!');
        }

        $grandTotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('checkout', compact('cartItems', 'grandTotal'));
    }

    /**
     * Place a new order (with database transaction)
     */
    public function placeOrder(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Please login to place an order!'
            ], 401);
        }

        // Validate the form
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your cart is empty!'
            ], 400);
        }

        try {
            // Use Database Transaction to ensure all-or-nothing
            DB::beginTransaction();

            // Calculate total price
            $totalPrice = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

            // Prepare total_products string (e.g., "Product1 (2), Product2 (1)")
            $totalProducts = $cartItems->map(function($item) {
                return $item->name . ' (' . $item->quantity . ')';
            })->implode(', ');

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'name' => $request->name,
                'number' => $request->phone,
                'email' => Auth::user()->email,
                'method' => 'Cash on Delivery', // Default payment method
                'address' => $request->address,
                'total_products' => $totalProducts,
                'total_price' => $totalPrice,
                'placed_on' => date('Y-m-d'), // MySQL DATE format
                'payment_status' => 'pending'
            ]);

            // Move items from cart to order_items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->pid,
                    'name' => $cartItem->name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'image' => $cartItem->image
                ]);
            }

            // Empty the user's cart
            Cart::where('user_id', $userId)->delete();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            // Rollback if anything fails
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Order placement failed: ' . $e->getMessage(), [
                'user_id' => $userId,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to place order. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show all orders for the logged-in user
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to view your orders!');
        }

        $orders = Order::where('user_id', Auth::id())
                      ->orderBy('id', 'desc')
                      ->get();

        return view('orders', compact('orders'));
    }

    /**
     * Show specific order details
     */
    public function show($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with('orderItems.product')
                     ->where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->first();

        if (!$order) {
            return redirect()->route('orders')->with('error', 'Order not found!');
        }

        return view('order-details', compact('order'));
    }

    /**
     * Show the thank you page after successful order
     */
    public function thankYou($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with('orderItems')->where('id', $orderId)->where('user_id', Auth::id())->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found!');
        }

        return view('thank-you', compact('order'));
    }
}
