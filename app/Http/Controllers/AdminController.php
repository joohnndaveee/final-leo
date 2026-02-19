<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Seller;
use App\Models\Message;
use App\Models\Chat;
use App\Models\SellerPayment;
use Illuminate\Support\Facades\Mail;

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
            'name' => 'required|email|max:50',
            'password' => 'required|string|max:20',
        ]);

        $email = $request->input('name'); // Using name field but checking email
        $password = sha1($request->input('password')); // Using SHA1 like the system

        // Find admin by email
        $admin = Admin::where('email', $email)->first();

        if ($admin && hash_equals($admin->password, $password)) {
            // Password is correct, log in the admin
            Auth::guard('admin')->login($admin);
            
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

        // Seller metrics (based on status in sellers table)
        $total_sellers = Seller::count();
        $pending_sellers = Seller::where('status', 'pending')->count();
        $approved_sellers = Seller::where('status', 'approved')->count();
        $rejected_sellers = Seller::where('status', 'rejected')->count();

        // 6. Customer Messages - Count of all messages and unread
        $number_of_messages = Message::count();
        $unread_messages = Message::where('status', 'unread')->count();

        // 7. Live Chats - Count of unique user conversations (kept for reference)
        $number_of_chats = Chat::select('user_id')->distinct()->count();

        // Get admin info
        $admin = Auth::guard('admin')->user();

        return view('admin.dashboard', compact(
            'pending_orders',
            'total_sales',
            'number_of_orders',
            'number_of_products',
            'number_of_users',
            'total_sellers',
            'pending_sellers',
            'approved_sellers',
            'rejected_sellers',
            'number_of_messages',
            'unread_messages',
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
            'status' => 'required|in:pending,paid,shipped,delivered,cancelled,refunded'
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found!'
            ], 404);
        }

        $order->payment_status = in_array($request->status, ['paid', 'shipped', 'delivered']) ? 'completed' : $request->status;
        $order->status = $request->status;

        if ($request->status === 'shipped') {
            $order->shipped_at = now();
        }

        if ($request->status === 'delivered') {
            $order->delivered_at = now();
        }

        if ($request->status === 'cancelled') {
            $order->cancelled_at = now();
        }
        $order->save();

        if ($order->email) {
            Mail::raw(
                "Your order #{$order->id} status updated to {$order->status}.",
                function ($message) use ($order) {
                    $message->to($order->email)->subject('Order status updated');
                }
            );
        }

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

        // Get all buyers/customers from users table
        $users = User::orderBy('created_at', 'desc')->get();
        
        return view('admin.users', compact('users'));
    }

    /**
     * Display all sellers
     */
    public function sellers()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access admin panel!');
        }

        // Get all sellers from sellers table
        $query = Seller::query();

        if (request()->filled('status')) {
            $status = request()->get('status');
            if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
                $query->where('status', $status);
            }
        }

        $sellers = $query->orderBy('created_at', 'desc')->get();

        return view('admin.sellers', compact('sellers'));
    }

    /**
     * Show a single seller's application details.
     */
    public function showSeller(Request $request, $id)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access admin panel!');
        }

        $seller = Seller::where('id', $id)->firstOrFail();
        $registrationPayment = SellerPayment::where('seller_id', $seller->id)
            ->where('payment_type', 'registration')
            ->latest('id')
            ->first();

        // For non-approved sellers, show only the application details + management actions.
        if (($seller->status ?? 'pending') !== 'approved') {
            return view('admin.seller_show', compact('seller', 'registrationPayment'));
        }

        // Get seller's products
        $sellerProducts = Product::where('seller_id', $id)->get();

        // ===== ORDERS FILTERS =====
        $ordersQuery = Order::whereHas('orderItems.product', function ($query) use ($id) {
            $query->where('seller_id', $id);
        });

        // Filter by order status
        if ($request->has('order_status') && $request->order_status !== '') {
            $ordersQuery->where('status', $request->order_status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $ordersQuery->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('order_date_range') && $request->order_date_range !== '') {
            $now = now();
            switch ($request->order_date_range) {
                case '7days':
                    $ordersQuery->where('placed_on', '>=', $now->copy()->subDays(7));
                    break;
                case '30days':
                    $ordersQuery->where('placed_on', '>=', $now->copy()->subDays(30));
                    break;
                case '90days':
                    $ordersQuery->where('placed_on', '>=', $now->copy()->subDays(90));
                    break;
            }
        }

        $orders = $ordersQuery->orderByDesc('id')->get();

        // ===== REVIEWS FILTERS =====
        $reviewsQuery = Review::whereIn('product_id', $sellerProducts->pluck('id'))->with('user', 'product');

        // Filter by rating
        if ($request->has('review_rating') && $request->review_rating !== '') {
            $reviewsQuery->where('rating', $request->review_rating);
        }

        // Filter by review date range
        if ($request->has('review_date_range') && $request->review_date_range !== '') {
            $now = now();
            switch ($request->review_date_range) {
                case 'latest':
                    // Already ordered by latest below
                    break;
                case 'oldest':
                    $reviewsQuery->orderBy('created_at', 'asc');
                    break;
                case '7days':
                    $reviewsQuery->where('created_at', '>=', $now->copy()->subDays(7));
                    break;
                case '30days':
                    $reviewsQuery->where('created_at', '>=', $now->copy()->subDays(30));
                    break;
            }
        }

        // Search reviews by comment
        if ($request->has('review_search') && $request->review_search !== '') {
            $searchTerm = '%' . $request->review_search . '%';
            $reviewsQuery->where('comment', 'like', $searchTerm)
                        ->orWhereHas('user', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        });
        }

        $reviews = $reviewsQuery->orderByDesc('created_at')->get();

        // ===== PRODUCTS FILTERS =====
        $productsQuery = Product::where('seller_id', $id);

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status !== '') {
            switch ($request->stock_status) {
                case 'in_stock':
                    $productsQuery->where('stock', '>', 10);
                    break;
                case 'low_stock':
                    $productsQuery->whereBetween('stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $productsQuery->where('stock', 0);
                    break;
            }
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min !== '') {
            $productsQuery->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max !== '') {
            $productsQuery->where('price', '<=', $request->price_max);
        }

        // Sort products
        if ($request->has('product_sort') && $request->product_sort !== '') {
            switch ($request->product_sort) {
                case 'newest':
                    $productsQuery->orderByDesc('id');
                    break;
                case 'price_high':
                    $productsQuery->orderByDesc('price');
                    break;
                case 'price_low':
                    $productsQuery->orderBy('price');
                    break;
            }
        } else {
            $productsQuery->orderByDesc('id');
        }

        $filteredProducts = $productsQuery->get();

        // Calculate statistics
        $totalOrders = $orders->count();
        $averageRating = Review::whereIn('product_id', $sellerProducts->pluck('id'))->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('admin.seller_show', compact(
            'seller', 'registrationPayment', 'orders', 'reviews', 'sellerProducts', 'filteredProducts',
            'totalOrders', 'averageRating', 'totalReviews'
        ));
    }

    /**
     * Update a user's role or seller status.
     */
    /**
     * Update seller status (approve/reject/suspend)
     */
    public function updateUserRole(Request $request, $id)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'seller_status' => 'required|in:pending,approved,rejected',
        ]);

        // Check if this is a seller ID
        $seller = Seller::find($id);

        if ($seller) {
            // Once approved, lock the application status (cannot be changed anymore).
            if ($seller->status === 'approved' && $request->seller_status !== 'approved') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This seller is already approved and the status can no longer be changed.',
                ], 422);
            }

            $seller->status = $request->seller_status;
            
            if ($request->seller_status === 'approved' && !$seller->approved_at) {
                $seller->approved_at = now();
                $seller->approved_notified = false; // Will show message on next login
            }
            
            $seller->save();

            // When approving a seller, mark their latest pending registration fee proof as completed
            if ($request->seller_status === 'approved') {
                $registrationPayment = $seller->sellerPayments()
                    ->where('payment_type', 'registration')
                    ->where('payment_status', 'pending')
                    ->latest('id')
                    ->first();

                if ($registrationPayment) {
                    $registrationPayment->update([
                        'payment_status' => 'completed',
                        'paid_at' => $registrationPayment->paid_at ?? now(),
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Seller status updated successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Seller not found',
        ], 404);
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

        // Source filter (guest, seller) - Messages page shows contact/guest messages
        // Use whereRaw for backwards compatibility with existing rows that may not have source
        if ($request->filled('source')) {
            $query->where(function ($q) use ($request) {
                if ($request->source === 'guest') {
                    $q->where('source', 'guest')->orWhereNull('source');
                } else {
                    $q->where('source', $request->source);
                }
            });
        }

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

        // Statistics (scoped by source if filtering)
        $statsQuery = Message::query();
        if ($request->filled('source')) {
            if ($request->source === 'guest') {
                $statsQuery->where(function ($q) {
                    $q->where('source', 'guest')->orWhereNull('source');
                });
            } else {
                $statsQuery->where('source', $request->source);
            }
        }
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'unread' => (clone $statsQuery)->where('status', 'unread')->count(),
            'read' => (clone $statsQuery)->where('status', 'read')->count(),
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
     * Display all seller subscriptions
     */
    public function subscriptions(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access admin panel!');
        }

        // Keep subscription_status in sync with subscription_end_date for reporting
        Seller::where('subscription_status', 'active')
            ->whereNotNull('subscription_end_date')
            ->where('subscription_end_date', '<', now())
            ->update(['subscription_status' => 'expired']);

        $query = Seller::with(['sellerSubscriptions' => function($q) {
            $q->latest();
        }]);

        // Filter by subscription status
        if ($request->filled('subscription_status')) {
            $query->where('subscription_status', $request->subscription_status);
        }

        // Filter by seller status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by shop name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sellers = $query->orderBy('subscription_end_date', 'asc')->get();

        // Calculate statistics
        $stats = [
            'total' => Seller::count(),
            'active' => Seller::where('subscription_status', 'active')->count(),
            'expired' => Seller::where('subscription_status', 'expired')->count(),
            'suspended' => Seller::where('subscription_status', 'suspended')->count(),
            'expiring_soon' => Seller::where('subscription_status', 'active')
                                    ->where('subscription_end_date', '<=', now()->addDays(7))
                                    ->where('subscription_end_date', '>=', now())
                                    ->count(),
        ];

        return view('admin.subscriptions', compact('sellers', 'stats'));
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
