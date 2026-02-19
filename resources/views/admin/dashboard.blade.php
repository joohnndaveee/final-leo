@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@push('styles')
<style>
    /* Minimal dashboard cards (professional / low color) */
    .dashboard-content .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--admin-panel-bg, #ffffff);
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: 10px;
        padding: 1.6rem;
        color: var(--admin-text, #111827) !important;
        box-shadow: var(--admin-shadow, 0 1px 2px rgba(17, 24, 39, 0.06));
        position: relative;
        overflow: hidden;
        display: block;
        text-decoration: none !important;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
    }

    .stat-card:hover {
        background: #fafafa;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }

    /* Subtle left accent per type (keeps theme green, others neutral) */
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #e5e7eb;
    }
    .stat-card.pending::before { background: #d1d5db; }
    .stat-card.completed::before { background: var(--main-color, #3ac72d); }
    .stat-card.orders::before { background: #d1d5db; }
    .stat-card.products::before { background: #d1d5db; }
    .stat-card.users::before { background: #d1d5db; }
    .stat-card.messages::before { background: #d1d5db; }
    .stat-card.chats::before { background: #d1d5db; }
    .stat-card.revenue::before { background: #d1d5db; }

    /* Clickable card indicator */
    a.stat-card::after {
        content: '\f054';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 1.6rem;
        right: 1.4rem;
        font-size: 1.2rem;
        opacity: 0.25;
        color: #6b7280;
        transition: opacity 0.15s ease, transform 0.15s ease;
    }
    a.stat-card:hover::after {
        opacity: 0.45;
        transform: translateX(2px);
    }

    .stat-card .icon {
        font-size: 2.2rem;
        margin-bottom: 1rem;
        color: #6b7280;
    }
    .stat-card.completed .icon {
        color: #166534;
    }

    .stat-card h3 {
        font-size: 2.4rem;
        font-weight: 700;
        margin: 0 0 0.35rem 0;
        color: var(--admin-text, #111827);
        line-height: 1.1;
    }

    .stat-card p {
        font-size: 1.25rem;
        font-weight: 500;
        margin: 0;
        color: #6b7280;
        text-transform: none;
        letter-spacing: 0.02em;
    }

    /* Responsive adjustments */
    @media (max-width: 1400px) {
        .dashboard-content .stats-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1024px) {
        .dashboard-content .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .dashboard-content .stats-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .stat-card h3 {
            font-size: 2.2rem;
        }

        .stat-card .icon {
            font-size: 2.1rem;
        }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Dashboard</h1>

{{-- Stats Grid - 4x2 Layout --}}
<div class="stats-container">
    
    {{-- Row 1: Orders & Revenue --}}
    {{-- Card 1: Pending Orders (read-only, no orders route) --}}
    <div class="stat-card pending">
        <i class="fas fa-clock icon"></i>
        <h3>{{ $pending_orders }}</h3>
        <p>Pending Orders</p>
    </div>

    {{-- Card 2: Total Revenue (Completed) --}}
    <div class="stat-card completed">
        <i class="fas fa-check-circle icon"></i>
        <h3>₱{{ number_format($total_sales) }}/-</h3>
        <p>Total Revenue</p>
    </div>

    {{-- Card 3: Total Orders (read-only, no orders route) --}}
    <div class="stat-card orders">
        <i class="fas fa-shopping-bag icon"></i>
        <h3>{{ $number_of_orders }}</h3>
        <p>Total Orders</p>
    </div>

    {{-- Card 4: Products in Catalog --}}
    <a href="{{ route('admin.products.index') }}" class="stat-card products" style="text-decoration: none; color: white;">
        <i class="fas fa-box icon"></i>
        <h3>{{ $number_of_products }}</h3>
        <p>Products Added</p>
    </a>

    {{-- Row 2: Users, Sellers & Messages --}}
    {{-- Card 5: Registered Users --}}
    <a href="{{ route('admin.users') }}" class="stat-card users" style="text-decoration: none; color: white;">
        <i class="fas fa-users icon"></i>
        <h3>{{ $number_of_users }}</h3>
        <p>Registered Users</p>
    </a>

    {{-- Card 6: Total Sellers --}}
    <a href="{{ route('admin.users') }}#sellers" class="stat-card messages" style="text-decoration: none; color: white;">
        <i class="fas fa-store icon"></i>
        <h3>{{ $total_sellers }}</h3>
        <p>Total Sellers</p>
    </a>

    {{-- Card 7: Pending Seller Applications --}}
    <a href="{{ route('admin.users') }}#sellers" class="stat-card chats" style="text-decoration: none; color: white;">
        <i class="fas fa-user-clock icon"></i>
        <h3>{{ $pending_sellers }}</h3>
        <p>Pending Sellers</p>
    </a>

    {{-- Card 8: Unread Messages --}}
    <div class="stat-card revenue">
        <i class="fas fa-envelope-open icon"></i>
        <h3>{{ $unread_messages }}</h3>
        <p>Unread Messages</p>
    </div>

</div>
@endsection
