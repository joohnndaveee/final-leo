<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to cart',
                'redirect' => route('login')
            ], 401);
        }

        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $userId = Auth::id();

        // Get product details
        $product = Product::findOrFail($productId);
        // Cart shows seller-level pricing only (regular/sale_price). Seasonal (admin) discounts are shown at checkout.
        $effectivePrice = $product->price;
        if ($product->sale_price !== null && (float) $product->sale_price > 0 && (float) $product->sale_price < (float) $product->price) {
            $effectivePrice = $product->sale_price;
        }

        // Check if product is in stock
        if ($product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this product is out of stock!'
            ], 400);
        }

        // Check if item already exists in user's cart
        $cartItem = Cart::where('user_id', $userId)
                       ->where('pid', $productId)
                       ->first();

        if ($cartItem) {
            // Item exists, check if requested quantity exceeds stock
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->stock} items available in stock!"
                ], 400);
            }
            
            // Increment quantity
            $cartItem->quantity = $newQuantity;
            $cartItem->price = $effectivePrice;
            $cartItem->image = $product->image_01;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated! Quantity increased.',
                'action' => 'updated',
                'cart_count' => Cart::where('user_id', $userId)->sum('quantity')
            ]);
        } else {
            // Check if requested quantity exceeds stock
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->stock} items available in stock!"
                ], 400);
            }
            
            // Item doesn't exist, create new cart entry
            Cart::create([
                'user_id' => $userId,
                'pid' => $productId,
                'name' => $product->name,
                'price' => $effectivePrice,
                'quantity' => $quantity,
                'image' => $product->image_01
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Added to cart successfully!',
                'action' => 'added',
                'cart_count' => Cart::where('user_id', $userId)->sum('quantity')
            ]);
        }
    }

    /**
     * Get cart count for authenticated user
     */
    public function getCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        
        return response()->json(['count' => $count]);
    }

    /**
     * Display cart page
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                           ->with('info', 'Please login to view your cart');
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $grandTotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'grandTotal'));
    }

    /**
     * Cart drawer content (right-side modal)
     */
    public function drawer()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to view your cart',
                'redirect' => route('login'),
            ], 401);
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->latest('id')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'cart_count' => $cartItems->sum('quantity'),
            'subtotal' => $subtotal,
            'html' => view('partials.cart-drawer-items', compact('cartItems', 'subtotal'))->render(),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $cartItem = Cart::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();

        // Get product to check stock
        $product = Product::findOrFail($cartItem->pid);

        // Check if requested quantity exceeds stock
        if ($request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->stock} items available in stock!"
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $effectivePrice = $product->price;
        if ($product->sale_price !== null && (float) $product->sale_price > 0 && (float) $product->sale_price < (float) $product->price) {
            $effectivePrice = $product->sale_price;
        }
        $cartItem->price = $effectivePrice;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => Cart::where('user_id', Auth::id())->sum('quantity')
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $cartItem = Cart::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => Cart::where('user_id', Auth::id())->sum('quantity')
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart_count' => 0
        ]);
    }
}
