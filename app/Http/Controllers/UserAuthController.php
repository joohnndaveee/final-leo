<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Seller;
use App\Models\SellerSubscription;
use App\Models\SellerWallet;

class UserAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        return view('user_login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:50',
            'pass' => 'required|max:20',
        ]);

        $email = $request->input('email');
        $password = sha1($request->input('pass')); // Using SHA1 like the old system

        $user = User::where('email', $email)->first();

        if ($user && hash_equals($user->password, $password)) {
            Auth::guard('web')->login($user);
            return redirect()->route('home')->with('success', 'Login successful!');
        }

        return back()->withErrors(['login' => 'Incorrect email or password!'])->withInput();
    }

    /**
     * Show the seller login form
     */
    public function showSellerLoginForm()
    {
        // Check the specific 'seller' guard
        if (Auth::guard('seller')->check()) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.login');
    }


    /**
     * Handle seller login (separate page)
     */
    public function sellerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
            'pass' => 'required|max:20',
        ]);

        $email = $request->input('email');
        $password = sha1($request->input('pass'));

        $seller = Seller::where('email', $email)->first();

        if (!$seller || !hash_equals($seller->password, $password)) {
            return back()->withErrors(['login' => 'Incorrect email or password!'])->withInput();
        }

        if ($seller->status === 'pending') {
            return back()->withErrors(['login' => 'Your seller application is pending approval.'])->withInput();
        }

        if ($seller->status === 'rejected') {
            return back()->withErrors(['login' => 'Your seller application has been rejected.'])->withInput();
        }

        if ($seller->status === 'suspended') {
            return back()->withErrors(['login' => 'Your seller account has been suspended.'])->withInput();
        }

        Auth::guard('seller')->login($seller);

        $message = 'Seller login successful!';
        if (!$seller->approved_notified) {
            $message = 'Congratulations! Your seller application has been approved. Welcome to the Seller Center.';
            $seller->approved_notified = true;
            $seller->save();
        }

        return redirect()->route('seller.dashboard')->with('success', $message);
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        return view('user_register');
    }

    /**
     * Show the seller registration form
     */
    public function showSellerRegisterForm()
    {
        if (Auth::guard('seller')->check()) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
            'email' => 'required|email|max:50|unique:users,email',
            'pass' => 'required|max:20',
            'cpass' => 'required|same:pass',
        ], [
            'email.unique' => 'Email already exists!',
            'cpass.same' => 'Confirm password not matched!',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => sha1($request->input('pass')),
        ]);

        return redirect()->route('login')->with('success', 'Registered successfully, login now please!');
    }

    /**
     * Handle seller registration
     */
    public function sellerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|max:50|unique:sellers,email',
            'pass' => 'required|max:20',
            'cpass' => 'required|same:pass',
            'shop_name' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email already exists!',
            'cpass.same' => 'Confirm password not matched!',
        ]);

        // Create seller account
        $seller = Seller::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => sha1($request->input('pass')),
            'shop_name' => $request->input('shop_name'),
            'status' => 'pending',
            'approved_notified' => false,
            'subscription_status' => 'inactive',
            'monthly_rent' => 500.00, // Default monthly rent
        ]);

        // Create initial subscription (inactive until approved and paid)
        SellerSubscription::create([
            'seller_id' => $seller->id,
            'subscription_type' => 'monthly',
            'amount' => 500.00,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'expired', // Starts as expired until first payment
            'auto_renew' => true,
        ]);

        // Create wallet for the seller
        SellerWallet::create([
            'seller_id' => $seller->id,
            'balance' => 0.00,
            'total_deposited' => 0.00,
            'total_withdrawn' => 0.00,
        ]);

        return redirect()->route('seller.login')->with('success', 'Registered as seller. Your application is pending approval.');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('seller.login')->with('success', 'Seller logged out successfully!');
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Logged out successfully!');
        }

        return redirect()->route('login');
    }
}
