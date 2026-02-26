@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')
@php
    $walletBalance = (float) $seller->getWalletBalance();

    $recentPending = $recentOrders->filter(function ($order) {
        $status = strtolower((string) ($order->status ?? $order->payment_status ?? ''));
        return in_array($status, ['pending', 'processing'], true);
    })->count();

    $recentFulfilled = $recentOrders->filter(function ($order) {
        $status = strtolower((string) ($order->status ?? $order->payment_status ?? ''));
        return in_array($status, ['shipped', 'delivered', 'completed', 'complete'], true);
    })->count();

    $recentTotal = max($recentOrders->count(), 1);
    $recentPendingPct = round(($recentPending / $recentTotal) * 100);
    $recentFulfilledPct = round(($recentFulfilled / $recentTotal) * 100);

    $scaledSales = $salesTotal / 1000;
    $scaledWallet = $walletBalance / 1000;
    $maxBar = max($productsCount, $ordersCount, $scaledSales, $scaledWallet, 1);

    $barProducts = max(8, min(100, round(($productsCount / $maxBar) * 100)));
    $barOrders = max(8, min(100, round(($ordersCount / $maxBar) * 100)));
    $barSales = max(8, min(100, round(($scaledSales / $maxBar) * 100)));
    $barWallet = max(8, min(100, round(($scaledWallet / $maxBar) * 100)));

    $statusClass = function ($order) {
        $status = strtolower((string) ($order->status ?? $order->payment_status ?? 'pending'));

        if (in_array($status, ['delivered', 'completed', 'complete'], true)) {
            return 'success';
        }

        if (in_array($status, ['shipped', 'processing'], true)) {
            return 'info';
        }

        if (in_array($status, ['cancelled', 'rejected', 'failed'], true)) {
            return 'danger';
        }

        return 'warning';
    };
@endphp

<style>
    .seller-dashboard {
        width: 100%;
        max-width: none;
        margin: 0;
        display: grid;
        gap: 1rem;
    }

    .seller-dashboard section {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .seller-dashboard > * {
        min-width: 0;
        width: 100%;
        justify-self: stretch;
    }

    .seller-head {
        padding: 1rem 1.1rem;
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 249, 255, 0.9));
    }

    .seller-head h1 {
        margin: 0;
        font-size: 2rem;
        letter-spacing: -0.01em;
        color: #0f172a;
    }

    .seller-head p {
        margin: 0.35rem 0 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(240px, 1fr));
        gap: 0.9rem;
        width: 100%;
    }

    .kpi-card {
        background: rgba(255, 255, 255, 0.93);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        animation: none;
    }

    .kpi-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .kpi-label {
        color: #64748b;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
    }

    .kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(14, 165, 233, 0.2));
        color: #047857;
        font-size: 0.95rem;
    }

    .kpi-value {
        margin: 0;
        color: #0f172a;
        font-size: 1.85rem;
        line-height: 1.1;
        font-weight: 800;
    }

    .kpi-sub {
        margin-top: 0.35rem;
        color: #64748b;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
        width: 100%;
    }

    .panel {
        background: rgba(255, 255, 255, 0.93);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    .panel h2 {
        margin: 0 0 0.9rem;
        color: #0f172a;
        font-size: 1.1rem;
    }

    .micro-bars {
        display: grid;
        gap: 0.8rem;
    }

    .micro-row {
        display: grid;
        gap: 0.35rem;
    }

    .micro-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.84rem;
        color: #475569;
    }

    .micro-track {
        height: 10px;
        border-radius: 999px;
        background: #eef2ff;
        overflow: hidden;
    }

    .micro-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #10b981, #0ea5e9);
    }

    .pipeline-list {
        display: grid;
        gap: 0.85rem;
    }

    .pipeline-item {
        display: grid;
        gap: 0.35rem;
    }

    .pipeline-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #475569;
        font-size: 0.84rem;
    }

    .pipeline-track {
        height: 8px;
        border-radius: 999px;
        background: #f1f5f9;
        overflow: hidden;
    }

    .pipeline-fill {
        height: 100%;
        border-radius: inherit;
    }

    .fill-ok { background: linear-gradient(90deg, #10b981, #22c55e); }
    .fill-warn { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    .orders-panel {
        background: rgba(255, 255, 255, 0.93);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .orders-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .orders-head h2 {
        margin: 0;
        font-size: 1.05rem;
        color: #0f172a;
    }

    .orders-link {
        text-decoration: none;
        color: #0f766e;
        font-size: 0.84rem;
        font-weight: 700;
        padding: 0.45rem 0.7rem;
        border-radius: 8px;
        border: 1px solid rgba(15, 118, 110, 0.25);
        background: rgba(240, 253, 250, 0.9);
    }

    .orders-link:hover {
        background: rgba(204, 251, 241, 0.9);
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .orders-table thead th {
        text-align: left;
        font-size: 0.78rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #64748b;
        background: #f8fafc;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .orders-table tbody td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
    }

    .orders-table tbody tr:hover {
        background: #f8fafc;
    }

    .oid {
        font-weight: 700;
        color: #0f766e;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: capitalize;
        border: 1px solid transparent;
    }

    .status-pill.success {
        background: rgba(16, 185, 129, 0.14);
        color: #065f46;
        border-color: rgba(16, 185, 129, 0.24);
    }

    .status-pill.info {
        background: rgba(59, 130, 246, 0.14);
        color: #1e3a8a;
        border-color: rgba(59, 130, 246, 0.25);
    }

    .status-pill.warning {
        background: rgba(245, 158, 11, 0.16);
        color: #92400e;
        border-color: rgba(245, 158, 11, 0.26);
    }

    .status-pill.danger {
        background: rgba(239, 68, 68, 0.14);
        color: #991b1b;
        border-color: rgba(239, 68, 68, 0.25);
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #64748b;
        font-size: 0.95rem;
    }

    .alert-banner {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.9rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        border: 1px solid;
    }

    .alert-danger {
        background: rgba(254, 242, 242, 0.92);
        border-color: rgba(248, 113, 113, 0.3);
        color: #991b1b;
    }

    .alert-warning {
        background: rgba(255, 251, 235, 0.94);
        border-color: rgba(251, 191, 36, 0.35);
        color: #92400e;
    }

    .alert-content h3 {
        margin: 0 0 0.3rem;
        font-size: 1rem;
    }

    .alert-content p {
        margin: 0;
        font-size: 0.88rem;
    }

    .alert-btn {
        text-decoration: none;
        border-radius: 8px;
        padding: 0.55rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 700;
        background: #fff;
        border: 1px solid currentColor;
        color: inherit;
        white-space: nowrap;
    }

    @media (max-width: 1200px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .seller-dashboard {
            gap: 0.8rem;
        }

        .seller-head h1 {
            font-size: 1.6rem;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .orders-table {
            table-layout: auto;
        }

        .orders-head {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.6rem;
        }

        .alert-banner {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="seller-dashboard">
    @if($seller->isSubscriptionDisabled())
        <div class="alert-banner alert-danger">
            <div class="alert-content">
                <h3>Subscription {{ ucfirst($seller->subscription_status) }}</h3>
                <p>Your subscription is {{ $seller->subscription_status }}. Renew to restore selling features.</p>
            </div>
            <a href="{{ route('seller.settings') }}" class="alert-btn">Renew Now</a>
        </div>
    @elseif($seller->isSubscriptionExpiringSoon())
        <div class="alert-banner alert-warning">
            <div class="alert-content">
                <h3>Subscription Expiring Soon</h3>
                <p>Your subscription expires in {{ $seller->getDaysUntilExpiration() }} days.</p>
            </div>
            <a href="{{ route('seller.settings') }}" class="alert-btn">Renew Now</a>
        </div>
    @endif

    <section class="seller-head">
        <h1>Seller Dashboard</h1>
        <p>Track your product catalog, order flow, and revenue from one clean workspace.</p>
    </section>

    <section class="kpi-grid">
        <article class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-label">Products</span>
                <span class="kpi-icon"><i class="fas fa-box-open"></i></span>
            </div>
            <h3 class="kpi-value">{{ number_format($productsCount) }}</h3>
            <div class="kpi-sub">Active listings</div>
        </article>

        <article class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-label">Orders</span>
                <span class="kpi-icon"><i class="fas fa-shopping-bag"></i></span>
            </div>
            <h3 class="kpi-value">{{ number_format($ordersCount) }}</h3>
            <div class="kpi-sub">All-time orders</div>
        </article>

        <article class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-label">Sales</span>
                <span class="kpi-icon"><i class="fas fa-chart-line"></i></span>
            </div>
            <h3 class="kpi-value">PHP {{ number_format($salesTotal, 2) }}</h3>
            <div class="kpi-sub">Total revenue</div>
        </article>

        <article class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-label">Wallet</span>
                <span class="kpi-icon"><i class="fas fa-wallet"></i></span>
            </div>
            <h3 class="kpi-value">PHP {{ number_format($walletBalance, 2) }}</h3>
            <div class="kpi-sub">Available balance</div>
        </article>
    </section>

    <section class="analytics-grid">
        <article class="panel">
            <h2>Performance Snapshot</h2>
            <div class="micro-bars">
                <div class="micro-row">
                    <div class="micro-label"><span>Products</span><strong>{{ $productsCount }}</strong></div>
                    <div class="micro-track"><div class="micro-fill" style="width: {{ $barProducts }}%"></div></div>
                </div>
                <div class="micro-row">
                    <div class="micro-label"><span>Orders</span><strong>{{ $ordersCount }}</strong></div>
                    <div class="micro-track"><div class="micro-fill" style="width: {{ $barOrders }}%"></div></div>
                </div>
                <div class="micro-row">
                    <div class="micro-label"><span>Sales (scaled)</span><strong>{{ number_format($salesTotal, 0) }}</strong></div>
                    <div class="micro-track"><div class="micro-fill" style="width: {{ $barSales }}%"></div></div>
                </div>
                <div class="micro-row">
                    <div class="micro-label"><span>Wallet (scaled)</span><strong>{{ number_format($walletBalance, 0) }}</strong></div>
                    <div class="micro-track"><div class="micro-fill" style="width: {{ $barWallet }}%"></div></div>
                </div>
            </div>
        </article>

        <article class="panel">
            <h2>Recent Order Pipeline</h2>
            <div class="pipeline-list">
                <div class="pipeline-item">
                    <div class="pipeline-head"><span>Fulfilled / Completed</span><strong>{{ $recentFulfilledPct }}%</strong></div>
                    <div class="pipeline-track"><div class="pipeline-fill fill-ok" style="width: {{ $recentFulfilledPct }}%"></div></div>
                </div>
                <div class="pipeline-item">
                    <div class="pipeline-head"><span>Pending / Processing</span><strong>{{ $recentPendingPct }}%</strong></div>
                    <div class="pipeline-track"><div class="pipeline-fill fill-warn" style="width: {{ $recentPendingPct }}%"></div></div>
                </div>
                <div class="pipeline-item">
                    <div class="pipeline-head"><span>Recent Orders Considered</span><strong>{{ $recentOrders->count() }}</strong></div>
                    <div class="pipeline-track"><div class="pipeline-fill fill-ok" style="width: 100%"></div></div>
                </div>
            </div>
        </article>
    </section>

    <section class="orders-panel">
        <div class="orders-head">
            <h2>Recent Orders</h2>
            <a href="{{ route('seller.orders.index') }}" class="orders-link">Open Orders</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="empty-state">No orders yet. Your latest transactions will appear here.</div>
        @else
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        @php
                            $orderStatus = $order->status ?? $order->payment_status ?? 'pending';
                            $pillClass = $statusClass($order);
                        @endphp
                        <tr>
                            <td><span class="oid">#{{ $order->id }}</span></td>
                            <td><span class="status-pill {{ $pillClass }}">{{ ucfirst($orderStatus) }}</span></td>
                            <td>PHP {{ number_format((float) $order->total_price, 2) }}</td>
                            <td>{{ $order->placed_on }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
</div>
@endsection
