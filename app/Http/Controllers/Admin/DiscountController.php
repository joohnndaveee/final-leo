<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    private function activeAdminDiscount()
    {
        $now = now();

        return Discount::query()
            ->whereNull('seller_id')
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->orderByDesc('start_date')
            ->orderByDesc('created_at')
            ->first();
    }

    private function deactivateOtherAdminDiscounts(?int $exceptId = null): void
    {
        Discount::query()
            ->whereNull('seller_id')
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    public function index()
    {
        $discounts = Discount::whereNull('seller_id')
            ->orderByDesc('created_at')
            ->paginate(20);

        $settings = SiteSetting::find(1);
        $activeDiscount = $this->activeAdminDiscount();

        return view('admin.discounts', compact('discounts', 'settings', 'activeDiscount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:percentage,fixed',
            'value'       => 'required|numeric|min:0',
            'min_price'   => 'nullable|numeric|min:0',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
            'seasonal_banner_enabled'    => 'nullable|boolean',
            'seasonal_banner_bg_color'   => ['nullable', 'string', 'max:20', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'seasonal_banner_text_color' => ['nullable', 'string', 'max:20', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'seasonal_banner_message'    => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // Ensure only one active admin seasonal discount at a time.
            $this->deactivateOtherAdminDiscounts();

            Discount::create([
                'seller_id'    => null,
                'name'         => $request->name,
                'description'  => $request->description,
                'type'         => $request->type,
                'value'        => $request->value,
                'min_price'    => $request->min_price,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'is_active'    => true,
            ]);

            SiteSetting::updateOrCreate(
                ['id' => 1],
                [
                    'seasonal_banner_enabled'    => (bool) $request->boolean('seasonal_banner_enabled'),
                    'seasonal_banner_bg_color'   => $request->seasonal_banner_bg_color ?: null,
                    'seasonal_banner_text_color' => $request->seasonal_banner_text_color ?: null,
                    'seasonal_banner_message'    => $request->seasonal_banner_message ?: null,
                    'updated_by'                 => auth('admin')->id(),
                ]
            );
        });

        return redirect()->route('admin.discounts.index')->with('success', 'Discount created.');
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::whereNull('seller_id')->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:percentage,fixed',
            'value'       => 'required|numeric|min:0',
            'min_price'   => 'nullable|numeric|min:0',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $discount) {
            $payload = $request->only('name', 'description', 'type', 'value', 'min_price', 'start_date', 'end_date', 'is_active');

            $wantsActive = array_key_exists('is_active', $payload) && (bool) $payload['is_active'] === true;
            if ($wantsActive) {
                $this->deactivateOtherAdminDiscounts($discount->id);
            }

            $discount->update($payload);
        });

        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated.');
    }

    public function toggle($id)
    {
        $discount = Discount::whereNull('seller_id')->findOrFail($id);
        DB::transaction(function () use ($discount) {
            $next = !$discount->is_active;
            if ($next) {
                $this->deactivateOtherAdminDiscounts($discount->id);
            }
            $discount->update(['is_active' => $next]);
        });

        return response()->json(['success' => true, 'is_active' => $discount->is_active]);
    }

    public function destroy($id)
    {
        $discount = Discount::whereNull('seller_id')->findOrFail($id);

        $discount->products()->update(['discount_id' => null]);
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted.');
    }

    public function applyToProduct(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|integer',
            'discount_id' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->discount_id) {
            Discount::whereNull('seller_id')->findOrFail($request->discount_id);
        }

        $product->update(['discount_id' => $request->discount_id]);

        return response()->json(['success' => true]);
    }
}
