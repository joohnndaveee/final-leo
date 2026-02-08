<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm(Request $request)
    {
        // Redirect already-logged-in BUYERS to home
        if (Auth::check()) {
            $user = Auth::user();

            if (($user->role ?? 'buyer') !== 'seller') {
                return redirect()->route('home');
            }

            // If a SELLER hits the login page, log them out first
            // so the form behaves like a fresh, not-logged-in session.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
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

        // Query the user by email first
        $user = User::where('email', $email)->first();

        // Check password using hash_equals to prevent timing attacks
        if ($user && hash_equals($user->password, $password)) {
            // If the user is a seller, ensure they are approved before allowing login
            if (($user->role ?? 'buyer') === 'seller') {
                $status = $user->seller_status ?? 'pending';
                if ($status !== 'approved') {
                    return back()->withErrors(['login' => 'Your seller account is ' . $status . '.']);
                }
            }

            // Log the user in using the default web guard
            Auth::login($user);

            // If this account is a seller, send them to the seller dashboard
            if (($user->role ?? 'buyer') === 'seller') {
                return redirect()->route('seller.dashboard')->with('success', 'Login successful!');
            }

            return redirect()->route('home')->with('success', 'Login successful!');
        } else {
            return back()->withErrors(['login' => 'Incorrect email or password!'])->withInput();
        }
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
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:50',
            'pass' => 'required|max:20',
        ]);

        $email = $request->input('email');
        $password = sha1($request->input('pass')); // Using SHA1 like the old system

        $user = User::where('email', $email)->first();

        // Validate user exists, password matches, and role is seller
        if (!$user || !hash_equals($user->password, $password) || (($user->role ?? 'buyer') !== 'seller')) {
            return back()
                ->withErrors(['login' => 'These credentials do not belong to a seller account.'])
                ->withInput($request->only('email'));
        }

        // Check seller application status
        $status = $user->seller_status ?? 'pending';

        // If not approved yet, do NOT log them in; just show message on login page
        if ($status === 'pending') {
            return back()
                ->with('seller_status', 'pending')
                ->with('success', 'Your seller application is still pending approval.')
                ->withInput($request->only('email'));
        }

        if ($status === 'rejected') {
            return back()
                ->with('seller_status', 'rejected')
                ->with('success', 'Your seller application was rejected. Please contact the admin for more details.')
                ->withInput($request->only('email'));
        }

        // Approved: log in and optionally show one-time approval message
        Auth::guard('seller')->login($user);

        if (!($user->seller_approved_notified ?? false)) {
            $message = 'Congratulations! Your seller application has been approved. Welcome to the Seller Center.';
            $user->seller_approved_notified = true;
            $user->save();
        } else {
            $message = 'Seller login successful!';
        }

        return redirect()->route('seller.dashboard')->with('success', $message);
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm(Request $request)
    {
        // Redirect already-logged-in BUYERS to home
        if (Auth::check()) {
            $user = Auth::user();

            if (($user->role ?? 'buyer') !== 'seller') {
                return redirect()->route('home');
            }

            // If a SELLER hits the register page, log them out first
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('user_register');
    }

    /**
     * Show the seller registration form
     */
    public function showSellerRegisterForm()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (($user->role ?? 'buyer') === 'seller') {
                return redirect()->route('seller.dashboard');
            }
        }

        return view('seller.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|max:20',
            'email' => 'required|email|max:50|unique:users,email',
            'pass' => 'required|max:20',
            'cpass' => 'required|same:pass',
        ], [
            'email.unique' => 'Email already exists!',
            'cpass.same' => 'Confirm password not matched!',
        ]);

        // Create new user with SHA1 password
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => sha1($request->input('pass')), // Using SHA1 like the old system
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
            'email' => 'required|email|max:50|unique:users,email',
            'pass' => 'required|max:20',
            'cpass' => 'required|same:pass',
            'shop_name' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email already exists!',
            'cpass.same' => 'Confirm password not matched!',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => sha1($request->input('pass')),
            'role' => 'seller',
            'shop_name' => $request->input('shop_name'),
            'seller_status' => 'pending',
            'seller_approved_notified' => false,
        ]);

        return redirect()->route('seller.login')->with('success', 'Registered as seller. Your application is pending approval.');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        // If logged in as a seller, logout from the seller guard
        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
            // We do NOT invalidate the session here to preserve the Buyer session if it exists
            return redirect()->route('seller.login')->with('success', 'Seller logged out successfully!');
        }

        // Otherwise, perform standard web/buyer logout
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
