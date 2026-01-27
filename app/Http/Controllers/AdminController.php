<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Message;
use App\Models\Chat;

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

        // 7. Live Chats - Count of unique user conversations
        $number_of_chats = Chat::select('user_id')->distinct()->count();

        // Get admin info
        $admin = Auth::guard('admin')->user();

        return view('admin.dashboard', compact(
            'pending_orders',
            'total_sales',
            'number_of_orders',
            'number_of_products',
            'number_of_users',
            'number_of_messages',
            'number_of_chats',
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
     * Display all messages with filtering and search
     */
    public function messages(Request $request)
    {
        // Check if admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access admin panel!');
        }

        $query = Message::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $messages = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Message::count(),
            'unread' => Message::where('status', 'unread')->count(),
            'read' => Message::where('status', 'read')->count(),
        ];
        
        return view('admin.messages', compact('messages', 'stats'));
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
     * Mark message as read
     */
    public function markMessageAsRead($id)
    {
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

        $message->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Message marked as read!'
        ]);
    }

    /**
     * Bulk delete messages
     */
    public function bulkDeleteMessages(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:messages,id'
        ]);

        Message::whereIn('id', $request->ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => count($request->ids) . ' message(s) deleted successfully!'
        ]);
    }

    /**
     * Export messages to CSV
     */
    public function exportMessages(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $query = Message::query();

        // Apply same filters as the messages page
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $messages = $query->orderBy('created_at', 'desc')->get();

        $filename = 'contact_inquiries_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($messages) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Subject', 'Message', 'Status', 'Created At', 'Read At']);
            
            // Add data
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->subject,
                    $message->message,
                    ucfirst($message->status),
                    $message->created_at->format('Y-m-d H:i:s'),
                    $message->read_at ? $message->read_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
