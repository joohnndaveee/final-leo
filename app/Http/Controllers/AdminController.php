<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Message;

class AdminController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        // Check if admin is already logged in
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login authentication
     */
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:20',
            'password' => 'required|string|max:20',
        ]);

        $name = $request->input('name');
        $password = $request->input('password');

        // Find admin by username
        $admin = Admin::where('name', $name)->first();

        if ($admin && \Illuminate\Support\Facades\Hash::check($password, $admin->password)) {
            // Password is correct, log in the admin
            Auth::guard('admin')->login($admin);
            
            // Also store in session for backward compatibility
            session(['admin_id' => $admin->id]);
            
            return redirect()->route('admin.dashboard')
                           ->with('success', 'Login successful!');
        }

        // Login failed
        return back()
            ->withErrors(['login' => 'Incorrect username or password!'])
            ->withInput($request->only('name'));
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Check if admin is logged in using Laravel's auth system
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->withErrors(['login' => 'Please login first']);
        }

        // Calculate dashboard statistics
        // 1. Pending Orders - Count of orders where payment_status = 'pending'
        $pending_orders = Order::where('payment_status', 'pending')->count();

        // 2. Total Sales - Sum of total_price where payment_status = 'complete' or 'delivered'
        $total_sales = Order::whereIn('payment_status', ['complete', 'completed', 'delivered'])->sum('total_price');

        // 3. Orders Placed - Count of all orders
        $number_of_orders = Order::count();

        // 4. Products Added - Count of all products
        $number_of_products = Product::count();

        // 5. Registered Users - Count of all users
        $number_of_users = User::count();

        // 6. Customer Messages - Count of all messages
        $number_of_messages = Message::count();

        // Get admin info
        $admin = Auth::guard('admin')->user();

        return view('admin.dashboard', compact(
            'pending_orders',
            'total_sales',
            'number_of_orders',
            'number_of_products',
            'number_of_users',
            'number_of_messages',
            'admin'
        ));
    }

    /**
     * Show all customer orders
     */
    public function adminOrders()
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->withErrors(['login' => 'Please login first']);
        }

        // Fetch all orders with user information, ordered by newest first
        $orders = Order::with('user')
                      ->orderBy('id', 'desc')
                      ->get();

        $admin = Auth::guard('admin')->user();

        return view('admin.orders', compact('orders', 'admin'));
    }

    /**
     * Show specific order details for admin
     */
    public function showOrderDetails($orderId)
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->withErrors(['login' => 'Please login first']);
        }

        $order = Order::with('orderItems')->find($orderId);

        if (!$order) {
            return redirect()->route('admin.orders')
                           ->with('error', 'Order not found!');
        }

        $admin = Auth::guard('admin')->user();

        return view('admin.order-details', compact('order', 'admin'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $orderId)
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validate the status
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found!'
            ], 404);
        }

        $order->payment_status = $request->status;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully!'
        ]);
    }

    /**
     * Display all users
     */
    public function users()
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access admin panel!');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        
        return view('admin.users', compact('users'));
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully!'
        ]);
    }

    /**
     * Display all messages
     */
    public function messages()
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access admin panel!');
        }

        $messages = Message::orderBy('created_at', 'desc')->get();
        
        return view('admin.messages', compact('messages'));
    }

    /**
     * Delete a message
     */
    public function deleteMessage($id)
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message not found!'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Message deleted successfully!'
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        // Use Laravel's auth system to logout
        Auth::guard('admin')->logout();
        
        // Also clear session for backward compatibility
        session()->forget('admin_id');
        
        return redirect()->route('admin.login')
                       ->with('success', 'Logged out successfully!');
    }
}
