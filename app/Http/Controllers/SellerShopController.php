<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SellerFollow;

class SellerShopController extends Controller
{
    public function show(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        $tab      = $request->query('tab', 'home');
        $category = $request->query('category');
        $search   = $request->query('q');

        // Base query — only active, in-stock products for this seller
        $query = Product::with(['discount', 'reviews'])
            ->where('seller_id', $seller->id)
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Category filter
        if ($category) {
            $cat = Category::where('is_active', true)
                ->where(function ($q) use ($category) {
                    $q->where('slug', $category)->orWhere('id', $category);
                })->first();

            if ($cat) {
                $query->where(function ($q) use ($cat) {
                    $q->where('category_id', $cat->id)
                      ->orWhere('type', $cat->slug)
                      ->orWhere('type', $cat->name);
                });
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        $products = $query->orderBy('id', 'desc')->paginate(20);

        // Featured/home products (is_featured first or just latest 10)
        $featuredProducts = Product::with(['discount', 'reviews'])
            ->where('seller_id', $seller->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderByDesc('is_featured')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        // Categories this seller sells in
        $sellerCategories = Category::where('is_active', true)
            ->whereHas('products', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id)
                  ->where('is_active', true)
                  ->where('stock', '>', 0);
            })
            ->get();

        // Total products count
        $totalProducts = Product::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->count();

        // Total reviews / average rating across all seller products
        $allReviews = \App\Models\Review::whereHas('product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->get();

        $totalReviews  = $allReviews->count();
        $averageRating = $totalReviews > 0 ? round($allReviews->avg('rating'), 1) : 0;

        $followerCount = SellerFollow::where('seller_id', $seller->id)->count();
        $isFollowing   = Auth::check()
            ? SellerFollow::where('user_id', Auth::id())->where('seller_id', $seller->id)->exists()
            : false;

        return view('seller-shop', compact(
            'seller',
            'products',
            'featuredProducts',
            'sellerCategories',
            'totalProducts',
            'totalReviews',
            'averageRating',
            'followerCount',
            'isFollowing',
            'tab',
            'category',
            'search'
        ));
    }
}
