<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Wishlist;

class CartWishlistComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $cartCount = 0;
        $wishlistCount = 0;

        // Get counts only if user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Count cart items for the current user
            $cartCount = Cart::where('user_id', $userId)->count();
            
            // Count wishlist items for the current user
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
        }

        // Share the counts with the view
        $view->with([
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
        ]);
    }
}
