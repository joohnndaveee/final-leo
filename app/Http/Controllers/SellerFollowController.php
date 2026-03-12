<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerFollow;
use App\Models\Seller;

class SellerFollowController extends Controller
{
    public function toggle(Request $request, $sellerId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success'  => false,
                'redirect' => route('login'),
                'message'  => 'Please log in to follow shops.',
            ], 401);
        }

        $seller = Seller::findOrFail($sellerId);
        $userId = Auth::id();

        $existing = SellerFollow::where('user_id', $userId)
                                ->where('seller_id', $sellerId)
                                ->first();

        if ($existing) {
            $existing->delete();
            $following = false;
        } else {
            SellerFollow::create(['user_id' => $userId, 'seller_id' => $sellerId]);
            $following = true;
        }

        $followerCount = SellerFollow::where('seller_id', $sellerId)->count();

        return response()->json([
            'success'        => true,
            'following'      => $following,
            'follower_count' => $followerCount,
            'message'        => $following ? 'You are now following ' . $seller->shop_name : 'Unfollowed ' . $seller->shop_name,
        ]);
    }
}
