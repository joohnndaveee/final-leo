<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products slider and all products slider.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch only necessary products for featured slider (limit to 10 most recent)
        $featuredProducts = Product::with('seller')->orderBy('id', 'desc')->limit(10)->get();
        
        // Fetch products for all products slider (limit to 20 most recent)
        $products = Product::with('seller')->orderBy('id', 'desc')->limit(20)->get();

        // Fetch active categories for the categories section
        $categories = Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'products'         => $products,
            'categories'       => $categories,
        ]);
    }
}
