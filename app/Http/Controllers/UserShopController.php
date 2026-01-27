<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class UserShopController extends Controller
{
    /**
     * Display the shop page with optional category filtering
     */
    public function index(Request $request)
    {
        // Get the category from URL query parameter
        $category = $request->query('category');
        
        // Build the query with ordering by ID (descending)
        $query = Product::orderBy('id', 'desc');
        
        // If category is provided, filter by it
        if ($category) {
            $query->where('type', $category);
        }
        
        // Paginate products (24 per page) instead of loading all
        $products = $query->paginate(24);
        
        // Pass products and category to the view
        return view('shop', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
