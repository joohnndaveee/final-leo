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

        // TODO: Migrate to Hash::check() for secure password comparison
        // Currently using plain-text password comparison for migration compatibility
        // Future: Update passwords to use Hash::make() and verify with Hash::check()
        // Then you can use: Auth::guard('admin')->attempt(['name' => $name, 'password' => $password])
        
        $admin = Admin::where('name', $name)
                     ->where('password', $password)
                     ->first();

        if ($admin) {
            // Use Laravel's auth system to log in the admin
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
        // 1. Total Pendings - Sum of total_price where order_status = 'pending'
        $total_pendings = Order::where('order_status', 'pending')->sum('total_price');

        // 2. Total Completes/Sales - Sum of total_price where order_status = 'delivered'
        $total_completes = Order::where('order_status', 'delivered')->sum('total_price');

        // 3. Orders Placed - Count of all orders
        $number_of_orders = Order::count();

        // 4. Products Added - Count of all products
        $number_of_products = Product::count();

        // 5. Normal Users - Count of all users
        $number_of_users = User::count();

        // 6. Admin Users - Count of all admins
        $number_of_admins = Admin::count();

        // 7. New Messages - Count of all messages
        $number_of_messages = Message::count();

        // Get admin info
        $admin = Auth::guard('admin')->user();

        return view('admin.dashboard', compact(
            'total_pendings',
            'total_completes',
            'number_of_orders',
            'number_of_products',
            'number_of_users',
            'number_of_admins',
            'number_of_messages',
            'admin'
        ));
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
