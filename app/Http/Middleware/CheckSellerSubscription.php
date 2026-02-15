<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSellerSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect()->route('seller.login');
        }

        // Check if subscription is expired or suspended
        if (in_array($seller->subscription_status, ['expired', 'suspended'])) {
            // Allow access to settings and wallet pages even when subscription expired
            $allowedRoutes = [
                'seller.settings',
                'seller.settings.update',
                'seller.wallet.index',
                'seller.wallet.deposit.form',
                'seller.wallet.deposit',
                'seller.wallet.pay-rent.form',
                'seller.wallet.pay-rent',
                'seller.wallet.payment-receipt',
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()
                    ->route('seller.wallet.pay-rent.form')
                    ->with('error', 'Your subscription has ' . $seller->subscription_status . '. Please renew your subscription to access this feature.');
            }
        }

        return $next($request);
    }
}
