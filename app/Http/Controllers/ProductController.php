<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Check if admin is authenticated
     */
    protected function checkAuth()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                           ->withErrors(['login' => 'Please login first']);
        }
        return null;
    }

    /**
     * Display a listing of products and the add product form
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    /**
     * Store a newly created product in storage
     */
    public function store(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:9999999999',
            'details' => 'required|string|max:500',
            'type' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:50',
            'image_01' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_02' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_03' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Handle image uploads
            $image01Name = time() . '_1_' . $request->file('image_01')->getClientOriginalName();
            $image02Name = time() . '_2_' . $request->file('image_02')->getClientOriginalName();
            $image03Name = time() . '_3_' . $request->file('image_03')->getClientOriginalName();

            // Move images to public/uploaded_img
            $request->file('image_01')->move(public_path('uploaded_img'), $image01Name);
            $request->file('image_02')->move(public_path('uploaded_img'), $image02Name);
            $request->file('image_03')->move(public_path('uploaded_img'), $image03Name);

            // Create the product
            Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'details' => $request->details,
                'type' => $request->type ?? '',
                'size' => $request->size ?? '',
                'color' => $request->color ?? '',
                'image_01' => $image01Name,
                'image_02' => $image02Name,
                'image_03' => $image03Name,
            ]);

            return redirect()->route('admin.products.index')
                           ->with('success', 'New product added!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to add product: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $product = Product::findOrFail($id);

            // Delete images from storage
            $imagePath01 = public_path('uploaded_img/' . $product->image_01);
            $imagePath02 = public_path('uploaded_img/' . $product->image_02);
            $imagePath03 = public_path('uploaded_img/' . $product->image_03);

            if (File::exists($imagePath01)) File::delete($imagePath01);
            if (File::exists($imagePath02)) File::delete($imagePath02);
            if (File::exists($imagePath03)) File::delete($imagePath03);

            // Delete the product
            // Note: If you have cart and wishlist tables, you should also delete related records
            // You can add these relationships in the Product model and use cascade delete
            $product->delete();

            return redirect()->route('admin.products.index')
                           ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $product = Product::findOrFail($id);
        return view('admin.update_product', compact('product'));
    }

    /**
     * Update the specified product in storage
     */
    public function update(Request $request, $id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:9999999999',
            'details' => 'required|string|max:500',
            'type' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:50',
            'image_01' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_02' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_03' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);

            // Update basic fields
            $product->name = $request->name;
            $product->price = $request->price;
            $product->details = $request->details;
            $product->type = $request->type ?? '';
            $product->size = $request->size ?? '';
            $product->color = $request->color ?? '';

            // Update images if new ones are uploaded
            if ($request->hasFile('image_01')) {
                // Delete old image
                $oldImagePath = public_path('uploaded_img/' . $product->image_01);
                if (File::exists($oldImagePath)) File::delete($oldImagePath);
                
                // Upload new image
                $image01Name = time() . '_1_' . $request->file('image_01')->getClientOriginalName();
                $request->file('image_01')->move(public_path('uploaded_img'), $image01Name);
                $product->image_01 = $image01Name;
            }

            if ($request->hasFile('image_02')) {
                $oldImagePath = public_path('uploaded_img/' . $product->image_02);
                if (File::exists($oldImagePath)) File::delete($oldImagePath);
                
                $image02Name = time() . '_2_' . $request->file('image_02')->getClientOriginalName();
                $request->file('image_02')->move(public_path('uploaded_img'), $image02Name);
                $product->image_02 = $image02Name;
            }

            if ($request->hasFile('image_03')) {
                $oldImagePath = public_path('uploaded_img/' . $product->image_03);
                if (File::exists($oldImagePath)) File::delete($oldImagePath);
                
                $image03Name = time() . '_3_' . $request->file('image_03')->getClientOriginalName();
                $request->file('image_03')->move(public_path('uploaded_img'), $image03Name);
                $product->image_03 = $image03Name;
            }

            $product->save();

            return redirect()->route('admin.products.index')
                           ->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
