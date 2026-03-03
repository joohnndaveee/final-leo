@extends('layouts.app')

@section('title', 'My Orders - U-KAY HUB')

@push('styles')
<style>
    .orders-wrap {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem 2rem;
    }
    .orders-title {
        font-size: 2.4rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 1.2rem;
    }
    .order-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-bottom: 1.2rem;
    }
    .order-tab {
        text-decoration: none;
        border: 1px solid #cbd5e1;
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        color: #334155;
        font-size: 0.92rem;
        font-weight: 600;
        background: #fff;
    }
    .order-tab.active {
        background: #0f766e;
        color: #fff;
        border-color: #0f766e;
    }
    .orders-list {
        display: grid;
        gap: 0.9rem;
    }
    .order-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .order-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .order-shop {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
    }
    .order-status {
        font-size: 0.8rem;
        font-weight: 700;
        color: #0f766e;
        background: #ccfbf1;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .status-refund { color: #0c4a6e; background: #dbeafe; }
    .status-complete { color: #14532d; background: #dcfce7; }
    .status-cancel { color: #7f1d1d; background: #fee2e2; }
    .status-pay { color: #854d0e; background: #fef3c7; }
    .order-item {
        display: grid;
        grid-template-columns: 74px 1fr;
        gap: 0.8rem;
        align-items: center;
        padding: 0.9rem 1rem;
    }
    .order-item + .order-item {
        border-top: 1px dashed #e2e8f0;
    }
    .item-image {
        width: 74px;
        height: 74px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }
    .item-name {
        font-size: 0.96rem;
        color: #111827;
        font-weight: 600;
    }
    .item-meta {
        margin-top: 0.2rem;
        color: #64748b;
        font-size: 0.84rem;
    }
    .order-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 0.8rem 1rem 1rem;
        border-top: 1px solid #f1f5f9;
    }
    .order-total {
        color: #0f172a;
        font-size: 0.95rem;
        font-weight: 700;
    }
    .order-link {
        text-decoration: none;
        background: #0f766e;
        color: #fff;
        border-radius: 10px;
        font-size: 0.86rem;
        padding: 0.5rem 0.85rem;
        font-weight: 700;
    }
    .empty-orders {
        background: #fff;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 2.4rem 1rem;
        text-align: center;
        color: #64748b;
    }
</style>
@endpush

@section('content')
@php
    $tabs = [
        'all' => 'All',
        'to_pay' => 'To Pay',
        'to_ship' => 'To Ship',
        'to_receive' => 'To Receive',
        'to_review' => 'To Review',
        'returns' => 'Returns',
    ];

    $statusMeta = function ($order) {
        $status = strtolower((string) ($order->status ?? $order->payment_status ?? 'pending'));
        $payment = strtolower((string) ($order->payment_status ?? ''));

        if (in_array($status, ['cancelled'], true)) {
            return ['Cancellation Successful', 'status-cancel'];
        }
        if (in_array($status, ['refunded', 'returned'], true) || $payment === 'refunded') {
            return ['Refund Completed', 'status-refund'];
        }
        if (in_array($status, ['return_requested', 'return_pickup_scheduled', 'return_picked_up', 'return_preparing', 'return_in_transit_to_seller'], true)) {
            return ['Return In Progress', 'status-refund'];
        }
        if (in_array($status, ['completed', 'complete'], true)) {
            return ['Order Completed', 'status-complete'];
        }
        if (in_array($status, ['pending'], true) && $payment === 'pending') {
            return ['To Pay', 'status-pay'];
        }
        if (in_array($status, ['shipped', 'out_for_delivery', 'delivered', 'in_transit'], true)) {
            return ['In Transit', ''];
        }
        return [ucwords(str_replace('_', ' ', $status ?: $payment ?: 'Pending')), ''];
    };
@endphp

<section class="orders-wrap">
    <h1 class="orders-title">My Orders</h1>

    <div class="order-tabs">
        @foreach($tabs as $tabKey => $tabLabel)
            <a class="order-tab {{ $tab === $tabKey ? 'active' : '' }}"
               href="{{ route('orders', ['tab' => $tabKey]) }}">
                {{ $tabLabel }} ({{ $tabCounts[$tabKey] ?? 0 }})
            </a>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="empty-orders">
            No orders in this category yet.
        </div>
    @else
        <div class="orders-list">
            @foreach($orders as $order)
                @php [$statusLabel, $statusClass] = $statusMeta($order); @endphp
                <article class="order-card">
                    <div class="order-head">
                        <div class="order-shop">Order #{{ $order->id }} - {{ date('M d, Y', strtotime($order->placed_on)) }}</div>
                        <div class="order-status {{ $statusClass }}">{{ $statusLabel }}</div>
                    </div>

                    @foreach($order->orderItems as $item)
                        <div class="order-item">
                            <img class="item-image" src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}">
                            <div>
                                <div class="item-name">{{ $item->name }}</div>
                                <div class="item-meta">
                                    Shop: {{ $item->product?->seller?->shop_name ?? 'Unknown Shop' }} - Qty: {{ $item->quantity }} - &#8369;{{ number_format($item->price, 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="order-foot">
                        <div class="order-total">Total: &#8369;{{ number_format($order->total_price, 2) }}</div>
                        <a class="order-link" href="{{ route('order.details', $order->id) }}">View Details</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
