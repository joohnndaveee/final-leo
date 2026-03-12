<?php

namespace App\Http\Controllers;

use App\Models\SellerChat;
use App\Models\SellerPayment;
use App\Models\SellerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerRentPaymentController extends Controller
{
    private const MONTHLY_RENT_DEFAULT = 500.00;

    public function showForm()
    {
        $seller = Auth::guard('seller')->user();
        if (!$seller) {
            return redirect()->route('seller.login');
        }

        $subscription = $seller->sellerSubscriptions()->latest()->first();
        if (!$subscription) {
            $amount = (float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT);
            $subscription = SellerSubscription::create([
                'seller_id' => $seller->id,
                'subscription_type' => 'monthly',
                'amount' => $amount,
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->subDay()->toDateString(),
                'status' => 'expired',
                'auto_renew' => true,
            ]);

            $seller->update([
                'subscription_status' => 'expired',
                'subscription_end_date' => $subscription->end_date,
                'monthly_rent' => $amount,
            ]);
        }

        $amount = (float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT);
        if ((float) $subscription->amount !== $amount) {
            $subscription->update(['amount' => $amount]);
        }

        $pendingPayment = $seller->sellerPayments()
            ->where('payment_type', 'subscription')
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        $daysUntilExpiry = now()->diffInDays($subscription->end_date, false);
        $isOverdue = $daysUntilExpiry < 0;

        $adminGcashName = (string) env('ADMIN_GCASH_NAME', 'Admin');
        $adminGcashNumber = (string) env('ADMIN_GCASH_NUMBER', '09xxxxxxxxx');
        $adminGcashQrImage = (string) env('ADMIN_GCASH_QR_IMAGE', 'images/gcash_qr.svg');
        $adminGcashQrUrl = asset($adminGcashQrImage);

        return view('seller.wallet.pay-rent', compact(
            'seller',
            'subscription',
            'pendingPayment',
            'isOverdue',
            'adminGcashName',
            'adminGcashNumber',
            'adminGcashQrUrl'
        ));
    }

    public function submit(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        if (!$seller) {
            return redirect()->route('seller.login');
        }

        if ($seller->subscription_status === 'suspended' && ($seller->suspension_reason ?? '') !== 'Overdue Payment') {
            return redirect()->back()->with('error', 'Your account is suspended. Please contact the administrator.');
        }

        $existingPending = $seller->sellerPayments()
            ->where('payment_type', 'subscription')
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if ($existingPending) {
            return redirect()->route('seller.subscription.payment-receipt', ['payment' => $existingPending->id])
                ->with('info', 'You already have a pending rent payment. Please wait for admin verification.');
        }

        $validated = $request->validate([
            'gcash_number_used' => ['required', 'regex:/^[0-9]+$/', 'max:30'],
            'reference_number' => 'required|string|max:100',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'notes' => 'nullable|string|max:1000',
        ], [
            'gcash_number_used.regex' => 'GCash number must contain digits only.',
        ]);

        $subscription = $seller->sellerSubscriptions()->latest()->first();
        if (!$subscription) {
            $amount = (float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT);
            $subscription = SellerSubscription::create([
                'seller_id' => $seller->id,
                'subscription_type' => 'monthly',
                'amount' => $amount,
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->subDay()->toDateString(),
                'status' => 'expired',
                'auto_renew' => true,
            ]);
        }

        $proofImageName = null;
        if ($request->hasFile('payment_proof')) {
            $proofImageName = time() . '_subscription_gcash_' . $request->file('payment_proof')->getClientOriginalName();
            $request->file('payment_proof')->move(public_path('uploaded_img'), $proofImageName);
        }

        $payment = SellerPayment::create([
            'seller_id' => $seller->id,
            'subscription_id' => $subscription->id,
            'payment_type' => 'subscription',
            'amount' => (float) ($seller->monthly_rent ?? $subscription->amount ?? self::MONTHLY_RENT_DEFAULT),
            'payment_method' => 'gcash',
            'payment_status' => 'pending',
            'reference_number' => $validated['reference_number'],
            'gcash_number_used' => $validated['gcash_number_used'],
            'proof_image' => $proofImageName,
            'notes' => $validated['notes'] ?? 'Monthly rent payment submitted via GCash. Awaiting admin verification.',
        ]);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        SellerChat::create([
            'seller_id' => $seller->id,
            'message' => "Hello {$name}!\n\nWe received your monthly rent payment submission.\nReference: {$payment->reference_number}\nAmount: PHP " . number_format((float) $payment->amount, 2) . "\nStatus: Pending verification\n\nWe will notify you once this is approved.",
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return redirect()->route('seller.subscription.payment-receipt', ['payment' => $payment->id])
            ->with('success', 'Payment proof submitted. Please wait for admin verification.');
    }

    public function receipt($paymentId)
    {
        $seller = Auth::guard('seller')->user();
        if (!$seller) {
            return redirect()->route('seller.login');
        }

        $payment = SellerPayment::where('seller_id', $seller->id)
            ->where('id', $paymentId)
            ->firstOrFail();

        $subscription = $payment->subscription ?? $seller->sellerSubscriptions()->latest()->first();

        return view('seller.wallet.payment-receipt', compact('seller', 'payment', 'subscription'));
    }
}
