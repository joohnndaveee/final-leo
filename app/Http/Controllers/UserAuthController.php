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
    public function showLoginForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
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

        // Query the users table directly with plain-text comparison
        $user = User::where('email', $email)
                    ->where('password', $password)
                    ->first();

        if ($user) {
            // Log the user in using the default web guard
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Login successful!');
        } else {
            return back()->withErrors(['login' => 'Incorrect email or password!'])->withInput();
        }
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('user_register');
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
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
