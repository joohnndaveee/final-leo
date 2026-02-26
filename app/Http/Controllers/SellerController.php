<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Models\SellerChat;

class SellerController extends Controller
{
    public function dashboard()
    {
        $seller = Auth::user();
        $sellerId = $seller->id;

        $productsCount = Product::where('seller_id', $sellerId)->count();
        $ordersCount = OrderItem::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->distinct('order_id')->count('order_id');

        $salesTotal = OrderItem::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->sum(DB::raw('price * quantity'));

        $recentOrders = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->orderByDesc('id')->limit(5)->get();

        return view('seller.dashboard', compact('seller', 'productsCount', 'ordersCount', 'salesTotal', 'recentOrders'));
    }

    public function products()
    {
        $seller = Auth::user();
        $products = Product::where('seller_id', $seller->id)->orderByDesc('id')->paginate(20);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('seller.products', compact('seller', 'products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'details' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'pieces' => 'required|integer|min:1',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            // max is in kilobytes
            'image_01' => 'required|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        // Handle image uploads
        $image01Name = null;
        if ($request->hasFile('image_01')) {
            $image01Name = time() . '_1_' . $request->file('image_01')->getClientOriginalName();
            $request->file('image_01')->move(public_path('uploaded_img'), $image01Name);
        }

        $category = Category::find($request->category_id);

        Product::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'details' => $request->details,
            'price' => $request->price,
            'sale_price' => ($request->filled('sale_price') && (float) $request->sale_price > 0) ? $request->sale_price : null,
            'category_id' => $request->category_id,
            'type' => $category ? $category->name : null,
            'stock' => $request->stock,
            'pieces' => $request->pieces,
            'image_01' => $image01Name,
            // DB schema may still require NOT NULL for image_02/image_03 in some environments
            'image_02' => '',
            'image_03' => '',
        ]);

        $stock = (int) ($request->stock ?? 0);
        if ($stock > 0 && $stock <= 10) {
            SellerChat::create([
                'seller_id' => Auth::id(),
                'message' => "Low stock alert\n\nProduct: {$request->name}\nRemaining stock: {$stock}",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Product created.');
    }

    /**
     * Toggle featured status of a product
     */
    public function toggleFeatured($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->update(['is_featured' => !$product->is_featured]);

        return response()->json([
            'success'     => true,
            'is_featured' => $product->is_featured,
        ]);
    }

    /**
     * Toggle active/inactive of a product
     */
    public function toggleActive($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        // Don't allow activating if out of stock
        if (!$product->is_active && (int) $product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Cannot activate a product with no stock.']);
        }

        $product->update(['is_active' => !$product->is_active]);

        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }

    public function editProduct($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('seller.edit-product', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $beforeStock = (int) ($product->stock ?? 0);

        $imageRequiredRule = !empty($product->image_01) ? 'nullable' : 'required';

        $request->validate([
            'name' => 'required|string|max:100',
            'details' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'pieces' => 'required|integer|min:1',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            // max is in kilobytes
            'image_01' => $imageRequiredRule . '|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->sale_price = ($request->filled('sale_price') && (float) $request->sale_price > 0) ? $request->sale_price : null;
        $product->category_id = $request->category_id;
        $cat = Category::find($request->category_id);
        $product->type = $cat ? $cat->name : $product->type;
        $product->stock = $request->stock;
        $product->pieces = $request->pieces;

        // Update images if new ones are uploaded
        if ($request->hasFile('image_01')) {
            $oldPath = public_path('uploaded_img/' . $product->image_01);
            if ($product->image_01 && File::exists($oldPath)) {
                File::delete($oldPath);
            }
            $image01Name = time() . '_1_' . $request->file('image_01')->getClientOriginalName();
            $request->file('image_01')->move(public_path('uploaded_img'), $image01Name);
            $product->image_01 = $image01Name;
        }

        $product->save();

        $afterStock = (int) ($product->stock ?? 0);
        if ($beforeStock > 10 && $afterStock > 0 && $afterStock <= 10) {
            SellerChat::create([
                'seller_id' => Auth::id(),
                'message' => "Low stock alert\n\nProduct: {$product->name}\nRemaining stock: {$afterStock}",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);
        }

        // Auto-disable if out of stock
        $product->autoDisableIfOutOfStock();

        return redirect()->route('seller.products.index')->with('success', 'Product updated.');
    }

    public function destroyProduct($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product removed.');
    }

    public function orders()
    {
        $seller = Auth::user();
        $sellerId = $seller->id;

        $orders = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
            ->with(['orderItems' => function ($query) use ($sellerId) {
                $query->whereHas('product', function ($inner) use ($sellerId) {
                    $inner->where('seller_id', $sellerId);
                })->with('product');
            }])
            ->orderByDesc('id')
            ->paginate(20);

        return view('seller.orders', compact('seller', 'orders'));
    }

    public function markShipped(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'shipping_method' => 'required|string|max:100',
        ]);

        $order->tracking_number = $request->tracking_number;
        $order->shipping_method = $request->shipping_method;
        $order->status = 'shipped';
        $order->payment_status = $order->payment_status === 'pending' ? 'pending' : 'completed';
        $order->shipped_at = now();
        $order->save();

        // Log tracking event
        OrderTracking::log($order->id, 'shipped', 'Order Shipped',
            "Your order has been shipped via {$order->shipping_method}. Tracking: {$order->tracking_number}");

        // Notify customer
        Notification::notifyUser(
            $order->user_id,
            'order_shipped',
            'Your Order Has Been Shipped!',
            "Order #{$order->id} is on its way. Tracking: {$order->tracking_number}",
            $order->id,
            'order'
        );

        if ($order->email) {
            Mail::raw(
                "Your order #{$order->id} has been shipped. Tracking: {$order->tracking_number}.",
                function ($message) use ($order) {
                    $message->to($order->email)->subject('Order shipped');
                }
            );
        }

        return redirect()->route('seller.orders.index')->with('success', 'Order marked as shipped.');
    }

    public function markDelivered(Order $order)
    {
        $sellerId = Auth::id();

        $ownsOrder = $order->orderItems()
            ->whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->exists();

        if (!$ownsOrder) {
            abort(403);
        }

        if (strtolower($order->status ?? '') !== 'shipped') {
            return redirect()->route('seller.orders.index')
                ->with('error', 'Only shipped orders can be marked as delivered.');
        }

        $order->status = 'delivered';
        $order->payment_status = 'completed';
        $order->delivered_at = now();
        $order->save();

        // Log tracking event
        OrderTracking::log($order->id, 'delivered', 'Order Delivered',
            'Your order has been delivered. Thank you for shopping!');

        // Notify customer
        Notification::notifyUser(
            $order->user_id,
            'order_delivered',
            'Order Delivered!',
            "Order #{$order->id} has been delivered. Please leave a review!",
            $order->id,
            'order'
        );

        if ($order->email) {
            Mail::raw(
                "Your order #{$order->id} has been delivered. Thank you for shopping with us!",
                function ($message) use ($order) {
                    $message->to($order->email)->subject('Order delivered');
                }
            );
        }

        return redirect()->route('seller.orders.index')->with('success', 'Order marked as delivered.');
    }

    /**
     * Show seller settings page
     */
    public function settings()
    {
        $seller = Auth::user();
        $wallet = $seller->wallet ?? \App\Models\SellerWallet::create(['seller_id' => $seller->id]);
        $subscription = $seller->sellerSubscriptions()->latest()->first();

        if (!$subscription) {
            $monthlyRent = (float) ($seller->monthly_rent ?? 500.00);
            $subscription = \App\Models\SellerSubscription::create([
                'seller_id' => $seller->id,
                'subscription_type' => 'monthly',
                'amount' => $monthlyRent,
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->subDay()->toDateString(),
                'status' => 'expired',
                'auto_renew' => true,
            ]);

            if (!$seller->subscription_end_date || $seller->subscription_status === 'inactive') {
                $seller->update([
                    'subscription_status' => 'expired',
                    'subscription_end_date' => $subscription->end_date,
                    'monthly_rent' => $monthlyRent,
                ]);
            }
        }

        $payments = $seller->sellerPayments()->latest()->paginate(10);
        $transactions = $seller->walletTransactions()->latest()->paginate(10);

        return view('seller.settings', compact('seller', 'wallet', 'subscription', 'payments', 'transactions'));
    }

    /**
     * Update seller settings
     */
    public function updateSettings(Request $request)
    {
        $seller = Auth::user();

        $section = $request->input('section', 'profile');

        if ($section === 'profile') {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:sellers,email,' . $seller->id,
                'phone' => 'nullable|string|max:20',
            ]);
            $seller->update($validated);
        } elseif ($section === 'business') {
            $validated = $request->validate([
                'shop_name' => 'required|string|max:255',
                'shop_description' => 'nullable|string|max:1000',
                'shop_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            ]);

            $update = [
                'shop_name' => $validated['shop_name'],
                'shop_description' => $validated['shop_description'] ?? null,
            ];

            if ($request->hasFile('shop_logo')) {
                $oldLogo = $seller->shop_logo;

                $logoFile = $request->file('shop_logo');
                $logoName = 'shop_logo_' . $seller->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $logoFile->getClientOriginalName());
                $logoFile->move(public_path('uploaded_img'), $logoName);
                $update['shop_logo'] = $logoName;

                if (!empty($oldLogo)) {
                    $oldPath = public_path('uploaded_img/' . $oldLogo);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
            }

            $seller->update($update);
        } elseif ($section === 'password') {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Check current password using SHA1
            if (!hash_equals($seller->password, sha1($validated['current_password']))) {
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            $seller->update(['password' => sha1($validated['password'])]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Show seller violation/suspension details page
     * For sellers suspended due to policy violation, reviews, etc.
     */
    public function violations()
    {
        $seller = Auth::user();

        // Redirect to dashboard if not suspended
        if ($seller->subscription_status !== 'suspended') {
            return redirect()->route('seller.dashboard')->with('info', 'Your account is not suspended.');
        }

        return view('seller.violations', compact('seller'));
    }

    /**
     * Send support message to admin (from violations page or anywhere)
     */
    public function sendSupportMessage(Request $request)
    {
        $seller = Auth::user();

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        \App\Models\Message::create([
            'name' => $seller->name,
            'email' => $seller->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'source' => 'seller',
            'seller_id' => $seller->id,
            'status' => 'unread',
        ]);

        return redirect()->back()->with('success', 'Your message has been sent. Admin will get back to you soon.');
    }
}
