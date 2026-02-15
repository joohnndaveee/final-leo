<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\SellerPayment;
use App\Models\SellerSubscription;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WalletController extends Controller
{
    /**
     * Show seller wallet details
     */
    public function index()
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);
        $transactions = $seller->walletTransactions()
            ->latest()
            ->paginate(15);

        return view('seller.wallet.index', compact('seller', 'wallet', 'transactions'));
    }

    /**
     * Show deposit form
     */
    public function showDepositForm()
    {
        $seller = auth('seller')->user();
        
        if (!$seller) {
            return redirect('/seller/login');
        }

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);

        return view('seller.wallet.deposit', compact('seller', 'wallet'));
    }

    /**
     * Process wallet deposit
     */
    public function deposit(Request $request)
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:1000000',
            'payment_method' => 'required|in:bank_transfer,card,manual',
            'reference' => 'nullable|string|max:100',
        ]);

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);

        // Record the transaction
        try {
            $transaction = $wallet->deposit(
                $validated['amount'],
                'Deposit via ' . $validated['payment_method'],
                $validated['reference'] ?? null
            );

            return redirect()->route('seller.wallet.index')
                ->with('success', "Deposit of $" . number_format($validated['amount'], 2) . " successful!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Deposit failed: ' . $e->getMessage());
        }
    }

    /**
     * Show pay rent form
     */
    public function showPayRentForm()
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);
        $subscription = $seller->sellerSubscriptions()->latest()->first();

        if (!$subscription) {
            return redirect()->route('seller.wallet.index')
                ->with('error', 'No active subscription found');
        }

        $daysUntilExpiry = now()->diffInDays($subscription->end_date, false);
        $isOverdue = $daysUntilExpiry < 0;

        return view('seller.wallet.pay-rent', compact('seller', 'wallet', 'subscription', 'isOverdue'));
    }

    /**
     * Process rent payment from wallet
     */
    public function payRent(Request $request)
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $subscription = $seller->sellerSubscriptions()->latest()->first();

        if (!$subscription) {
            return redirect()->route('seller.wallet.index')
                ->with('error', 'No active subscription found');
        }

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);

        // Check if wallet has enough balance
        if (!$wallet->hasEnoughBalance($subscription->amount)) {
            return redirect()->back()
                ->with('error', "Insufficient wallet balance. You need $" . number_format($subscription->amount, 2) . 
                    " but only have $" . number_format($wallet->balance, 2));
        }

        try {
            // Deduct from wallet
            $wallet->payRent(
                $subscription->amount,
                'Monthly rent payment for subscription #' . $subscription->id,
                $subscription->id
            );

            // Record payment
            $payment = SellerPayment::create([
                'seller_id' => $seller->id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'payment_method' => 'wallet',
                'payment_status' => 'completed',
                'reference_number' => 'WALLET-' . time(),
                'paid_at' => now(),
            ]);

            // Renew subscription
            $newEndDate = now()->addMonth();
            $subscription->update([
                'status' => 'active',
                'end_date' => $newEndDate,
            ]);

            // Update seller
            $seller->update([
                'subscription_status' => 'active',
                'subscription_end_date' => $newEndDate,
                'last_payment_date' => now(),
                'payment_notification_sent' => false,
            ]);

            // Send confirmation email
            try {
                Mail::to($seller->email)->send(new PaymentConfirmation($payment, $seller));
            } catch (\Exception $e) {
                \Log::warning('Payment confirmation email failed: ' . $e->getMessage());
                // Don't fail the payment if email fails, just log it
            }

            return redirect()->route('seller.wallet.payment-receipt', [
                'payment' => $payment->id
            ])->with('success', "Payment of $" . number_format($subscription->amount, 2) . " successful!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Show payment receipt
     */
    public function showPaymentReceipt($paymentId)
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $payment = SellerPayment::where([
            ['id', '=', $paymentId],
            ['seller_id', '=', $seller->id]
        ])->firstOrFail();

        $subscription = $payment->subscription;

        return view('seller.wallet.payment-receipt', compact('seller', 'payment', 'subscription'));
    }

    /**
     * Show withdrawal form
     */
    public function showWithdrawalForm()
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);

        return view('seller.wallet.withdraw', compact('seller', 'wallet'));
    }

    /**
     * Process wallet withdrawal
     */
    public function withdraw(Request $request)
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return redirect('/seller/login');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_account' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
        ]);

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);

        // Check balance
        if (!$wallet->hasEnoughBalance($validated['amount'])) {
            return redirect()->back()
                ->with('error', "Insufficient balance. You have $" . number_format($wallet->balance, 2));
        }

        try {
            $wallet->withdraw(
                $validated['amount'],
                'Withdrawal to ' . $validated['bank_name'] . ' (' . $validated['bank_account'] . ')'
            );

            return redirect()->route('seller.wallet.index')
                ->with('success', "Withdrawal request of $" . number_format($validated['amount'], 2) . " submitted. Pending admin approval.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Withdrawal failed: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint: Get wallet balance and transactions
     */
    public function getBalance()
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $wallet = $seller->wallet ?? SellerWallet::create(['seller_id' => $seller->id]);

        return response()->json([
            'balance' => $wallet->balance,
            'total_deposited' => $wallet->total_deposited,
            'total_withdrawn' => $wallet->total_withdrawn,
            'updated_at' => $wallet->updated_at,
        ]);
    }

    /**
     * API endpoint: Get transaction history
     */
    public function getTransactions(Request $request)
    {
        $seller = auth('seller')->user();

        if (!$seller) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        $transactions = $seller->walletTransactions()
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json([
            'transactions' => $transactions,
            'total' => $seller->walletTransactions()->count(),
        ]);
    }
}
