<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Notification;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\SellerChat;
use App\Models\Product;
use App\Models\Discount;
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

        $subtotalRegular = 0.0;
        $subtotal = 0.0; // after seller item discounts (sale_price)
        foreach ($cartItems as $item) {
            $qty = (int) ($item->quantity ?? 0);
            if ($qty <= 0) continue;

            $product = $item->product;
            $regularUnit = $product ? (float) ($product->price ?? 0) : (float) $item->price;
            $saleUnit = $regularUnit;
            if ($product && $product->sale_price !== null && (float) $product->sale_price > 0 && (float) $product->sale_price < $regularUnit) {
                $saleUnit = (float) $product->sale_price;
            }

            $subtotalRegular += $regularUnit * $qty;
            $subtotal += $saleUnit * $qty;

            // Show per-item price as seller item price (sale_price) in the "Your Order" list.
            $item->price = $saleUnit;
        }

        $itemDiscount = max(0, $subtotalRegular - $subtotal);

        // Admin seasonal discount applies to ALL products at checkout.
        $seasonalPromotion = Discount::query()
            ->whereNull('seller_id')
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->orderByDesc('created_at')
            ->get()
            ->first(fn ($d) => $d->isActive());

        $seasonalDiscount = 0.0;
        if ($seasonalPromotion) {
            $afterSeasonal = (float) $seasonalPromotion->computePrice((float) $subtotal);
            $seasonalDiscount = max(0, (float) $subtotal - $afterSeasonal);
        }

        $grandTotal = max(0, $subtotal - $seasonalDiscount);

        // Available vouchers (from sellers in the cart)
        $sellerIds = $cartItems->pluck('product.seller_id')->filter()->unique();
        $availableVouchers = Voucher::whereIn('seller_id', $sellerIds)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', now()))
            ->get();

        return view('checkout', compact(
            'cartItems',
            'subtotalRegular',
            'subtotal',
            'itemDiscount',
            'seasonalPromotion',
            'seasonalDiscount',
            'grandTotal',
            'availableVouchers'
        ));
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
            'name'            => 'required|string|max:255',
            'address'         => 'required|string|max:500',
            'phone'           => 'required|string|max:20',
            'shipping_method' => 'nullable|string|max:100',
            'payment_method'  => 'nullable|string|max:100',
            'voucher_code'    => 'nullable|string|max:50',
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
            $subtotal = 0.0; // after seller item discounts (sale_price)
            foreach ($cartItems as $item) {
                $qty = (int) ($item->quantity ?? 0);
                if ($qty <= 0) continue;

                $product = $item->product;
                $regularUnit = $product ? (float) ($product->price ?? 0) : (float) $item->price;
                $saleUnit = $regularUnit;
                if ($product && $product->sale_price !== null && (float) $product->sale_price > 0 && (float) $product->sale_price < $regularUnit) {
                    $saleUnit = (float) $product->sale_price;
                }

                $subtotal += $saleUnit * $qty;
            }
            $shippingFee = 0;

            $seasonalPromotion = Discount::query()
                ->whereNull('seller_id')
                ->where('is_active', true)
                ->orderByDesc('start_date')
                ->orderByDesc('created_at')
                ->get()
                ->first(fn ($d) => $d->isActive());

            $seasonalDiscount = 0.0;
            if ($seasonalPromotion) {
                $afterSeasonal = (float) $seasonalPromotion->computePrice((float) $subtotal);
                $seasonalDiscount = max(0, (float) $subtotal - $afterSeasonal);
            }

            $totalAfterSeasonal = max(0, $subtotal + $shippingFee - $seasonalDiscount);

            // Apply voucher if provided
            $voucherDiscount = 0;
            $voucherId       = null;
            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', strtoupper($request->voucher_code))->first();
                if ($voucher && $voucher->isValid()) {
                    $voucherDiscount = $voucher->computeDiscount((float) $totalAfterSeasonal);
                    $voucherId       = $voucher->id;
                }
            }

            $grandTotal = max(0, $totalAfterSeasonal - $voucherDiscount);

            // Prepare total_products string (e.g., "Product1 (2), Product2 (1)")
            $totalProducts = $cartItems->map(function($item) {
                return $item->name . ' (' . $item->quantity . ')';
            })->implode(', ');

            $paymentResult = (new MockPaymentService())->charge(Auth::user(), $grandTotal, $request->input('payment_method', 'COD'));

            // Create the order
            $order = Order::create([
                'user_id'           => $userId,
                'name'              => $request->name,
                'number'            => $request->phone,
                'email'             => Auth::user()->email,
                'method'            => $request->input('payment_method', 'Cash on Delivery'),
                'address'           => $request->address,
                'total_products'    => $totalProducts,
                'total_price'       => $grandTotal,
                'placed_on'         => date('Y-m-d'),
                'payment_status'    => $paymentResult['status'],
                'status'            => $paymentResult['status'] === 'paid' ? 'paid' : 'pending',
                'shipping_method'   => $request->shipping_method ?? 'Standard',
                'shipping_fee'      => $shippingFee,
                'payment_reference' => $paymentResult['reference'],
                'voucher_id'        => $voucherId,
                'voucher_discount'  => $voucherDiscount,
            ]);

            // Log initial order tracking event
            OrderTracking::log($order->id, 'order_placed', 'Order Placed',
                'Your order has been received and is being processed.');

            // Move items from cart to order_items
            $seasonalPercent = null;
            $seasonalFixedCents = null;
            if ($seasonalPromotion && $seasonalDiscount > 0) {
                if ($seasonalPromotion->type === 'percentage') {
                    $seasonalPercent = max(0.0, min(100.0, (float) $seasonalPromotion->value));
                } else {
                    $seasonalFixedCents = (int) round($seasonalDiscount * 100);
                }
            }

            $remainingFixedCents = $seasonalFixedCents ?? 0;
            $subtotalCents = (int) round($subtotal * 100);
            $lastCartId = $cartItems->last()?->id;

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                if (!$product || $product->stock < $cartItem->quantity) {
                    throw new \RuntimeException("Insufficient stock for {$cartItem->name}");
                }

                $regularUnit = (float) ($product->price ?? 0);
                $saleUnit = $regularUnit;
                if ($product->sale_price !== null && (float) $product->sale_price > 0 && (float) $product->sale_price < $regularUnit) {
                    $saleUnit = (float) $product->sale_price;
                }

                $unitPrice = $saleUnit;
                $qty = (int) ($cartItem->quantity ?? 0);
                if ($qty > 0 && $seasonalPromotion && $seasonalDiscount > 0) {
                    if ($seasonalPercent !== null) {
                        $unitPrice = max(0, (float) $saleUnit - ((float) $saleUnit * ($seasonalPercent / 100)));
                    } elseif ($seasonalFixedCents !== null && $subtotalCents > 0) {
                        $lineCents = (int) round($saleUnit * $qty * 100);
                        if ($cartItem->id === $lastCartId) {
                            $allocCents = $remainingFixedCents;
                        } else {
                            $allocCents = (int) floor(($seasonalFixedCents * $lineCents) / $subtotalCents);
                            $allocCents = max(0, min($allocCents, $remainingFixedCents));
                        }
                        $remainingFixedCents -= $allocCents;

                        $perUnitDiscount = ($allocCents / max(1, $qty)) / 100;
                        $unitPrice = max(0, (float) $saleUnit - (float) $perUnitDiscount);
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->pid,
                    'name' => $cartItem->name,
                    'price' => $unitPrice,
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

            // Increment voucher usage count and record usage
            if ($voucherId) {
                Voucher::where('id', $voucherId)->increment('used_count');
                VoucherUsage::create([
                    'voucher_id'      => $voucherId,
                    'user_id'         => $userId,
                    'order_id'        => $order->id,
                    'discount_amount' => $voucherDiscount,
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Send in-app notification to customer
            Notification::notifyUser(
                $userId,
                'order_placed',
                'Order Placed Successfully',
                "Your order #{$order->id} has been placed. Total: ₱" . number_format($grandTotal, 2),
                $order->id,
                'order'
            );

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

        $order = Order::with(['orderItems.product', 'tracking'])
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

        // Log tracking event
        OrderTracking::log($order->id, 'delivered', 'Received by Customer',
            'You confirmed receipt of your order. Thank you for shopping!');

        // Notify the customer
        Notification::notifyUser(
            $order->user_id,
            'order_completed',
            'Order Completed',
            "Order #{$order->id} is marked as received. Thank you!",
            $order->id,
            'order'
        );

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
