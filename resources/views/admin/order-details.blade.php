@extends('layouts.admin')

@section('title', 'Order Details - Admin Panel')

@push('styles')
<style>
        .order-details-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.4rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateX(-5px);
        }

        .order-header {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-header h2 {
            font-size: 2.5rem;
            color: #27ae60;
            margin: 0;
        }

        .status-badge {
            padding: 1rem 2rem;
            border-radius: 20px;
            font-size: 1.4rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .details-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .details-card h3 {
            font-size: 1.8rem;
            color: #27ae60;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
            text-align: right;
        }

        .products-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .products-card h3 {
            font-size: 1.8rem;
            color: #27ae60;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 1rem;
        }

        .product-item {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 1.5rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
            align-items: center;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #27ae60;
        }

        .product-info h4 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .product-meta {
            font-size: 1.3rem;
            color: #666;
        }

        .product-total {
            font-size: 1.6rem;
            font-weight: 700;
            color: #27ae60;
            text-align: right;
        }

        .order-summary {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #27ae60;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            font-size: 1.5rem;
        }

        .summary-row.grand-total {
            font-size: 2rem;
            font-weight: 700;
            color: #27ae60;
        }

        @media (max-width: 968px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="order-details-container">
        <a href="{{ route('admin.orders') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>

        <div class="order-header">
            <h2>Order #{{ $order->id }}</h2>
            <span class="status-badge status-{{ $order->payment_status }}">
                {{ ucfirst($order->payment_status) }}
            </span>
        </div>

        <div class="details-grid">
            <!-- Customer Information -->
            <div class="details-card">
                <h3>Customer Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $order->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $order->number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $order->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $order->address }}</span>
                </div>
            </div>

            <!-- Order Information -->
            <div class="details-card">
                <h3>Order Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value">#{{ $order->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">{{ date('F d, Y', strtotime($order->placed_on)) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ $order->method }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Products Ordered -->
        <div class="products-card">
            <h3>Products Ordered</h3>
            @foreach($order->orderItems as $item)
                <div class="product-item">
                    <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
                    
                    <div class="product-info">
                        <h4>{{ $item->name }}</h4>
                        <div class="product-meta">
                            ₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}
                        </div>
                    </div>

                    <div class="product-total">
                        ₱{{ number_format($item->price * $item->quantity, 2) }}
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
    </div>
</div>
@endsection
