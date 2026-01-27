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

        // Check if item already exists in user's cart
        $cartItem = Cart::where('user_id', $userId)
                       ->where('pid', $productId)
                       ->first();

        if ($cartItem) {
            // Item exists, increment quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated! Quantity increased.',
                'action' => 'updated',
                'cart_count' => Cart::where('user_id', $userId)->sum('quantity')
            ]);
        } else {
            // Item doesn't exist, create new cart entry
            Cart::create([
                'user_id' => $userId,
                'pid' => $productId,
                'name' => $product->name,
                'price' => $product->price,
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

        $cartItem->quantity = $request->quantity;
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
