<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class UserShopController extends Controller
{
    /**
     * Display the shop page with optional category filtering
     */
    public function index(Request $request)
    {
        // Sellers should not browse buyer shop pages; send them to seller dashboard
        if (Auth::check() && (Auth::user()->role ?? 'buyer') === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        // Get the category from URL query parameter
        $category = $request->query('category');
        $search = $request->query('q');
        $sort = $request->query('sort', 'newest');
        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');
        
        // Build the query with ordering by ID (descending)
        $query = Product::query();
        
        // If category is provided, filter by it
        if ($category) {
            $query->where('type', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        if ($priceMin !== null) {
            $query->where('price', '>=', (float) $priceMin);
        }

        if ($priceMax !== null) {
            $query->where('price', '<=', (float) $priceMax);
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                // Sort by average rating via subquery
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }
        
        // Paginate products (24 per page) instead of loading all
        $products = $query->paginate(24);
        
        // Pass products and category to the view
        return view('shop', [
            'products' => $products,
            'category' => $category,
            'search' => $search,
            'sort' => $sort,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
        ]);
    }
}
