@extends('layouts.app')

@section('title', 'Seller Application Status - U-KAY HUB')

@push('styles')
<style>
    .seller-status-section {
        padding: 3rem 2rem;
        max-width: 800px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .seller-status-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
        text-align: center;
    }

    .seller-status-card h1 {
        font-size: 2.4rem;
        color: #27ae60;
        margin-bottom: 1rem;
    }

    .seller-status-badge {
        display: inline-block;
        padding: 0.5rem 1.4rem;
        border-radius: 999px;
        font-size: 1.3rem;
        text-transform: capitalize;
        margin-bottom: 1.5rem;
    }

    .seller-status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .seller-status-badge.approved {
        background: #d4edda;
        color: #155724;
    }

    .seller-status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .seller-status-card p {
        font-size: 1.4rem;
        color: #555;
        margin-bottom: 1rem;
    }

    .seller-status-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-link {
        padding: 0.9rem 1.6rem;
        border-radius: 8px;
        background: #27ae60;
        color: #fff;
        text-decoration: none;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .btn-outline {
        padding: 0.9rem 1.6rem;
        border-radius: 8px;
        background: transparent;
        border: 2px solid #27ae60;
        color: #27ae60;
        text-decoration: none;
        font-size: 1.4rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<section class="seller-status-section">
    <div class="seller-status-card">
        @php
            $status = $user->seller_status ?? 'pending';
        @endphp

        <h1>Seller Application Status</h1>

        <div class="seller-status-badge {{ $status }}">
            {{ ucfirst($status) }}
        </div>

        @if($status === 'approved')
            <p>Your seller application has been approved. You can now access your seller dashboard and start listing products.</p>
        @elseif($status === 'rejected')
            <p>Your seller application was rejected. Please review your details and contact the admin if you believe this is a mistake.</p>
        @else
            <p>Your seller application is currently under review. We will notify you once the admin has made a decision.</p>
        @endif

        <div class="seller-status-actions">
            @if($status === 'approved')
                <a href="{{ route('seller.dashboard') }}" class="btn-link">Go to Seller Dashboard</a>
            @else
                <a href="{{ route('seller.apply') }}" class="btn-outline">Edit Application Details</a>
            @endif
            <a href="{{ route('home') }}" class="btn-outline">Back to Home</a>
        </div>
    </div>
</section>
@endsection

