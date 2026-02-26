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
            static $heroBgPath  = false; // false = not yet fetched; null = fetched, no value

            $cartCount = 0;
            $wishlistCount = 0;

            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }

            if ($siteLogoUrl === null) {
                try {
                    $row = DB::table('site_settings')->where('id', 1)->first();
                    $siteLogoUrl = asset($row?->site_logo_path ?: 'images/logo.png');
                    $heroBgPath  = $row?->hero_bg_path ?: null;
                    $seasonalBannerEnabled = (bool) ($row?->seasonal_banner_enabled ?? true);
                    $seasonalBannerBgColor = $row?->seasonal_banner_bg_color ?: '#1a3009';
                    $seasonalBannerTextColor = $row?->seasonal_banner_text_color ?: '#ffffff';
                    $seasonalBannerMessage = $row?->seasonal_banner_message ?: null;
                } catch (\Throwable $e) {
                    $siteLogoUrl = asset('images/logo.png');
                    $heroBgPath  = null;
                    $seasonalBannerEnabled = true;
                    $seasonalBannerBgColor = '#1a3009';
                    $seasonalBannerTextColor = '#ffffff';
                    $seasonalBannerMessage = null;
                }
            }

            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
            $view->with('siteLogoUrl', $siteLogoUrl);
            $view->with('heroBgPath', $heroBgPath);
            $view->with('seasonalBannerEnabled', $seasonalBannerEnabled ?? true);
            $view->with('seasonalBannerBgColor', $seasonalBannerBgColor ?? '#1a3009');
            $view->with('seasonalBannerTextColor', $seasonalBannerTextColor ?? '#ffffff');
            $view->with('seasonalBannerMessage', $seasonalBannerMessage ?? null);
        });
    }
}
