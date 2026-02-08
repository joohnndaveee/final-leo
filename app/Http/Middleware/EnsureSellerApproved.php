<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerApproved
{
    /**
     * Handle an incoming request.
     *
     * Only allow sellers with seller_status = 'approved' to proceed.
     * Admin users (role = 'admin') are always allowed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow app-side admins through
        if (($user->role ?? 'buyer') === 'admin') {
            return $next($request);
        }

        if (($user->role ?? 'buyer') === 'seller' && ($user->seller_status ?? 'pending') !== 'approved') {
            $status = $user->seller_status ?? 'pending';

            if ($status === 'rejected') {
                $message = 'Your seller application was rejected. Please review your shop details or contact support.';
            } else {
                $message = 'Your seller application is pending approval. You can review your shop details here.';
            }

            return redirect()->route('seller.application.status')->with('info', $message);
        }

        return $next($request);
    }
}

