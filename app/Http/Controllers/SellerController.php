<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

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

        return view('seller.products', compact('seller', 'products'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'details' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'image_01' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_02' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_03' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image uploads
        $image01Name = null;
        $image02Name = null;
        $image03Name = null;

        if ($request->hasFile('image_01')) {
            $image01Name = time() . '_1_' . $request->file('image_01')->getClientOriginalName();
            $request->file('image_01')->move(public_path('uploaded_img'), $image01Name);
        }

        if ($request->hasFile('image_02')) {
            $image02Name = time() . '_2_' . $request->file('image_02')->getClientOriginalName();
            $request->file('image_02')->move(public_path('uploaded_img'), $image02Name);
        }

        if ($request->hasFile('image_03')) {
            $image03Name = time() . '_3_' . $request->file('image_03')->getClientOriginalName();
            $request->file('image_03')->move(public_path('uploaded_img'), $image03Name);
        }

        Product::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'details' => $request->details,
            'price' => $request->price,
            'type' => $request->type,
            'stock' => $request->stock,
            'image_01' => $image01Name,
            'image_02' => $image02Name,
            'image_03' => $image03Name,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Product created.');
    }

    public function editProduct($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        return view('seller.edit-product', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'details' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'type' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'image_01' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_02' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_03' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->type = $request->type;
        $product->stock = $request->stock;

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

        if ($request->hasFile('image_02')) {
            $oldPath = public_path('uploaded_img/' . $product->image_02);
            if ($product->image_02 && File::exists($oldPath)) {
                File::delete($oldPath);
            }
            $image02Name = time() . '_2_' . $request->file('image_02')->getClientOriginalName();
            $request->file('image_02')->move(public_path('uploaded_img'), $image02Name);
            $product->image_02 = $image02Name;
        }

        if ($request->hasFile('image_03')) {
            $oldPath = public_path('uploaded_img/' . $product->image_03);
            if ($product->image_03 && File::exists($oldPath)) {
                File::delete($oldPath);
            }
            $image03Name = time() . '_3_' . $request->file('image_03')->getClientOriginalName();
            $request->file('image_03')->move(public_path('uploaded_img'), $image03Name);
            $product->image_03 = $image03Name;
        }

        $product->save();

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
                'shop_name' => 'required|string|max:100',
                'shop_description' => 'nullable|string|max:500',
            ]);
            $seller->update($validated);
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
