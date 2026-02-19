<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Wishlist;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share cart and wishlist count with all views
        View::composer('*', function ($view) {
            static $siteLogoUrl = null;

            $cartCount = 0;
            $wishlistCount = 0;

            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }

            if ($siteLogoUrl === null) {
                try {
                    $path = DB::table('site_settings')->where('id', 1)->value('site_logo_path');
                    $siteLogoUrl = asset($path ?: 'images/logo.png');
                } catch (\Throwable $e) {
                    $siteLogoUrl = asset('images/logo.png');
                }
            }

            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
            $view->with('siteLogoUrl', $siteLogoUrl);
        });
    }
}
