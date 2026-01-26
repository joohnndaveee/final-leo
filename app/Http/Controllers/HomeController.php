<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the home page with random products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch 6 random products from the database
        $products = Product::inRandomOrder()
                           ->limit(6)
                           ->get();

        return view('home', [
            'products' => $products
        ]);
    }
}
