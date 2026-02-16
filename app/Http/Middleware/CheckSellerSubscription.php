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

        // Keep seller.subscription_status in sync with subscription_end_date
        if ($seller->subscription_status === 'active' && $seller->subscription_end_date && $seller->subscription_end_date->isPast()) {
            $seller->update(['subscription_status' => 'expired']);
        }

        // Treat inactive as locked (unpaid) as well
        if (in_array($seller->subscription_status, ['inactive', 'expired', 'suspended']) || !$seller->subscription_end_date) {
            // Allow access to settings and wallet pages even when subscription expired
            $allowedRoutes = [
                'seller.settings',
                'seller.settings.update',
                'seller.subscription.show',
                'seller.wallet.index',
                'seller.wallet.deposit.form',
                'seller.wallet.deposit',
                'seller.wallet.pay-rent.form',
                'seller.wallet.pay-rent',
                'seller.wallet.payment-receipt',
                'seller.violations',
                'seller.support.send',
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                // If suspended for a reason other than overdue payment, paying should not "unlock" features.
                if ($seller->subscription_status === 'suspended' && ($seller->suspension_reason ?? '') !== 'Overdue Payment') {
                    return redirect()
                        ->route('seller.violations')
                        ->with('error', 'Your seller account is suspended. Please view the suspension details or contact the administrator.');
                }

                return redirect()
                    ->route('seller.wallet.pay-rent.form')
                    ->with('error', 'Your subscription is not active. Please pay your monthly rent to access this feature.');
            }
        }

        return $next($request);
    }
}
