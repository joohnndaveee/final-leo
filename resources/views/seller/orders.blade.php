@extends('layouts.seller')

@section('title', 'Seller Orders')

@push('styles')
<style>
    .orders-section {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid var(--main-color);
    }

    .header-section h1 {
        font-size: 2.8rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-section h1 i {
        color: var(--main-color);
    }

    .orders-count {
        font-size: 1.6rem;
        color: white;
        background: var(--main-color);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.8rem;
        border-radius: 1.2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--main-color);
    }

    .stat-card h3 {
        font-size: 1.2rem;
        color: #6b7280;
        margin: 0 0 0.8rem 0;
        font-weight: 500;
    }

    .stat-card .number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--main-color);
        margin: 0;
    }

    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 1.2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filters-section form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filters-section .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .filters-section label {
        font-size: 1.2rem;
        font-weight: 600;
        color: #374151;
    }

    .filters-section input,
    .filters-section select {
        padding: 0.8rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.6rem;
        font-size: 1.3rem;
        background: white;
    }

    .filters-section input:focus,
    .filters-section select:focus {
        outline: none;
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .filters-section button {
        padding: 0.8rem 2rem;
        background: var(--main-color);
        color: white;
        border: none;
        border-radius: 0.6rem;
        font-size: 1.3rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filters-section button:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .orders-table-wrapper {
        background: white;
        border-radius: 1.2rem;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    }

    .orders-table thead th {
        padding: 1.5rem;
        text-align: left;
        font-size: 1.4rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .orders-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .orders-table tbody tr:hover {
        background: rgba(76, 175, 80, 0.05);
    }

    .orders-table tbody td {
        padding: 1.5rem;
        font-size: 1.4rem;
        color: #4b5563;
    }

    .order-id {
        font-weight: 700;
        color: var(--main-color);
        font-size: 1.5rem;
    }

    .items-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .items-list li {
        padding: 0.3rem 0;
        font-size: 1.3rem;
        color: #6b7280;
    }

    .status-badge {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-shipped {
        background: #bfdbfe;
        color: #1e40af;
    }

    .status-delivered {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-form {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .action-form input {
        padding: 0.8rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.6rem;
        font-size: 1.2rem;
        flex: 1;
        min-width: 120px;
    }

    .action-form input:focus {
        outline: none;
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .action-form button {
        padding: 0.8rem 1.5rem;
        background: var(--main-color);
        color: white;
        border: none;
        border-radius: 0.6rem;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .action-form button:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }

    .success-message {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left: 4px solid #10b981;
        color: #065f46;
        padding: 1.2rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .success-message i {
        font-size: 1.6rem;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: #6b7280;
        margin: 0 0 0.5rem 0;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 1.4rem;
        color: #9ca3af;
        margin: 0;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        padding: 2rem 1rem;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 0.6rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.6rem;
        font-size: 1.3rem;
        text-decoration: none;
        color: #374151;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background: var(--main-color);
        color: white;
        border-color: var(--main-color);
    }

    .pagination .active span {
        background: var(--main-color);
        color: white;
        border-color: var(--main-color);
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .action-form {
            flex-direction: column;
        }

        .action-form input,
        .action-form button {
            width: 100%;
        }

        .orders-section {
            padding: 0 0.8rem;
        }

        .orders-table thead th {
            padding: 1rem 0.6rem;
            font-size: 1.2rem;
        }

        .orders-table tbody td {
            padding: 1rem 0.6rem;
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@section('content')
<section class="orders-section">
    <!-- Header -->
    <div class="header-section">
        <h1>
            <i class="fas fa-shopping-bag"></i>
            Seller Orders
        </h1>
        <div class="orders-count">
            <i class="fas fa-box"></i>
            {{ $orders->total() }} Order{{ $orders->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    @php
        $pendingCount = $orders->where('status', 'pending')->count();
        $shippedCount = $orders->where('status', 'shipped')->count();
        $deliveredCount = $orders->where('status', 'delivered')->count();
    @endphp
    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-clock"></i> Pending Orders</h3>
            <p class="number">{{ $pendingCount }}</p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-truck"></i> Shipped Orders</h3>
            <p class="number">{{ $shippedCount }}</p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-check-circle"></i> Delivered Orders</h3>
            <p class="number">{{ $deliveredCount }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('seller.orders.index') }}">
            <div class="form-group">
                <label for="status">Filter by Status</label>
                <select name="status" id="status">
                    <option value="">All Orders</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="search">Search Order ID</label>
                <input type="text" name="search" id="search" placeholder="Order #..." value="{{ request('search') }}">
            </div>
            <button type="submit">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request('status') || request('search'))
                <a href="{{ route('seller.orders.index') }}" style="padding:0.8rem 2rem;background:#9ca3af;color:white;border:none;border-radius:0.6rem;font-size:1.3rem;font-weight:600;cursor:pointer;text-decoration:none;display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="orders-table-wrapper">
        @forelse($orders as $order)
            @if($loop->first)
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Products</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                        <tr>
                            <td><span class="order-id">#{{ $order->id }}</span></td>
                            <td>
                                <strong>{{ $order->name }}</strong>
                                <br>
                                <small style="color:#9ca3af;">{{ $order->email }}</small>
                            </td>
                            <td>
                                <div style="font-size:1.2rem;color:#6b7280;margin-bottom:0.4rem;">
                                    {{ $order->orderItems->count() }} Product{{ $order->orderItems->count() !== 1 ? 's' : '' }}
                                </div>
                                <ul class="items-list">
                                    @forelse($order->orderItems as $item)
                                        <li>{{ $item->name }} <strong>(x{{ $item->quantity }})</strong></li>
                                    @empty
                                        <li style="color:#9ca3af;">No items</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td><strong style="font-size:1.5rem;color:var(--main-color);">₱{{ number_format($order->total_price, 2) }}</strong></td>
                            <td>
                                <span class="status-badge status-{{ strtolower($order->status ?? $order->payment_status) }}">
                                    {{ ucfirst($order->status ?? $order->payment_status) }}
                                </span>
                            </td>
                            <td>{{ date('M d, Y', strtotime($order->placed_on)) }}</td>
                            <td>
                                @if(strtolower($order->status ?? '') === 'pending')
                                    <form action="{{ route('seller.orders.ship', $order) }}" method="POST" class="action-form">
                                        @csrf
                                        <input type="text" name="tracking_number" placeholder="Tracking #" required title="Enter tracking number">
                                        <input type="text" name="shipping_method" placeholder="Courier (e.g., JNE)" required title="Enter shipping method/courier">
                                        <button type="submit" title="Mark as shipped">
                                            <i class="fas fa-paper-plane"></i> Ship
                                        </button>
                                    </form>
                                @elseif(strtolower($order->status ?? '') === 'shipped')
                                    <form action="{{ route('seller.orders.deliver', $order) }}" method="POST" class="action-form">
                                        @csrf
                                        <button type="submit" title="Mark as delivered">
                                            <i class="fas fa-check-circle"></i> Delivered
                                        </button>
                                    </form>
                                @else
                                    <span style="color:#9ca3af;font-size:1.2rem;">No action available</span>
                                @endif
                            </td>
                        </tr>
            @if($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Orders Yet</h3>
                <p>You don't have any orders yet. When customers order your products, they will appear here.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->count() > 0)
            <div class="pagination">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
