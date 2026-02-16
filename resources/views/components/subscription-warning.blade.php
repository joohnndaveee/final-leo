@if (auth('seller')->check())
    @php
        $seller = auth('seller')->user();
        $subscription = $seller->sellerSubscriptions()->latest()->first();
        $isExpired = $subscription && $subscription->status === 'expired';
        $isSuspended = $seller->subscription_status === 'suspended';
        $daysUntilExpiry = 0;
        $showWarning = false;

        if ($subscription) {
            $daysUntilExpiry = now()->diffInDays($subscription->end_date, false);
            if ($daysUntilExpiry < 0) {
                $showWarning = true;
            } elseif ($daysUntilExpiry <= 7 && $daysUntilExpiry >= 0) {
                $showWarning = true;
            }
        }
    @endphp

    @if ($isSuspended || $showWarning)
    <style>
    .subscription-alert {
        max-width: 1200px;
        margin: 1.5rem auto 0;
        padding: 0 2rem;
    }
    .subscription-alert-inner {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.2rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .subscription-alert.suspended .subscription-alert-inner {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #fecaca;
    }
    .subscription-alert.warning .subscription-alert-inner {
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        border: 1px solid #fed7aa;
    }
    .subscription-alert-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .subscription-alert.suspended .subscription-alert-icon {
        background: #dc2626;
        color: white;
        font-size: 1.4rem;
    }
    .subscription-alert.warning .subscription-alert-icon {
        background: #ea580c;
        color: white;
        font-size: 1.4rem;
    }
    .subscription-alert-body {
        flex: 1;
        min-width: 0;
    }
    .subscription-alert-body strong {
        display: block;
        font-size: 1.5rem;
        margin-bottom: 0.3rem;
    }
    .subscription-alert.suspended .subscription-alert-body strong { color: #991b1b; }
    .subscription-alert.suspended .subscription-alert-body p { color: #7f1d1d; }
    .subscription-alert.warning .subscription-alert-body strong,
    .subscription-alert.warning .subscription-alert-body p { color: #9a3412; }
    .subscription-alert-body p {
        margin: 0;
        font-size: 1.35rem;
        line-height: 1.5;
    }
    .subscription-alert-notes {
        display: block;
        margin-top: 0.4rem;
        font-style: italic;
        opacity: 0.95;
    }
    .subscription-alert-actions {
        display: flex;
        gap: 0.75rem;
        flex-shrink: 0;
    }
    .subscription-alert-btn {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-size: 1.3rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .subscription-alert.suspended .subscription-alert-btn {
        background: #dc2626;
        color: white;
        border: none;
    }
    .subscription-alert.suspended .subscription-alert-btn:hover {
        background: #b91c1c;
    }
    .subscription-alert.suspended .subscription-alert-btn:not(.primary) {
        background: white;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }
    .subscription-alert.suspended .subscription-alert-btn:not(.primary):hover {
        background: #fef2f2;
    }
    .subscription-alert.warning .subscription-alert-btn.primary {
        background: #ea580c;
        color: white;
        border: none;
    }
    .subscription-alert.warning .subscription-alert-btn.primary:hover {
        background: #c2410c;
    }
    @media (max-width: 768px) {
        .subscription-alert {
            padding: 0 1.5rem;
        }
        .subscription-alert-inner {
            flex-wrap: wrap;
        }
        .subscription-alert-actions {
            width: 100%;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }
    }
</style>
    @endif

    @if ($isSuspended && !request()->routeIs('seller.violations'))
        <div class="subscription-alert suspended" role="alert">
            <div class="subscription-alert-inner">
                <div class="subscription-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="subscription-alert-body">
                    <strong>Account suspended</strong>
                    <p>Your seller account has been suspended due to <strong>{{ $seller->suspension_reason ?? 'Administrative Action' }}</strong>.
                    @if($seller->suspension_notes)
                        <span class="subscription-alert-notes">"{{ $seller->suspension_notes }}"</span>
                    @endif
                    @if(($seller->suspension_reason ?? '') === 'Overdue Payment')
                        Monthly rent of <strong>₱{{ number_format($seller->monthly_rent ?? 0, 2) }}</strong> is required to continue selling.
                    @else
                        Please contact the administrator for more information.
                    @endif
                    </p>
                </div>
                <div class="subscription-alert-actions">
                    @if(($seller->suspension_reason ?? '') === 'Overdue Payment')
                        <a href="{{ route('seller.wallet.pay-rent.form') }}" class="subscription-alert-btn primary">Pay Now</a>
                    @else
                        <a href="{{ route('seller.violations') }}" class="subscription-alert-btn primary">View Violation Details</a>
                    @endif
                </div>
            </div>
        </div>
    @elseif ($showWarning && $subscription)
        <div class="subscription-alert warning" role="alert">
            <div class="subscription-alert-inner">
                <div class="subscription-alert-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="subscription-alert-body">
                    @if ($daysUntilExpiry < 0)
                        <strong>Payment overdue</strong>
                        <p>Your monthly rent payment is overdue. Please pay immediately to continue selling.</p>
                    @else
                        <strong>Payment due soon</strong>
                        <p>Your subscription expires in {{ $daysUntilExpiry }} day{{ $daysUntilExpiry !== 1 ? 's' : '' }}. Please pay ₱{{ number_format($subscription->amount, 2) }} to continue.</p>
                    @endif
                </div>
                <div class="subscription-alert-actions">
                    <a href="{{ route('seller.wallet.pay-rent.form') }}" class="subscription-alert-btn primary">Pay Now</a>
                </div>
            </div>
        </div>
    @endif
@endif
