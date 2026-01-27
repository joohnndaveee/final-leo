@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@push('styles')
<style>
    .dashboard-content .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1.5rem;
        padding: 2rem;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: block;
        text-decoration: none;
        cursor: pointer;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.25);
    }

    .stat-card:hover::before {
        transform: translate(20%, -20%) scale(1.2);
    }

    /* Clickable card indicator */
    a.stat-card::after {
        content: '\f054';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        bottom: 1.5rem;
        right: 1.5rem;
        font-size: 1.4rem;
        opacity: 0;
        transition: all 0.3s ease;
    }

    a.stat-card:hover::after {
        opacity: 0.7;
        right: 1.2rem;
    }

    /* Different gradient colors for each card */
    .stat-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.15);
    }

    .stat-card.pending:hover {
        box-shadow: 0 10px 30px rgba(240, 147, 251, 0.25);
    }

    .stat-card.completed {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.15);
    }

    .stat-card.completed:hover {
        box-shadow: 0 10px 30px rgba(79, 172, 254, 0.25);
    }

    .stat-card.orders {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        box-shadow: 0 4px 15px rgba(67, 233, 123, 0.15);
    }

    .stat-card.orders:hover {
        box-shadow: 0 10px 30px rgba(67, 233, 123, 0.25);
    }

    .stat-card.products {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        box-shadow: 0 4px 15px rgba(250, 112, 154, 0.15);
    }

    .stat-card.products:hover {
        box-shadow: 0 10px 30px rgba(250, 112, 154, 0.25);
    }

    .stat-card.users {
        background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        box-shadow: 0 4px 15px rgba(48, 207, 208, 0.15);
    }

    .stat-card.users:hover {
        box-shadow: 0 10px 30px rgba(48, 207, 208, 0.25);
    }

    .stat-card.admins {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        box-shadow: 0 4px 15px rgba(168, 237, 234, 0.15);
    }

    .stat-card.admins:hover {
        box-shadow: 0 10px 30px rgba(168, 237, 234, 0.25);
    }

    .stat-card.messages {
        background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%);
        box-shadow: 0 4px 15px rgba(255, 154, 86, 0.15);
    }

    .stat-card.messages:hover {
        box-shadow: 0 10px 30px rgba(255, 154, 86, 0.25);
    }

    .stat-card.chats {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        box-shadow: 0 4px 15px rgba(52, 211, 153, 0.15);
    }

    .stat-card.chats:hover {
        box-shadow: 0 10px 30px rgba(52, 211, 153, 0.25);
    }

    .stat-card.revenue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
    }

    .stat-card.revenue:hover {
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.25);
    }

    .stat-card .icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .stat-card h3 {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .stat-card p {
        font-size: 1.4rem;
        font-weight: 500;
        margin-bottom: 0;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
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
            font-size: 2.5rem;
        }

        .stat-card .icon {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Dashboard</h1>

{{-- Stats Grid - 4x2 Layout --}}
<div class="stats-container">
    
    {{-- Row 1 --}}
    {{-- Card 1: Pending Orders --}}
    <a href="{{ route('admin.orders') }}?status=pending" class="stat-card pending" style="text-decoration: none; color: white;">
        <i class="fas fa-clock icon"></i>
        <h3>{{ $pending_orders }}</h3>
        <p>Pending Orders</p>
    </a>

    {{-- Card 2: Total Sales (Completed) --}}
    <div class="stat-card completed">
        <i class="fas fa-check-circle icon"></i>
        <h3>₱{{ number_format($total_sales) }}/-</h3>
        <p>Total Sales</p>
    </div>

    {{-- Card 3: Orders Placed --}}
    <a href="{{ route('admin.orders') }}" class="stat-card orders" style="text-decoration: none; color: white;">
        <i class="fas fa-shopping-bag icon"></i>
        <h3>{{ $number_of_orders }}</h3>
        <p>Orders Placed</p>
    </a>

    {{-- Card 4: Products Added --}}
    <a href="{{ route('admin.products.index') }}" class="stat-card products" style="text-decoration: none; color: white;">
        <i class="fas fa-box icon"></i>
        <h3>{{ $number_of_products }}</h3>
        <p>Products Added</p>
    </a>

    {{-- Row 2 --}}
    {{-- Card 5: Registered Users --}}
    <a href="{{ route('admin.users') }}" class="stat-card users" style="text-decoration: none; color: white;">
        <i class="fas fa-users icon"></i>
        <h3>{{ $number_of_users }}</h3>
        <p>Registered Users</p>
    </a>

    {{-- Card 6: Contact Inquiries --}}
    <a href="{{ route('admin.messages') }}" class="stat-card messages" style="text-decoration: none; color: white;">
        <i class="fas fa-envelope icon"></i>
        <h3>{{ $number_of_messages }}</h3>
        <p>Contact Inquiries</p>
    </a>

    {{-- Card 7: Live Chats --}}
    <a href="{{ route('admin.chats.index') }}" class="stat-card chats" style="text-decoration: none; color: white;">
        <i class="fas fa-comments icon"></i>
        <h3>{{ $number_of_chats }}</h3>
        <p>Live Chats</p>
    </a>

    {{-- Card 8: Total Revenue --}}
    <div class="stat-card revenue">
        <i class="fas fa-dollar-sign icon"></i>
        <h3>₱{{ number_format($total_sales) }}/-</h3>
        <p>Total Revenue</p>
    </div>

</div>
@endsection
