<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Show the user profile page
     */
    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to view your profile!');
        }

        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update the user profile
     */
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to update your profile!');
        }

        $user = Auth::user();

        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Update basic information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Update password if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect!'])
                           ->withInput();
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Load orders markup for the profile panel (AJAX).
     */
    public function ordersPanel(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $tab = $this->normalizeOrderTab($request->query('tab'));

        $allOrders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product.seller', 'tracking'])
            ->orderBy('id', 'desc')
            ->get();

        $orders = $allOrders
            ->filter(fn (Order $order) => $this->orderMatchesTab($order, $tab))
            ->values();

        $tabCounts = [
            'all'          => $allOrders->count(),
            'to_pay'       => $allOrders->filter(fn (Order $order) => $this->orderMatchesTab($order, 'to_pay'))->count(),
            'to_ship'      => $allOrders->filter(fn (Order $order) => $this->orderMatchesTab($order, 'to_ship'))->count(),
            'to_receive'   => $allOrders->filter(fn (Order $order) => $this->orderMatchesTab($order, 'to_receive'))->count(),
            'completed'    => $allOrders->filter(fn (Order $order) => $this->orderMatchesTab($order, 'completed'))->count(),
            'cancelled'    => $allOrders->filter(fn (Order $order) => $this->orderMatchesTab($order, 'cancelled'))->count(),
            'return_refund'=> $allOrders->filter(fn (Order $order) => $this->orderMatchesTab($order, 'return_refund'))->count(),
        ];

        $html = view('partials.profile-orders-panel', compact('orders', 'tab', 'tabCounts'))->render();

        return response()->json([
            'tab' => $tab,
            'html' => $html,
        ]);
    }

    /**
     * Return a single order's detail HTML for the inline profile panel.
     */
    public function orderDetail($orderId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $order = Order::with(['orderItems.product', 'tracking'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $html = view('partials.profile-order-detail', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    /**
     * Load notifications markup for the profile panel (AJAX).
     */
    public function notificationsPanel()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $html = view('partials.profile-notifications-panel', compact('notifications'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }

    private function normalizeOrderTab(?string $tab): string
    {
        $allowedTabs = ['all', 'to_pay', 'to_ship', 'to_receive', 'completed', 'cancelled', 'return_refund'];
        return in_array($tab, $allowedTabs, true) ? $tab : 'all';
    }

    private function orderMatchesTab(Order $order, string $tab): bool
    {
        if ($tab === 'all') {
            return true;
        }

        $status = strtolower((string) ($order->status ?? ''));
        $paymentStatus = strtolower((string) ($order->payment_status ?? ''));

        return match ($tab) {
            'to_pay'       => $paymentStatus === 'pending' && in_array($status, ['pending', 'paid', ''], true),
            'to_ship'      => in_array($status, ['paid', 'confirmed', 'packed', 'processing'], true)
                              || ($status === 'pending' && in_array($paymentStatus, ['paid', 'completed', 'complete'], true)),
            'to_receive'   => in_array($status, ['shipped', 'out_for_delivery', 'delivered', 'in_transit'], true),
            'completed'    => in_array($status, ['completed', 'complete'], true),
            'cancelled'    => $status === 'cancelled',
            'return_refund'=> in_array($status, [
                'not_received',
                'refunded', 'returned', 'return_requested',
                'return_pickup_scheduled', 'return_picked_up',
                'return_preparing', 'return_in_transit_to_seller',
            ], true) || in_array($paymentStatus, ['refunded'], true),
            default => true,
        };
    }
}
