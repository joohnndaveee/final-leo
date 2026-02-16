<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerSubscription;
use App\Models\SellerPayment;
use App\Models\SellerChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SellerSubscriptionController extends Controller
{
    private const MONTHLY_RENT_DEFAULT = 500.00;
    /**
     * Get subscription details for a seller
     */
    public function show()
    {
        // Subscription & billing is shown in seller settings.
        return redirect()->to(route('seller.settings') . '#subscription');
    }

    /**
     * Store a new subscription
     */
    public function store(Request $request)
    {
        // Wallet-only payments: subscription is activated by paying via wallet.
        return redirect()
            ->route('seller.wallet.pay-rent.form')
            ->with('info', 'Please pay your monthly rent via wallet to activate your subscription.');
    }

    /**
     * Update subscription
     */
    public function update(Request $request, $sellerId, $subscriptionId)
    {
        $seller = Seller::findOrFail($sellerId);
        $subscription = SellerSubscription::findOrFail($subscriptionId);

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

    /**
     * Toggle notification for seller
     * Used by admin to notify seller about upcoming/overdue payments
     */
    public function toggleNotification(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);
        
        // Check if user is admin
        if (auth('admin')->check()) {
            // Send notification email
            $this->sendPaymentNotification($seller);

            $seller->update([
                'payment_notification_sent' => true,
            ]);

            $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
            $rentAmount = number_format((float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT), 2);
            $endDateStr = $seller->subscription_end_date ? $seller->subscription_end_date->format('M d, Y') : 'N/A';
            SellerChat::create([
                'seller_id' => $seller->id,
                'message' => "Hello {$name}!\n\nReminder: your subscription payment is due.\nDue date: {$endDateStr}\nAmount: ₱{$rentAmount}\nAction: Wallet → Pay Monthly Rent",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);

            return redirect()->back()->with('success', 'Payment reminder sent to seller');
        }

        return redirect()->back()->with('error', 'Unauthorized');
    }

    /**
     * Mark subscription as paid
     * Used by admin to activate subscription after payment confirmation
     */
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

        // Record payment
        SellerPayment::create([
            'seller_id' => $sellerId,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->amount,
            'payment_method' => 'manual',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update subscription
        $currentEnd = \Carbon\Carbon::parse($subscription->end_date);
        $baseDate = $currentEnd->greaterThan(now()) ? $currentEnd : now();
        $newEndDate = $this->calculateEndDate($baseDate, $subscription->subscription_type);

        $subscription->update([
            'status' => 'active',
            'end_date' => $newEndDate,
        ]);

        // Update seller - always activate subscription on payment
        $seller->update([
            'subscription_status' => 'active',
            'subscription_end_date' => $subscription->end_date,
            'last_payment_date' => now(),
            'payment_notification_sent' => false,
        ]);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        SellerChat::create([
            'seller_id' => $seller->id,
            'message' => "Hello {$name}!\n\nYour subscription has been marked as paid.\nActive until: " . \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') . "\n\nThank you!",
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Subscription activated. Payment recorded.');
    }

    /**
     * Disable/suspend seller account
     * Used by admin to suspend seller due to non-payment or other reasons
     */
    public function disableSeller(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);

        if (!auth('admin')->check()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Validate suspension reason
        $request->validate([
            'suspension_reason' => 'required|string|max:255',
            'suspension_notes' => 'nullable|string|max:1000',
        ]);

        // Suspend subscription with reason and notes
        $seller->update([
            'subscription_status' => 'suspended',
            'suspension_reason' => $request->suspension_reason,
            'suspension_notes' => $request->suspension_notes,
            'suspended_by' => auth('admin')->id(),
            'suspended_at' => now(),
        ]);

        // Send suspension email
        $this->sendSuspensionNotification($seller);

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        $reason = $seller->suspension_reason ?? 'Administrative Action';
        $notes = $seller->suspension_notes ? "\nNotes: {$seller->suspension_notes}" : '';

        $message = "Hello {$name}!\nYour seller account is currently suspended.\n\n\"{$reason}\"{$notes}";
        if ($reason === 'Overdue Payment') {
            $rentAmount = number_format((float) ($seller->monthly_rent ?? self::MONTHLY_RENT_DEFAULT), 2);
            $message .= "\n\nTo restore access:\n- Amount: ₱{$rentAmount}\n- Action: Wallet → Pay Monthly Rent";
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

    /**
     * Unsuspend/reactivate seller subscription
     * Used by admin to reactivate a suspended subscription
     */
    public function unsuspendSeller(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);

        if (!auth('admin')->check()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Check if seller has a valid subscription
        $subscription = $seller->sellerSubscriptions()->latest()->first();
        $hasActiveSubscription = $subscription && $subscription->end_date >= now();

        // Only update subscription status, not seller approval status
        // Also clear suspension details
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

    /**
     * Calculate end date based on subscription type
     */
    private function calculateEndDate($startDate, $type)
    {
        switch ($type) {
            case 'monthly':
                return \Carbon\Carbon::parse($startDate)->addMonth();
            case 'quarterly':
                return \Carbon\Carbon::parse($startDate)->addMonths(3);
            case 'yearly':
                return \Carbon\Carbon::parse($startDate)->addYear();
            default:
                return \Carbon\Carbon::parse($startDate)->addMonth();
        }
    }

    /**
     * Send payment reminder email to seller
     */
    private function sendPaymentNotification(Seller $seller)
    {
        try {
            // Send email notification
            // This is a placeholder - configure actual email sending in your Mail class
            $subject = 'Payment Reminder: Monthly Shop Rent Due';
            $message = "Dear {$seller->shop_name},\n\n";
            $message .= "Your monthly shop rent of ₱" . number_format($seller->monthly_rent, 2) . " is due.\n";
            $message .= "Please make your payment to continue selling.\n\n";
            $message .= "Payment Details:\nDue Date: " . $seller->subscription_end_date . "\n";
            $message .= "Amount: ₱" . number_format($seller->monthly_rent, 2) . "\n\n";
            $message .= "Please log in to your seller dashboard to pay via wallet or contact support.\n\n";
            $message .= "Thank you,\nShop Management";

            // Queue the email for background sending
            // Mail::to($seller->email)->queue(new PaymentReminder($seller));
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Payment notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send account suspension email to seller
     * Message varies based on suspension reason
     */
    private function sendSuspensionNotification(Seller $seller)
    {
        try {
            $reason = $seller->suspension_reason ?? 'Administrative Action';
            $subject = 'Account Suspended: ' . $reason;

            $message = "Dear {$seller->shop_name},\n\n";
            $message .= "Your seller account has been suspended.\n\n";
            $message .= "Reason: {$reason}\n";

            if ($seller->suspension_notes) {
                $message .= "Details: {$seller->suspension_notes}\n\n";
            }

            if ($reason === 'Overdue Payment') {
                $message .= "Outstanding Amount: ₱" . number_format($seller->monthly_rent ?? 0, 2) . "\n\n";
                $message .= "To restore your account and continue selling, please:\n";
                $message .= "1. Log in to your seller dashboard\n";
                $message .= "2. Go to Subscription & Billing\n";
                $message .= "3. Pay the outstanding amount\n\n";
            } else {
                $message .= "Please contact the administrator for more information about resolving this suspension.\n\n";
            }

            $message .= "If you have any questions, please contact support.\n\n";
            $message .= "Thank you,\nShop Management";

            // Mail::to($seller->email)->queue(new SuspensionNotice($seller));
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Suspension notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
