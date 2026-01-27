@extends('layouts.app')

@section('title', 'Order Details - U-KAY HUB')

@push('styles')
<style>
    .order-details-section {
        padding: 2rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.5rem;
        background: white;
        color: #27ae60;
        border: 2px solid #27ae60;
        border-radius: 6px;
        text-decoration: none;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background: #27ae60;
        color: white;
    }

    .order-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .order-header h1 {
        font-size: 2.5rem;
        color: #27ae60;
        margin-bottom: 0.5rem;
    }

    .order-header .order-date {
        font-size: 1.4rem;
        color: #666;
    }

    .order-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
    }

    .info-card h2 {
        font-size: 1.8rem;
        color: #27ae60;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 0.8rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        font-size: 1.3rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 600;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 1.2rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-complete,
    .status-completed,
    .status-delivered {
        background: #d4edda;
        color: #155724;
    }

    .products-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
        grid-column: 1 / -1;
    }

    .products-card h2 {
        font-size: 1.8rem;
        color: #27ae60;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 0.8rem;
    }

    .product-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        align-items: center;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #27ae60;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .product-name {
        font-size: 1.6rem;
        color: #333;
        font-weight: 600;
    }

    .product-meta {
        font-size: 1.3rem;
        color: #666;
    }

    .product-price-section {
        text-align: right;
    }

    .product-price {
        font-size: 1.4rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .product-subtotal {
        font-size: 1.8rem;
        color: #27ae60;
        font-weight: 700;
    }

    .order-summary {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #27ae60;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        font-size: 1.5rem;
    }

    .summary-row.grand-total {
        font-size: 2rem;
        font-weight: 700;
        color: #27ae60;
        border-top: 2px solid #27ae60;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    @media (max-width: 968px) {
        .order-content {
            grid-template-columns: 1fr;
        }

        .product-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .product-image {
            width: 80px;
            height: 80px;
        }

        .product-price-section {
            grid-column: 2;
            text-align: left;
            margin-top: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .product-item {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .product-image {
            margin: 0 auto;
        }

        .product-price-section {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<section class="order-details-section">
    <a href="{{ route('orders') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to My Orders
    </a>

    <div class="order-header">
        <h1>Order #{{ $order->id }}</h1>
        <p class="order-date">Placed on {{ date('F d, Y', strtotime($order->placed_on)) }}</p>
    </div>

    <div class="order-content">
        <!-- Order Information -->
        <div class="info-card">
            <h2>Order Information</h2>
            <div class="info-row">
                <span class="info-label">Order ID:</span>
                <span class="info-value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ date('M d, Y', strtotime($order->placed_on)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ strtolower($order->payment_status) }}">
                    {{ $order->payment_status }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ $order->method }}</span>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="info-card">
            <h2>Delivery Information</h2>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $order->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $order->number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $order->address }}</span>
            </div>
        </div>
    </div>

    <!-- Products Ordered -->
    <div class="products-card">
        <h2>Products Ordered</h2>
        @foreach($order->orderItems as $item)
            <div class="product-item">
                <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
                
                <div class="product-info">
                    <div class="product-name">{{ $item->name }}</div>
                    <div class="product-meta">
                        Price: ₱{{ number_format($item->price, 2) }} × Quantity: {{ $item->quantity }}
                    </div>
                </div>

                <div class="product-price-section">
                    <div class="product-price">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</div>
                    <div class="product-subtotal">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
                </div>
            </div>
        @endforeach

        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₱{{ number_format($order->total_price, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Delivery Fee:</span>
                <span>FREE</span>
            </div>
            <div class="summary-row grand-total">
                <span>Grand Total:</span>
                <span>₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</section>
@endsection
