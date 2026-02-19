<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SellerChat;
use App\Models\Product;
use App\Services\MockPaymentService;

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
            'phone' => 'required|string|max:20',
            'shipping_method' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:100',
        ]);

        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your cart is empty!'
            ], 400);
        }

        try {
            // Use Database Transaction to ensure all-or-nothing
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $shippingFee = 0;
            $grandTotal = $subtotal + $shippingFee;

            // Prepare total_products string (e.g., "Product1 (2), Product2 (1)")
            $totalProducts = $cartItems->map(function($item) {
                return $item->name . ' (' . $item->quantity . ')';
            })->implode(', ');

            $paymentResult = (new MockPaymentService())->charge(Auth::user(), $grandTotal, $request->input('payment_method', 'COD'));

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'name' => $request->name,
                'number' => $request->phone,
                'email' => Auth::user()->email,
                'method' => $request->input('payment_method', 'Cash on Delivery'),
                'address' => $request->address,
                'total_products' => $totalProducts,
                'total_price' => $grandTotal,
                'placed_on' => date('Y-m-d'), // MySQL DATE format
                'payment_status' => $paymentResult['status'],
                'status' => $paymentResult['status'] === 'paid' ? 'paid' : 'pending',
                'shipping_method' => $request->shipping_method ?? 'Standard',
                'shipping_fee' => $shippingFee,
                'payment_reference' => $paymentResult['reference'],
            ]);

            // Move items from cart to order_items
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                if (!$product || $product->stock < $cartItem->quantity) {
                    throw new \RuntimeException("Insufficient stock for {$cartItem->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->pid,
                    'name' => $cartItem->name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'image' => $cartItem->image
                ]);

                // Decrease stock atomically
                $beforeStock = (int) ($product->stock ?? 0);
                $product->decrement('stock', $cartItem->quantity);
                $product->refresh();
                $afterStock = (int) ($product->stock ?? 0);

                // Low stock notification to seller (only when crossing threshold)
                if ($product->seller_id && $beforeStock > 10 && $afterStock > 0 && $afterStock <= 10) {
                    $sellerName = $product->seller?->shop_name ?? 'Seller';
                    SellerChat::create([
                        'seller_id' => $product->seller_id,
                        'message' => "Low stock alert\n\nProduct: {$product->name}\nRemaining stock: {$afterStock}\n\nTip: Update stock in Seller Center → Products.",
                        'sender_type' => 'admin',
                        'is_read' => false,
                    ]);
                }
            }

            // Empty the user's cart
            Cart::where('user_id', $userId)->delete();

            // Commit the transaction
            DB::commit();

            // Notify user via email
            Mail::raw(
                "Your order #{$order->id} has been placed. Status: {$order->status}. Total: {$order->total_price}",
                function ($message) use ($order) {
                    $message->to($order->email)->subject('Order placed');
                }
            );

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

    /**
     * Customer marks an order as received/completed
     */
    public function markReceived($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with('orderItems.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $status = strtolower((string) ($order->status ?? ''));
        if (!in_array($status, ['delivered', 'completed', 'complete'], true)) {
            return redirect()->back()->with('error', 'Only delivered orders can be marked as received.');
        }

        if (in_array($status, ['completed', 'complete'], true)) {
            return redirect()->back()->with('info', 'This order is already marked as received.');
        }

        $order->status = 'completed';
        $order->payment_status = 'completed';
        $order->save();

        $sellerIds = $order->orderItems
            ->map(fn ($item) => $item->product?->seller_id)
            ->filter()
            ->unique()
            ->values();

        $customerName = Auth::user()->name ?? 'Customer';
        foreach ($sellerIds as $sellerId) {
            SellerChat::create([
                'seller_id' => (int) $sellerId,
                'message' => "Order completed\n\nOrder #{$order->id} was marked as received by {$customerName}.",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Thanks! Your order is marked as received.');
    }
}
