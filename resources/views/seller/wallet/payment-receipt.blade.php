@extends('layouts.seller')

@section('title', 'Subscription Payment')

@section('content')
<div style="max-width:760px;margin:1.5rem auto;">
    <h2 style="margin-bottom:.25rem;"><i class="fas fa-receipt"></i> Subscription Payment</h2>
    <p style="margin-top:0;color:#6b7280;">Track your monthly rent payment submission and approval status.</p>

    @php
        $isCompleted = $payment->payment_status === 'completed';
    @endphp

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
        @if ($isCompleted)
            <div style="background:#dcfce7;color:#166534;padding:.8rem 1rem;border-radius:8px;margin-bottom:1rem;">
                <strong>Approved:</strong> Your monthly rent payment has been verified and your subscription is active.
            </div>
        @else
            <div style="background:#fffbeb;color:#92400e;padding:.8rem 1rem;border-radius:8px;margin-bottom:1rem;">
                <strong>Pending:</strong> Your payment proof is waiting for admin verification.
            </div>
        @endif

        <p><strong>Payment Type:</strong> {{ ucfirst((string) ($payment->payment_type ?? 'subscription')) }}</p>
        <p><strong>Status:</strong> {{ ucfirst((string) $payment->payment_status) }}</p>
        <p><strong>Amount:</strong> PHP {{ number_format((float) $payment->amount, 2) }}</p>
        <p><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', (string) $payment->payment_method)) }}</p>
        <p><strong>Reference:</strong> {{ $payment->reference_number ?? '-' }}</p>
        <p><strong>GCash Number Used:</strong> {{ $payment->gcash_number_used ?? '-' }}</p>
        <p><strong>Submitted At:</strong> {{ $payment->created_at?->format('M d, Y h:i A') ?? '-' }}</p>
        <p><strong>Approved At:</strong> {{ $payment->paid_at?->format('M d, Y h:i A') ?? '-' }}</p>

        @if (!empty($payment->proof_image))
            <p><strong>Proof Screenshot:</strong> <a href="{{ asset('uploaded_img/' . $payment->proof_image) }}" target="_blank">View uploaded proof</a></p>
        @endif

        @if ($subscription)
            <hr style="border:0;border-top:1px solid #e5e7eb;margin:1rem 0;">
            <p><strong>Subscription Type:</strong> {{ ucfirst((string) $subscription->subscription_type) }}</p>
            <p><strong>Subscription End Date:</strong> {{ $subscription->end_date?->format('M d, Y') ?? '-' }}</p>
        @endif

        <div style="margin-top:1.25rem;display:flex;gap:.6rem;flex-wrap:wrap;">
            <a href="{{ route('seller.subscription.pay-rent.form') }}" style="text-decoration:none;padding:.7rem 1rem;border-radius:8px;background:#2563eb;color:#fff;">Back to Rent Payment</a>
            <a href="{{ route('seller.settings') }}" style="text-decoration:none;padding:.7rem 1rem;border-radius:8px;background:#059669;color:#fff;">Open Settings</a>
        </div>
    </div>
</div>
@endsection

