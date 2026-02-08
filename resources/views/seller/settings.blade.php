@extends('layouts.seller')

@section('title', 'Shop Settings - U-KAY HUB')

@push('styles')
<style>
    .seller-apply-section {
        padding: 3rem 2rem;
        max-width: 800px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .seller-apply-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
    }

    .seller-apply-card h1 {
        font-size: 2.4rem;
        color: #27ae60;
        margin-bottom: 0.5rem;
    }

    .seller-apply-card p {
        font-size: 1.4rem;
        color: #666;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 0.6rem;
        font-weight: 600;
    }

    .form-group label span {
        color: #e74c3c;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 1.1rem;
        font-size: 1.4rem;
        border-radius: 8px;
        border: 2px solid #ddd;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .apply-btn {
        width: 100%;
        padding: 1.4rem;
        font-size: 1.6rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: #fff;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<section class="seller-apply-section">
    <div class="seller-apply-card">
        @php
            $isSeller = ($user->role ?? 'buyer') === 'seller';
        @endphp
        <h1>Shop Settings</h1>
        <p>
            @if(($user->seller_status ?? 'pending') === 'approved')
                Your shop is approved. You can update the information buyers see on your products.
            @elseif(($user->seller_status ?? 'pending') === 'rejected')
                Your seller application was rejected. Please review your details and contact admin if needed.
            @else
                Your seller application is pending. You may update your business details below while waiting for approval.
            @endif
        </p>

        <form action="{{ route('seller.apply.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="shop_name">Shop Name <span>*</span></label>
                <input type="text" id="shop_name" name="shop_name"
                       value="{{ old('shop_name', $user->shop_name) }}" required>
                @error('shop_name')
                    <div style="color:#e74c3c;font-size:1.3rem;margin-top:0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="business_address">Business Address <span>*</span></label>
                <textarea id="business_address" name="business_address" required>{{ old('business_address', $user->business_address ?? $user->address) }}</textarea>
                @error('business_address')
                    <div style="color:#e74c3c;font-size:1.3rem;margin-top:0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="business_id_number">Business ID / Registration Number</label>
                <input type="text" id="business_id_number" name="business_id_number"
                       value="{{ old('business_id_number', $user->business_id_number) }}">
                @error('business_id_number')
                    <div style="color:#e74c3c;font-size:1.3rem;margin-top:0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="business_notes">Additional Details (optional)</label>
                <textarea id="business_notes" name="business_notes"
                          placeholder="Describe the products you plan to sell, links to social media, etc.">{{ old('business_notes', $user->business_notes) }}</textarea>
                @error('business_notes')
                    <div style="color:#e74c3c;font-size:1.3rem;margin-top:0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="apply-btn">Save shop details</button>
        </form>
    </div>
</section>
@endsection

