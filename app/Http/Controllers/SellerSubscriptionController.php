<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerChat;
use App\Models\SellerPayment;
use App\Models\SellerSubscription;
use Illuminate\Http\Request;

class SellerSubscriptionController extends Controller
{
    private const MONTHLY_RENT_DEFAULT = 500.00;

    public function show()
    {
        return redirect()->to(route('seller.settings') . '#subscription');
    }

    public function store(Request $request)
    {
        return redirect()
            ->route('seller.subscription.pay-rent.form')
            ->with('info', 'Please submit your monthly rent payment via GCash for admin verification.');
    }

    public function update(Request $request, $subscriptionId)
    {
        $seller = auth('seller')->user();
        if (!$seller) {
            return redirect()->route('seller.login');
        }
        $subscription = SellerSubscription::findOrFail($subscriptionId);
        if ((int) $subscription->seller_id !== (int) $seller->id) {
            abort(403);
        }

        $validated = $request->validate([
            'subscription_type' => 'in:monthly,quarterly,yearly',
            'amount' => 'numeric|min:0.01',
            'auto_renew' => 'boolean',
        ]);

        $subscription->update($validated);

        if (isset($validated['amount'])) {
            $seller->update(['monthly_rent' => $validated['amount']]);
        }

        return redirect()->back()->with('success', 'Subscription updated successfully');
    }

    public function toggleNotification(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);

        if (!auth('admin')->check()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $this->sendPaymentNotification($seller);

        $seller->update([
            'payment_notification_sent' => true,
        ]);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        $rentAmount = number_format((float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT), 2);
        $endDateStr = $seller->subscription_end_date ? $seller->subscription_end_date->format('M d, Y') : 'N/A';

        SellerChat::create([
            'seller_id' => $seller->id,
            'message' => "Hello {$name}!\n\nReminder: your subscription payment is due.\nDue date: {$endDateStr}\nAmount: PHP {$rentAmount}\nAction: Subscription -> Pay Monthly Rent (GCash)",
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Payment reminder sent to seller');
    }

    public function markAsPaid(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);

        if (!auth('admin')->check()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $subscription = $seller->sellerSubscriptions()->latest()->first();
        if (!$subscription) {
            return redirect()->back()->with('error', 'No subscription found');
        }

        $pendingPayment = $seller->sellerPayments()
            ->where('payment_type', 'subscription')
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if ($pendingPayment) {
            $pendingPayment->update([
                'subscription_id' => $subscription->id,
                'payment_status' => 'completed',
                'paid_at' => now(),
                'notes' => trim((string) (($pendingPayment->notes ?? '') . "\nApproved by admin on " . now()->format('Y-m-d H:i:s'))),
            ]);
        } else {
            SellerPayment::create([
                'seller_id' => $seller->id,
                'subscription_id' => $subscription->id,
                'payment_type' => 'subscription',
                'amount' => $subscription->amount,
                'payment_method' => 'manual',
                'payment_status' => 'completed',
                'paid_at' => now(),
                'notes' => 'Admin marked as paid without pending proof submission.',
            ]);
        }

        $currentEnd = \Carbon\Carbon::parse($subscription->end_date);
        $baseDate = $currentEnd->greaterThan(now()) ? $currentEnd : now();
        $newEndDate = $this->calculateEndDate($baseDate, $subscription->subscription_type);

        $subscription->update([
            'status' => 'active',
            'end_date' => $newEndDate,
        ]);

        $isOverdueSuspension = ($seller->suspension_reason ?? '') === 'Overdue Payment';
        $seller->update([
            'subscription_status' => 'active',
            'subscription_end_date' => $newEndDate,
            'last_payment_date' => now(),
            'payment_notification_sent' => false,
            'suspension_reason' => $isOverdueSuspension ? null : $seller->suspension_reason,
            'suspension_notes' => $isOverdueSuspension ? null : $seller->suspension_notes,
            'suspended_by' => $isOverdueSuspension ? null : $seller->suspended_by,
            'suspended_at' => $isOverdueSuspension ? null : $seller->suspended_at,
        ]);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        SellerChat::create([
            'seller_id' => $seller->id,
            'message' => "Hello {$name}!\n\nYour subscription payment was approved.\nActive until: " . \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') . "\n\nThank you!",
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Subscription activated. Payment recorded.');
    }

    public function disableSeller(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);

        if (!auth('admin')->check()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $request->validate([
            'suspension_reason' => 'required|string|max:255',
            'suspension_notes' => 'nullable|string|max:1000',
        ]);

        $seller->update([
            'subscription_status' => 'suspended',
            'suspension_reason' => $request->suspension_reason,
            'suspension_notes' => $request->suspension_notes,
            'suspended_by' => auth('admin')->id(),
            'suspended_at' => now(),
        ]);

        $this->sendSuspensionNotification($seller);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        $reason = $seller->suspension_reason ?? 'Administrative Action';
        $notes = $seller->suspension_notes ? "\nNotes: {$seller->suspension_notes}" : '';

        $message = "Hello {$name}!\nYour seller account is currently suspended.\n\n\"{$reason}\"{$notes}";
        if ($reason === 'Overdue Payment') {
            $rentAmount = number_format((float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT), 2);
            $message .= "\n\nTo restore access:\n- Amount: PHP {$rentAmount}\n- Action: Subscription -> Pay Monthly Rent (GCash)";
        } else {
            $message .= "\n\nIf you want to appeal, please reply here with your explanation and any proof.";
        }

        SellerChat::create([
            'seller_id' => $seller->id,
            'message' => $message,
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Seller subscription suspended: ' . $request->suspension_reason);
    }

    public function unsuspendSeller(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);

        if (!auth('admin')->check()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $subscription = $seller->sellerSubscriptions()->latest()->first();
        $hasActiveSubscription = $subscription && $subscription->end_date >= now();

        $seller->update([
            'subscription_status' => $hasActiveSubscription ? 'active' : 'expired',
            'suspension_reason' => null,
            'suspension_notes' => null,
            'suspended_by' => null,
            'suspended_at' => null,
        ]);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        $status = $hasActiveSubscription ? 'active' : 'expired';
        SellerChat::create([
            'seller_id' => $seller->id,
            'message' => "Hello {$name}!\n\nYour seller account has been reactivated.\nCurrent subscription status: {$status}.",
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Seller subscription reactivated');
    }

    private function calculateEndDate($startDate, $type)
    {
        return match ($type) {
            'quarterly' => \Carbon\Carbon::parse($startDate)->addMonths(3),
            'yearly' => \Carbon\Carbon::parse($startDate)->addYear(),
            default => \Carbon\Carbon::parse($startDate)->addMonth(),
        };
    }

    private function sendPaymentNotification(Seller $seller): bool
    {
        try {
            return true;
        } catch (\Exception $e) {
            \Log::error('Payment notification failed: ' . $e->getMessage());
            return false;
        }
    }

    private function sendSuspensionNotification(Seller $seller): bool
    {
        try {
            return true;
        } catch (\Exception $e) {
            \Log::error('Suspension notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
