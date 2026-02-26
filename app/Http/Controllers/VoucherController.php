<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $seller   = Auth::guard('seller')->user();
        $vouchers = Voucher::where('seller_id', $seller->id)
                           ->withCount('usages')
                           ->orderByDesc('created_at')
                           ->paginate(20);

        return view('seller.vouchers', compact('seller', 'vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'               => 'required|string|max:50|unique:vouchers,code',
            'type'               => 'required|in:percentage,fixed',
            'value'              => 'required|numeric|min:0',
            'min_order_amount'   => 'nullable|numeric|min:0',
            'max_discount_amount'=> 'nullable|numeric|min:0',
            'usage_limit'        => 'nullable|integer|min:1',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'description'        => 'nullable|string|max:255',
        ]);

        $seller = Auth::guard('seller')->user();

        Voucher::create([
            'seller_id'           => $seller->id,
            'code'                => strtoupper($request->code),
            'description'         => $request->description,
            'type'                => $request->type,
            'value'               => $request->value,
            'min_order_amount'    => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit'         => $request->usage_limit,
            'used_count'          => 0,
            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
            'is_active'           => true,
        ]);

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher created.');
    }

    public function update(Request $request, $id)
    {
        $seller  = Auth::guard('seller')->user();
        $voucher = Voucher::where('seller_id', $seller->id)->findOrFail($id);

        $request->validate([
            'code'               => 'required|string|max:50|unique:vouchers,code,' . $id,
            'type'               => 'required|in:percentage,fixed',
            'value'              => 'required|numeric|min:0',
            'min_order_amount'   => 'nullable|numeric|min:0',
            'max_discount_amount'=> 'nullable|numeric|min:0',
            'usage_limit'        => 'nullable|integer|min:1',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'description'        => 'nullable|string|max:255',
        ]);

        $voucher->update([
            'code'                => strtoupper($request->code),
            'description'         => $request->description,
            'type'                => $request->type,
            'value'               => $request->value,
            'min_order_amount'    => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit'         => $request->usage_limit,
            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
        ]);

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher updated.');
    }

    public function toggle($id)
    {
        $seller  = Auth::guard('seller')->user();
        $voucher = Voucher::where('seller_id', $seller->id)->findOrFail($id);
        $voucher->update(['is_active' => !$voucher->is_active]);

        return response()->json(['success' => true, 'is_active' => $voucher->is_active]);
    }

    public function destroy($id)
    {
        $seller  = Auth::guard('seller')->user();
        $voucher = Voucher::where('seller_id', $seller->id)->findOrFail($id);
        $voucher->delete();

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher deleted.');
    }

    /**
     * Validate a voucher code at checkout (AJAX)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code'        => 'required|string',
            'order_total' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher || !$voucher->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired voucher code.']);
        }

        // Check if user already used this voucher
        if (Auth::check()) {
            $alreadyUsed = VoucherUsage::where('voucher_id', $voucher->id)
                                       ->where('user_id', Auth::id())
                                       ->exists();
            if ($alreadyUsed) {
                return response()->json(['valid' => false, 'message' => 'You have already used this voucher.']);
            }
        }

        $discount = $voucher->computeDiscount((float) $request->order_total);

        return response()->json([
            'valid'           => true,
            'voucher_id'      => $voucher->id,
            // Keep both keys for compatibility with older frontend code.
            'discount'        => $discount,
            'discount_amount' => $discount,
            'message'         => "Voucher applied! You save ₱" . number_format($discount, 2),
        ]);
    }
}
