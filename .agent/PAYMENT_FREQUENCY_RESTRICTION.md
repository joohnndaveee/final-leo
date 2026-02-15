# Payment Frequency Restriction - One Payment Per Month

## Overview
Sellers can now only pay their monthly subscription once per billing period. They cannot make multiple payments in the same month.

## Payment Eligibility Rules

### When Can Sellers Pay?
Sellers can ONLY pay when:
1. **Subscription has expired** (end_date is in the past), OR
2. **Subscription is expiring within 7 days** (end_date is within the next 7 days)

### When Can't Sellers Pay?
Sellers CANNOT pay when:
- Subscription is still active with more than 7 days remaining

## Implementation Details

### 1. Backend Validation (WalletController@payRent)
**Location:** `app/Http/Controllers/WalletController.php`

**Check Added:**
```php
// Check if subscription is already active and not expiring soon
if ($subscription->end_date && $subscription->end_date > now()->addDays(7)) {
    $daysLeft = now()->diffInDays($subscription->end_date);
    return redirect()->route('seller.wallet.index')
        ->with('error', "Your subscription is still active for {$daysLeft} days. You can only pay when your subscription expires or is within 7 days of expiration.");
}
```

**Result:** Even if seller tries to bypass frontend, backend will reject the payment.

### 2. Frontend Display (WalletController@showPayRentForm)
**Location:** `app/Http/Controllers/WalletController.php`

**Flag Added:**
```php
// Can only pay if subscription expired or expiring within 7 days
$canPayNow = !$subscription->end_date || $subscription->end_date <= now()->addDays(7);
```

**Result:** View receives `$canPayNow` flag to show/hide payment button.

### 3. UI Changes (pay-rent.blade.php)
**Location:** `resources/views/seller/wallet/pay-rent.blade.php`

**Three States:**

#### State 1: Not Eligible to Pay (More than 7 days remaining)
```blade
@if (!$canPayNow)
    <div class="alert alert-info">
        <strong>Payment Not Available Yet</strong>
        Your subscription is still active. You can only pay when your subscription expires or is within 7 days of expiration.
        Current expiration: Feb 28, 2026 (15 days remaining)
    </div>
@endif
```
**Shows:** Info message with days remaining  
**Hides:** Payment button

#### State 2: Eligible but Insufficient Balance
```blade
@elseif ($wallet->balance < $subscription->amount)
    <div class="alert alert-warning">
        <strong>Insufficient Balance!</strong>
        You need $250.00 more to pay the rent.
        <a href="deposit">Add Funds Now</a>
    </div>
@endif
```
**Shows:** Warning with link to deposit  
**Hides:** Payment button

#### State 3: Eligible and Sufficient Balance
```blade
@else
    <form action="pay-rent" method="POST">
        <button type="button">Confirm Payment</button>
    </form>
@endif
```
**Shows:** Payment button  
**Action:** Allows payment

## User Experience Flow

### Scenario 1: Active Subscription (20 days remaining)
1. Seller goes to "Pay Monthly Rent"
2. **Sees:** Blue info box saying "Payment Not Available Yet"
3. **Message:** "Your subscription is still active for 20 days. You can only pay when your subscription expires or is within 7 days of expiration."
4. **No payment button shown**

### Scenario 2: Expiring Soon (5 days remaining)
1. Seller goes to "Pay Monthly Rent"
2. **Sees:** Payment summary and balance check
3. **If balance sufficient:** Payment button appears
4. **If balance insufficient:** "Add Funds Now" button appears
5. **Can pay and renew subscription**

### Scenario 3: Expired Subscription
1. Seller goes to "Pay Monthly Rent"
2. **Sees:** Red "Payment Overdue!" alert
3. **Payment button available** (if balance sufficient)
4. **Can pay immediately**

### Scenario 4: Tries to Pay Too Early (Bypass Attempt)
1. Seller somehow submits payment form (e.g., browser dev tools)
2. **Backend rejects:** "Your subscription is still active for X days..."
3. **Redirected to wallet** with error message
4. **No payment processed**

## Timeline Example

```
Day 1: Seller pays → Subscription active until Day 30
Day 10: Tries to pay → ❌ Blocked (20 days remaining)
Day 20: Tries to pay → ❌ Blocked (10 days remaining)
Day 24: Tries to pay → ✅ Allowed (6 days remaining - within 7-day window)
Day 24: Pays → Subscription extended to Day 54
Day 31: Old expiration date passed, but subscription active until Day 54
```

## Benefits

✅ **Prevents Double Payments**
- Sellers can't accidentally pay twice in one month
- No refund requests for duplicate payments

✅ **Predictable Billing**
- One payment per month maximum
- Clear payment windows

✅ **Early Payment Option**
- 7-day grace period before expiration
- Sellers can pay early to avoid interruption

✅ **Backend Protection**
- Even if UI is bypassed, backend validates
- Double protection against abuse

## Configuration

### Adjustable Parameters

**Early Payment Window (Currently 7 days):**
```php
// In WalletController.php
$canPayNow = $subscription->end_date <= now()->addDays(7);
```

To change to 3 days:
```php
$canPayNow = $subscription->end_date <= now()->addDays(3);
```

To change to 14 days:
```php
$canPayNow = $subscription->end_date <= now()->addDays(14);
```

## Testing Checklist

- [ ] Seller with 20 days remaining cannot pay
- [ ] Seller with 5 days remaining can pay
- [ ] Seller with expired subscription can pay
- [ ] Payment button hidden when not eligible
- [ ] Info message shows correct days remaining
- [ ] Backend rejects payment if not eligible
- [ ] After payment, next payment blocked until 7 days before new expiration
- [ ] Wallet index shows subscription status correctly

## Files Modified

1. ✅ `app/Http/Controllers/WalletController.php`
   - Added payment eligibility check in `payRent()`
   - Added `$canPayNow` flag in `showPayRentForm()`
   - Added `$subscription` to wallet index

2. ✅ `resources/views/seller/wallet/pay-rent.blade.php`
   - Added three-state conditional display
   - Shows info message when not eligible
   - Hides payment button when not eligible

## Summary

Sellers can now only pay once per billing period, with a 7-day early payment window before expiration. This prevents duplicate payments while still allowing sellers to pay early to avoid service interruption.
