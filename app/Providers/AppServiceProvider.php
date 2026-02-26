<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\CartWishlistComposer;
use App\Models\Discount;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share cart and wishlist counts with all views
        View::composer('*', CartWishlistComposer::class);

        // Share the active admin seasonal discount (for buyer navbar banner).
        View::composer('layouts.app', function ($view) {
            $now = now();
            $seasonalDiscount = Discount::query()
                ->whereNull('seller_id')
                ->where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->orderByDesc('start_date')
                ->orderByDesc('created_at')
                ->first(['id', 'name', 'type', 'value', 'end_date']);

            $view->with('seasonalDiscount', $seasonalDiscount);
        });
    }
}
