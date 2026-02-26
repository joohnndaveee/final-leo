<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $seller    = Auth::guard('seller')->user();
        $discounts = Discount::where('seller_id', $seller->id)
                             ->orderByDesc('created_at')
                             ->paginate(20);

        return view('seller.discounts', compact('seller', 'discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'type'       => 'required|in:percentage,fixed',
            'value'      => 'required|numeric|min:0',
            'min_price'  => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'description'=> 'nullable|string|max:500',
        ]);

        $seller = Auth::guard('seller')->user();

        Discount::create([
            'seller_id'   => $seller->id,
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => $request->type,
            'value'       => $request->value,
            'min_price'   => $request->min_price,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'is_active'   => true,
        ]);

        return redirect()->route('seller.discounts.index')->with('success', 'Discount created.');
    }

    public function update(Request $request, $id)
    {
        $seller   = Auth::guard('seller')->user();
        $discount = Discount::where('seller_id', $seller->id)->findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:100',
            'type'       => 'required|in:percentage,fixed',
            'value'      => 'required|numeric|min:0',
            'min_price'  => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'description'=> 'nullable|string|max:500',
        ]);

        $discount->update($request->only('name','description','type','value','min_price','start_date','end_date'));

        return redirect()->route('seller.discounts.index')->with('success', 'Discount updated.');
    }

    public function toggle($id)
    {
        $seller   = Auth::guard('seller')->user();
        $discount = Discount::where('seller_id', $seller->id)->findOrFail($id);
        $discount->update(['is_active' => !$discount->is_active]);

        return response()->json(['success' => true, 'is_active' => $discount->is_active]);
    }

    public function destroy($id)
    {
        $seller   = Auth::guard('seller')->user();
        $discount = Discount::where('seller_id', $seller->id)->findOrFail($id);

        // Detach from products first
        $discount->products()->update(['discount_id' => null]);
        $discount->delete();

        return redirect()->route('seller.discounts.index')->with('success', 'Discount deleted.');
    }

    /**
     * Apply or remove discount from product via AJAX
     */
    public function applyToProduct(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $request->validate([
            'product_id'  => 'required|integer',
            'discount_id' => 'nullable|integer',
        ]);

        $product = \App\Models\Product::where('seller_id', $seller->id)->findOrFail($request->product_id);

        // Verify discount belongs to seller if provided
        if ($request->discount_id) {
            Discount::where('seller_id', $seller->id)->findOrFail($request->discount_id);
        }

        $product->update(['discount_id' => $request->discount_id]);

        return response()->json(['success' => true]);
    }
}
