@extends('layouts.app')

@section('title', 'Thank You - U-KAY HUB')

@push('styles')
<style>
    .thank-you-section {
        padding: 2rem 2rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .thank-you-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .success-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }

    .success-icon i {
        font-size: 3rem;
        color: white;
    }

    .thank-you-section h1 {
        font-size: 2.5rem;
        color: #27ae60;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .thank-you-section .subtitle {
        font-size: 1.4rem;
        color: #666;
        margin-bottom: 1.5rem;
    }

    .order-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 1.5rem;
    }

    .order-details-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
    }

    .order-id-badge {
        display: inline-block;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
    }

    .order-info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        font-size: 1.3rem;
    }

    .order-info-row:last-child {
        border-bottom: none;
    }

    .order-info-label {
        color: #666;
        font-weight: 500;
    }

    .order-info-value {
        color: #333;
        font-weight: 600;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    .order-items-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
    }

    .order-items-card h3 {
        font-size: 1.8rem;
        color: #27ae60;
        margin-bottom: 1rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 0.8rem;
    }

    .order-item-summary {
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        font-size: 1.3rem;
        line-height: 1.5;
    }

    .order-item-summary:last-child {
        border-bottom: none;
    }

    .order-total-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #27ae60;
        margin-top: 1rem;
        border-top: 2px solid #27ae60;
    }

    .info-message {
        background: rgba(39, 174, 96, 0.1);
        border-left: 4px solid #27ae60;
        padding: 1.2rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        color: #333;
        line-height: 1.6;
    }

    .info-message i {
        color: #27ae60;
        margin-right: 0.8rem;
        font-size: 1.4rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 500px;
        margin: 0 auto;
    }

    .btn {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        font-size: 1.3rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        box-shadow: 0 3px 12px rgba(39, 174, 96, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: white;
        color: #27ae60;
        border: 2px solid #27ae60;
    }

    .btn-secondary:hover {
        background: #27ae60;
        color: white;
    }

    @media (max-width: 968px) {
        .order-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .thank-you-section h1 {
            font-size: 2rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="thank-you-section">
    <div class="thank-you-header">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1>Thank You for Your Order!</h1>
        <p class="subtitle">Your order has been successfully placed and is being processed.</p>
    </div>

    <div class="order-content">
        <!-- Left Column: Order Details -->
        <div class="order-details-card">
            <div style="text-align: center;">
                <span class="order-id-badge">Order #{{ $order->id }}</span>
            </div>

            <div class="order-info-row">
                <span class="order-info-label">Name:</span>
                <span class="order-info-value">{{ $order->name }}</span>
            </div>

            <div class="order-info-row">
                <span class="order-info-label">Phone:</span>
                <span class="order-info-value">{{ $order->number }}</span>
            </div>

            <div class="order-info-row">
                <span class="order-info-label">Address:</span>
                <span class="order-info-value">{{ $order->address }}</span>
            </div>

            <div class="order-info-row">
                <span class="order-info-label">Payment:</span>
                <span class="order-info-value">{{ $order->method }}</span>
            </div>

            <div class="order-info-row">
                <span class="order-info-label">Date:</span>
                <span class="order-info-value">{{ date('M d, Y', strtotime($order->placed_on)) }}</span>
            </div>

            <div class="order-info-row">
                <span class="order-info-label">Status:</span>
                <span class="order-info-value" style="color: #f39c12; text-transform: capitalize;">{{ $order->payment_status }}</span>
            </div>
        </div>

        <!-- Right Column: Order Items -->
        <div class="order-items-card">
            <h3>Order Items</h3>
            @foreach($order->orderItems as $item)
                <div class="order-item-summary">
                    <strong>{{ $item->name }}</strong><br>
                    ₱{{ number_format($item->price, 2) }} × {{ $item->quantity }} = 
                    <strong style="color: #27ae60;">₱{{ number_format($item->price * $item->quantity, 2) }}</strong>
                </div>
            @endforeach

            <div class="order-total-row">
                <span>Total:</span>
                <span>₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="info-message">
        <i class="fas fa-info-circle"></i>
        <strong>What's Next?</strong> Our team will contact you shortly to confirm your order. 
        Please keep your phone accessible. Delivery within 3-5 business days.
    </div>

    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
        <a href="{{ route('orders') }}" class="btn btn-secondary">View My Orders</a>
    </div>
</section>
@endsection
