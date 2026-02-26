@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@push('styles')
<style>
    /* Layout */
    .dashboard-content {
        padding: 1.75rem 2rem;
        background: #f0f4f8;
        min-height: 100%;
    }

    .dashboard-wrap { width: 100%; }

    /* Header */
    .dashboard-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .dashboard-title {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .dashboard-subtitle {
        margin: 0.25rem 0 0;
        color: #64748b;
        font-size: 0.925rem;
    }

    .dashboard-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.9rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .dashboard-date i { color: #3b82f6; }

    /* Stat Cards Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-card {
        border-radius: 14px;
        padding: 1.25rem 1.4rem;
        color: #fff !important;
        text-decoration: none !important;
        display: block;
        position: relative;
        overflow: hidden;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        color: #fff !important;
    }

    /* decorative circle using ::before */
    .stat-card::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 110px; height: 110px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
        pointer-events: none;
        z-index: 0;
    }

    .stat-card > * { position: relative; z-index: 1; }

    .stat-card.sc-blue   { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(59,130,246,0.35) !important; }
    .stat-card.sc-red    { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(239,68,68,0.35) !important; }
    .stat-card.sc-violet { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(139,92,246,0.35) !important; }
    .stat-card.sc-teal   { background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(20,184,166,0.35) !important; }
    .stat-card.sc-amber  { background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(245,158,11,0.35) !important; }
    .stat-card.sc-green  { background: linear-gradient(135deg, #22c55e 0%, #15803d 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(34,197,94,0.35) !important; }
    .stat-card.sc-sky    { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(14,165,233,0.35) !important; }
    .stat-card.sc-rose   { background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%) !important; border: none !important; box-shadow: 0 4px 16px rgba(244,63,94,0.35) !important; }

    .stat-card.sc-blue:hover,
    .stat-card.sc-red:hover,
    .stat-card.sc-violet:hover,
    .stat-card.sc-teal:hover,
    .stat-card.sc-amber:hover,
    .stat-card.sc-green:hover,
    .stat-card.sc-sky:hover,
    .stat-card.sc-rose:hover {
        filter: brightness(1.08);
    }

    .stat-card-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: rgba(255,255,255,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 1rem;
        color: #fff !important;
    }

    .stat-card-value {
        font-size: 1.7rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.3rem;
        letter-spacing: -0.02em;
        color: #fff !important;
    }

    .stat-card-label {
        font-size: 0.82rem;
        font-weight: 500;
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #fff !important;
    }

    .stat-card-arrow {
        position: absolute;
        top: 1.1rem; right: 1.1rem;
        width: 28px; height: 28px;
        background: rgba(255,255,255,0.22);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
        opacity: 0;
        transition: opacity 0.18s ease;
        z-index: 2;
    }

    .stat-card:hover .stat-card-arrow { opacity: 1; }

    /* Main Grid */
    .db-main-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    /* Panel Base */
    .panel {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        padding: 1.3rem 1.5rem;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.1rem;
    }

    .panel-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* Metrics Panel */
    .metric-list { display: grid; gap: 1rem; }

    .metric-row {
        display: grid;
        grid-template-columns: 1fr auto auto;
        align-items: center;
        gap: 0.75rem;
    }

    .metric-info { min-width: 0; }

    .metric-name {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.35rem;
    }

    .metric-track {
        height: 7px;
        border-radius: 999px;
        background: #f1f5f9;
        overflow: hidden;
    }

    .metric-fill {
        height: 100%;
        border-radius: inherit;
        transition: width 0.6s ease;
    }

    .metric-val {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
    }

    .metric-sub {
        font-size: 0.78rem;
        color: #94a3b8;
        white-space: nowrap;
    }

    /* Donut Panel */
    .donut-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.1rem;
    }

    .donut-ring-wrap {
        position: relative;
        width: 150px;
        height: 150px;
    }

    .donut-ring-wrap svg { width: 100%; height: 100%; transform: rotate(-90deg); }

    .donut-center {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .donut-center-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }

    .donut-center-label {
        font-size: 0.7rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.2rem;
    }

    .donut-legend {
        width: 100%;
        display: grid;
        gap: 0.55rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.875rem;
        color: #475569;
    }

    .legend-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot-approved { background: #22c55e; }
    .dot-pending  { background: #f59e0b; }
    .dot-rejected { background: #ef4444; }

    .legend-count {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.875rem;
    }

    /* Bottom Row */
    .db-bottom-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1rem;
    }

    /* Volume Tags */
    .vol-tags { display: grid; gap: 0.75rem; }

    .vol-tag {
        border-radius: 10px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .vol-tag.vt-a { background: linear-gradient(90deg, #ef4444, #f97316); }
    .vol-tag.vt-b { background: linear-gradient(90deg, #3b82f6, #06b6d4); }
    .vol-tag.vt-c { background: linear-gradient(90deg, #8b5cf6, #6d28d9); }

    .vol-tag-sub { font-size: 0.72rem; opacity: 0.8; font-weight: 400; margin-top: 0.15rem; }
    .vol-tag-val { font-size: 1rem; font-weight: 800; }

    /* Quick Links */
    .quick-links {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.7rem;
    }

    .quick-link {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #1e293b !important;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
    }

    .quick-link i {
        width: 28px; height: 28px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        flex-shrink: 0;
        background: #e0e7ff;
        color: #4f46e5;
    }

    .quick-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 1280px) {
        .stats-row { grid-template-columns: repeat(4, 1fr); }
        .db-main-grid, .db-bottom-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 900px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .dashboard-content { padding: 1.1rem; }
        .stats-row { grid-template-columns: 1fr; }
        .quick-links { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
@php
    $safeOrders   = max((int) $number_of_orders, 1);
    $safeSellers  = max((int) $total_sellers, 1);
    $safeMessages = max((int) $number_of_messages, 1);

    $fulfilledOrders    = max((int) $number_of_orders - (int) $pending_orders, 0);
    $ordersFulfilledPct = round(($fulfilledOrders / $safeOrders) * 100);
    $ordersPendingPct   = round(((int) $pending_orders / $safeOrders) * 100);
    $unreadMessagesPct  = round(((int) $unread_messages / $safeMessages) * 100);
    $pendingSellerPct   = round(((int) $pending_sellers / $safeSellers) * 100);

    $approvedPct = round(((int) $approved_sellers / $safeSellers) * 100);
    $pendingPct  = round(((int) $pending_sellers  / $safeSellers) * 100);
    $rejectedPct = max(0, 100 - $approvedPct - $pendingPct);

    $circ = 339.292;
    $aLen = round($approvedPct / 100 * $circ, 2);
    $pLen = round($pendingPct  / 100 * $circ, 2);
    $rLen = round($rejectedPct / 100 * $circ, 2);
@endphp

<section class="dashboard-wrap">

    {{-- Header --}}
    <div class="dashboard-head">
        <div>
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Platform performance snapshot &amp; operational overview.</p>
        </div>
        <div class="dashboard-date">
            <i class="fas fa-calendar-alt"></i>
            {{ now()->format('m/d/Y') }}
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stats-row">

        <div class="stat-card sc-blue">
            <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-card-value">{{ $pending_orders }}</div>
            <div class="stat-card-label">Pending Orders</div>
        </div>

        <div class="stat-card sc-green">
            <div class="stat-card-icon"><i class="fas fa-coins"></i></div>
            <div class="stat-card-value">&#8369;{{ number_format($total_sales, 0) }}</div>
            <div class="stat-card-label">Total Revenue</div>
        </div>

        <div class="stat-card sc-violet">
            <div class="stat-card-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-card-value">{{ $number_of_orders }}</div>
            <div class="stat-card-label">Total Orders</div>
        </div>

        <a href="{{ route('admin.products.index') }}" class="stat-card sc-teal">
            <div class="stat-card-icon"><i class="fas fa-box"></i></div>
            <div class="stat-card-value">{{ $number_of_products }}</div>
            <div class="stat-card-label">Products</div>
            <div class="stat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('admin.users') }}" class="stat-card sc-sky">
            <div class="stat-card-icon"><i class="fas fa-users"></i></div>
            <div class="stat-card-value">{{ $number_of_users }}</div>
            <div class="stat-card-label">Registered Users</div>
            <div class="stat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('admin.users') }}#sellers" class="stat-card sc-amber">
            <div class="stat-card-icon"><i class="fas fa-store"></i></div>
            <div class="stat-card-value">{{ $total_sellers }}</div>
            <div class="stat-card-label">Total Sellers</div>
            <div class="stat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('admin.users') }}#sellers" class="stat-card sc-rose">
            <div class="stat-card-icon"><i class="fas fa-user-clock"></i></div>
            <div class="stat-card-value">{{ $pending_sellers }}</div>
            <div class="stat-card-label">Pending Sellers</div>
            <div class="stat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <div class="stat-card sc-red">
            <div class="stat-card-icon"><i class="fas fa-envelope-open"></i></div>
            <div class="stat-card-value">{{ $unread_messages }}</div>
            <div class="stat-card-label">Unread Messages</div>
        </div>

    </div>

    {{-- Main Grid: Metrics + Donut --}}
    <div class="db-main-grid">

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Comparison Details</h2>
            </div>
            <div class="metric-list">

                <div class="metric-row">
                    <div class="metric-info">
                        <div class="metric-name">Order Fulfillment</div>
                        <div class="metric-track">
                            <div class="metric-fill" style="width:{{ $ordersFulfilledPct }}%; background:linear-gradient(90deg,#22c55e,#16a34a);"></div>
                        </div>
                    </div>
                    <div class="metric-val">{{ $ordersFulfilledPct }}%</div>
                    <div class="metric-sub">{{ $fulfilledOrders }} / {{ $number_of_orders }}</div>
                </div>

                <div class="metric-row">
                    <div class="metric-info">
                        <div class="metric-name">Orders Still Pending</div>
                        <div class="metric-track">
                            <div class="metric-fill" style="width:{{ $ordersPendingPct }}%; background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
                        </div>
                    </div>
                    <div class="metric-val">{{ $ordersPendingPct }}%</div>
                    <div class="metric-sub">{{ $pending_orders }} orders</div>
                </div>

                <div class="metric-row">
                    <div class="metric-info">
                        <div class="metric-name">Pending Seller Applications</div>
                        <div class="metric-track">
                            <div class="metric-fill" style="width:{{ $pendingSellerPct }}%; background:linear-gradient(90deg,#3b82f6,#06b6d4);"></div>
                        </div>
                    </div>
                    <div class="metric-val">{{ $pendingSellerPct }}%</div>
                    <div class="metric-sub">{{ $pending_sellers }} sellers</div>
                </div>

                <div class="metric-row">
                    <div class="metric-info">
                        <div class="metric-name">Unread Messages Ratio</div>
                        <div class="metric-track">
                            <div class="metric-fill" style="width:{{ $unreadMessagesPct }}%; background:linear-gradient(90deg,#8b5cf6,#6d28d9);"></div>
                        </div>
                    </div>
                    <div class="metric-val">{{ $unreadMessagesPct }}%</div>
                    <div class="metric-sub">{{ $unread_messages }} unread</div>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Seller Status Mix</h2>
            </div>
            <div class="donut-wrap">
                <div class="donut-ring-wrap">
                    <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="54" stroke="#f1f5f9" stroke-width="12"/>
                        @if($aLen > 0)
                        <circle cx="60" cy="60" r="54" stroke="#22c55e" stroke-width="12"
                            stroke-dasharray="{{ $aLen }} {{ $circ }}"
                            stroke-dashoffset="0" stroke-linecap="round"/>
                        @endif
                        @if($pLen > 0)
                        <circle cx="60" cy="60" r="54" stroke="#f59e0b" stroke-width="12"
                            stroke-dasharray="{{ $pLen }} {{ $circ }}"
                            stroke-dashoffset="-{{ $aLen }}" stroke-linecap="round"/>
                        @endif
                        @if($rLen > 0)
                        <circle cx="60" cy="60" r="54" stroke="#ef4444" stroke-width="12"
                            stroke-dasharray="{{ $rLen }} {{ $circ }}"
                            stroke-dashoffset="-{{ $aLen + $pLen }}" stroke-linecap="round"/>
                        @endif
                    </svg>
                    <div class="donut-center">
                        <div class="donut-center-val">{{ $total_sellers }}</div>
                        <div class="donut-center-label">Sellers</div>
                    </div>
                </div>
                <div class="donut-legend">
                    <div class="legend-item">
                        <div class="legend-left"><span class="dot dot-approved"></span> Approved</div>
                        <span class="legend-count">{{ $approved_sellers }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left"><span class="dot dot-pending"></span> Pending</div>
                        <span class="legend-count">{{ $pending_sellers }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left"><span class="dot dot-rejected"></span> Rejected</div>
                        <span class="legend-count">{{ $rejected_sellers }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Bottom Grid: Summary Tags + Quick Access --}}
    <div class="db-bottom-grid">

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Platform Summary</h2>
            </div>
            <div class="vol-tags">
                <div class="vol-tag vt-a">
                    <div>
                        <div>Pending Orders</div>
                        <div class="vol-tag-sub">Threshold &middot; Awaiting action</div>
                    </div>
                    <div class="vol-tag-val">{{ $pending_orders }}</div>
                </div>
                <div class="vol-tag vt-b">
                    <div>
                        <div>Unread Messages</div>
                        <div class="vol-tag-sub">Threshold &middot; Needs response</div>
                    </div>
                    <div class="vol-tag-val">{{ $unread_messages }}</div>
                </div>
                <div class="vol-tag vt-c">
                    <div>
                        <div>Total Revenue</div>
                        <div class="vol-tag-sub">Threshold &middot; All-time</div>
                    </div>
                    <div class="vol-tag-val">&#8369;{{ number_format($total_sales, 0) }}</div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Quick Access</h2>
            </div>
            <div class="quick-links">
                <a class="quick-link" href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Users
                </a>
                <a class="quick-link" href="{{ route('admin.sellers') }}">
                    <i class="fas fa-store"></i> Sellers
                </a>
                <a class="quick-link" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                <a class="quick-link" href="{{ route('admin.discounts.index') }}">
                    <i class="fas fa-percent"></i> Discounts
                </a>
            </div>
        </div>

    </div>

</section>
@endsection
