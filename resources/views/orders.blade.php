@extends('layouts.app')

@section('title', 'My Orders - U-KAY HUB')

@push('styles')
<style>
    .orders-section {
        padding: 3rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .orders-section .heading {
        font-size: 3rem;
        color: #27ae60;
        text-align: center;
        margin-bottom: 3rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .orders-container {
        display: grid;
        gap: 2rem;
    }

    .order-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 2rem;
        align-items: center;
    }

    .order-card:hover {
        border-color: #27ae60;
        box-shadow: 0 6px 25px rgba(39, 174, 96, 0.2);
        transform: translateY(-2px);
    }

    .order-id-section {
        text-align: center;
        padding-right: 2rem;
        border-right: 2px solid #eee;
    }

    .order-id-label {
        font-size: 1.2rem;
        color: #999;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .order-id-number {
        font-size: 2rem;
        font-weight: 700;
        color: #27ae60;
    }

    .order-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .order-detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        font-size: 1.2rem;
        color: #666;
        font-weight: 500;
    }

    .detail-value {
        font-size: 1.4rem;
        color: #333;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 1.2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }

    .status-complete,
    .status-completed,
    .status-paid,
    .status-shipped,
    .status-delivered {
        background: #d4edda;
        color: #155724;
        border: 1px solid #28a745;
    }

    .status-refunded {
        background: #e8f4fd;
        color: #0c5460;
        border: 1px solid #17a2b8;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #dc3545;
    }

    .order-actions {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .view-details-btn {
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1.3rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        white-space: nowrap;
    }

    .view-details-btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }

    .empty-orders {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-orders i {
        font-size: 8rem;
        color: #ddd;
        margin-bottom: 2rem;
    }

    .empty-orders h2 {
        font-size: 2.5rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .empty-orders p {
        font-size: 1.5rem;
        color: #999;
        margin-bottom: 2rem;
    }

    .empty-orders .btn {
        display: inline-block;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .empty-orders .btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
    }

    @media (max-width: 968px) {
        .order-card {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .order-id-section {
            padding-right: 0;
            padding-bottom: 1.5rem;
            border-right: none;
            border-bottom: 2px solid #eee;
        }

        .order-details {
            grid-template-columns: 1fr 1fr;
        }

        .order-actions {
            flex-direction: row;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .order-details {
            grid-template-columns: 1fr;
        }

        .orders-section .heading {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<section class="orders-section">
    <h1 class="heading">My Orders</h1>

    @if($orders->isEmpty())
        <div class="empty-orders">
            <i class="fas fa-shopping-bag"></i>
            <h2>No Orders Yet</h2>
            <p>You haven't placed any orders yet. Start shopping now!</p>
            <a href="{{ route('home') }}" class="btn">Start Shopping</a>
        </div>
    @else
        <div class="orders-container">
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-id-section">
                        <div class="order-id-label">Order</div>
                        <div class="order-id-number">#{{ $order->id }}</div>
                    </div>

                    <div class="order-details">
                        <div class="order-detail-item">
                            <span class="detail-label">Date</span>
                            <span class="detail-value">{{ date('M d, Y', strtotime($order->placed_on)) }}</span>
                        </div>

                        <div class="order-detail-item">
                            <span class="detail-label">Total</span>
                            <span class="detail-value">₱{{ number_format($order->total_price, 2) }}</span>
                        </div>

                        <div class="order-detail-item">
                            <span class="detail-label">Payment</span>
                            <span class="detail-value">{{ $order->method }}</span>
                        </div>

                        <div class="order-detail-item">
                            <span class="detail-label">Status</span>
                            <span class="status-badge status-{{ strtolower($order->status ?? $order->payment_status) }}">
                                {{ strtoupper($order->status ?? $order->payment_status) }}
                            </span>
                        </div>
                        @if($order->tracking_number)
                            <div class="order-detail-item">
                                <span class="detail-label">Tracking</span>
                                <span class="detail-value">{{ $order->tracking_number }} ({{ $order->shipping_method }})</span>
                            </div>
                        @endif
                    </div>

                    <div class="order-actions">
                        <a href="{{ route('order.details', $order->id) }}" class="view-details-btn">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
