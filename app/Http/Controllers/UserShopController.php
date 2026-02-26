<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
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
        $priceMinRaw = $request->query('price_min');
        $priceMaxRaw = $request->query('price_max');
        $priceMin = ($priceMinRaw === '' ? null : $priceMinRaw);
        $priceMax = ($priceMaxRaw === '' ? null : $priceMaxRaw);
        
        // Build the query (only active + in-stock products)
        // "above 1 quantity" => stock > 1
        $query = Product::with('seller')
            ->where('is_active', true)
            ->where('stock', '>', 1);
        $query->with('discount');
        
        // If category is provided, filter by it (slug-based via categories table)
        // Note: some legacy products store category in `type` instead of `category_id`.
        if ($category) {
            $cat = Category::where('is_active', true)
                ->where(function ($q) use ($category) {
                    $q->where('slug', $category)->orWhere('name', $category);
                })
                ->first();
            if ($cat) {
                $query->where(function ($q) use ($cat, $category) {
                    $q->where('category_id', $cat->id)
                      ->orWhere('type', $cat->slug)
                      ->orWhere('type', $cat->name)
                      ->orWhere('type', $category);
                });
            } else {
                // Fallback for legacy products stored with type column
                $query->where('type', $category);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        // Compute bounds for the price slider (before applying price filters)
        $boundsQuery = clone $query;
        $priceBoundsMin = (float) ($boundsQuery->min('price') ?? 0);
        $priceBoundsMax = (float) ($boundsQuery->max('price') ?? 0);
        if ($priceBoundsMax < $priceBoundsMin) {
            $priceBoundsMax = $priceBoundsMin;
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
        
        // Load active categories for sidebar list (include product counts).
        // Count both:
        // - modern products: products.category_id = categories.id
        // - legacy products: products.type = categories.slug OR categories.name
        $categories = Category::where('is_active', true)
            ->select('categories.*')
            ->selectSub(function ($q) {
                $q->from('products')
                    ->selectRaw('count(*)')
                    ->where('products.is_active', 1)
                    ->where('products.stock', '>', 1)
                    ->where(function ($w) {
                        $w->whereColumn('products.category_id', 'categories.id')
                          ->orWhereColumn('products.type', 'categories.slug')
                          ->orWhereColumn('products.type', 'categories.name');
                    });
            }, 'products_count')
            ->having('products_count', '>', 0)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Pass products and category to the view
        return view('shop', [
            'products'   => $products,
            'category'   => $category,
            'categories' => $categories,
            'search'     => $search,
            'sort'       => $sort,
            'priceMin'   => $priceMin,
            'priceMax'   => $priceMax,
            'priceBoundsMin' => $priceBoundsMin,
            'priceBoundsMax' => $priceBoundsMax,
        ]);
    }
}
