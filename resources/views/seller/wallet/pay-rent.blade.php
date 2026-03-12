@extends('layouts.seller')

@section('title', 'Pay Monthly Rent')

@section('content')
<div style="max-width:760px;margin:1.5rem auto;">
    <h2 style="margin-bottom:.25rem;"><i class="fas fa-money-bill-wave"></i> Pay Monthly Rent via GCash</h2>
    <p style="margin-top:0;color:#6b7280;">Submit your GCash payment proof. Admin will verify and activate your subscription.</p>

    @if (session('success'))
        <div style="background:#dcfce7;color:#166534;padding:.85rem 1rem;border-radius:10px;margin-bottom:1rem;">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div style="background:#dbeafe;color:#1e40af;padding:.85rem 1rem;border-radius:10px;margin-bottom:1rem;">{{ session('info') }}</div>
    @endif
    @if (session('error'))
        <div style="background:#fee2e2;color:#991b1b;padding:.85rem 1rem;border-radius:10px;margin-bottom:1rem;">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:.85rem 1rem;border-radius:10px;margin-bottom:1rem;">
            <ul style="margin:.2rem 0 0 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;margin-bottom:1rem;">
        <p style="margin:.2rem 0;"><strong>Monthly Rent:</strong> PHP {{ number_format((float) $subscription->amount, 2) }}</p>
        <p style="margin:.2rem 0;"><strong>Subscription Status:</strong> {{ ucfirst((string) $seller->subscription_status) }}</p>
        <p style="margin:.2rem 0;"><strong>Current End Date:</strong> {{ $subscription->end_date?->format('M d, Y') ?? 'N/A' }}</p>
        @if ($isOverdue)
            <p style="margin:.5rem 0 0;color:#991b1b;"><strong>Payment is overdue.</strong> Submit proof to restore selling access.</p>
        @endif
    </div>

    @if ($pendingPayment)
        <div style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:1rem;border-radius:12px;">
            <p style="margin:0 0 .4rem;"><strong>Pending payment already submitted.</strong></p>
            <p style="margin:0 0 .7rem;">Reference: {{ $pendingPayment->reference_number }} | Submitted: {{ $pendingPayment->created_at?->format('M d, Y h:i A') }}</p>
            <a href="{{ route('seller.subscription.payment-receipt', ['payment' => $pendingPayment->id]) }}" style="display:inline-block;padding:.6rem .9rem;background:#92400e;color:#fff;border-radius:8px;text-decoration:none;">View Submission</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;align-items:start;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
                <h4 style="margin-top:0;">Admin GCash Details</h4>
                <p style="margin:.3rem 0;"><strong>Name:</strong> {{ $adminGcashName }}</p>
                <p style="margin:.3rem 0;"><strong>Number:</strong> {{ $adminGcashNumber }}</p>
                <img src="{{ $adminGcashQrUrl }}" alt="Admin GCash QR" style="width:100%;max-width:260px;border:1px solid #e5e7eb;border-radius:10px;margin-top:.5rem;">
            </div>

            <form action="{{ route('seller.subscription.pay-rent.submit') }}" method="POST" enctype="multipart/form-data" style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
                @csrf
                <h4 style="margin-top:0;">Submit Payment Proof</h4>
                <div style="margin-bottom:.75rem;">
                    <label for="gcash_number_used">GCash Number You Used</label>
                    <input id="gcash_number_used" name="gcash_number_used" value="{{ old('gcash_number_used', $seller->gcash_number_used) }}" required style="width:100%;padding:.65rem;border:1px solid #d1d5db;border-radius:8px;">
                </div>
                <div style="margin-bottom:.75rem;">
                    <label for="reference_number">Reference Number</label>
                    <input id="reference_number" name="reference_number" value="{{ old('reference_number') }}" required style="width:100%;padding:.65rem;border:1px solid #d1d5db;border-radius:8px;">
                </div>
                <div style="margin-bottom:.75rem;">
                    <label for="payment_proof">Proof Screenshot</label>
                    <input id="payment_proof" name="payment_proof" type="file" accept="image/*" required style="width:100%;padding:.5rem;border:1px solid #d1d5db;border-radius:8px;">
                </div>
                <div style="margin-bottom:.9rem;">
                    <label for="notes">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" style="width:100%;padding:.65rem;border:1px solid #d1d5db;border-radius:8px;">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" style="width:100%;padding:.75rem;border:0;border-radius:8px;background:#059669;color:#fff;font-weight:700;cursor:pointer;">Submit for Verification</button>
            </form>
        </div>
    @endif
</div>
@endsection

