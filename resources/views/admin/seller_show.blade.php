@extends('layouts.admin')

@section('title', 'Seller Details - Admin Panel')

@push('styles')
<style>
    .sellers-container {
        padding: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #1f2937;
        color: black;
        padding: 2rem;
        border-radius: 1.2rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-card h3 {
        font-size: 1.2rem;
        margin: 0;
        opacity: 0.9;
        font-weight: 500;
    }

    .stat-card .number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0 0 0;
    }

    .section-title {
        font-size: 2rem;
        margin: 2.5rem 0 1.5rem 0;
        color: var(--black);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 2px solid var(--main-color);
        padding-bottom: 1rem;
    }

    .section-title i {
        color: var(--main-color);
    }

    .table-wrapper {
        background: white;
        border-radius: 1.2rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    }

    .table thead th {
        padding: 1.5rem;
        text-align: left;
        font-size: 1.4rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(58, 199, 45, 0.05);
    }

    .table tbody td {
        padding: 1.5rem;
        font-size: 1.4rem;
        color: #4b5563;
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stars {
        color: #ffc107;
    }

    .empty-message {
        padding: 3rem;
        text-align: center;
        color: #9ca3af;
        font-size: 1.5rem;
    }

    .empty-message i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        display: block;
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;
        font-size: 1.2rem;
        font-weight: 500;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .info-card {
        background: white;
        padding: 1.8rem;
        border-radius: 1.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .info-card h3 {
        font-size: 1.6rem;
        margin: 0 0 1.2rem 0;
        color: var(--black);
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.8rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.8rem;
    }

    .info-item strong {
        display: block;
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: 0.3rem;
    }

    .info-item span {
        font-size: 1.5rem;
        color: var(--black);
    }
</style>
@endpush

@section('content')
<div class="sellers-container">
    <!-- Breadcrumb Navigation -->
    <div style="margin-bottom:2rem;font-size:1.4rem;">
        <a href="{{ route('admin.sellers') }}" style="color:var(--main-color);text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Back to Sellers
        </a>
    </div>

    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;padding-bottom:1rem;border-bottom:2px solid var(--main-color);">
        <div>
            <h1 style="font-size:2.4rem;margin:0;display:flex;align-items:center;gap:0.8rem;">
                <i class="fas fa-store" style="color:var(--main-color);"></i>
                {{ $seller->shop_name ?? 'Seller Profile' }}
            </h1>
            <p style="margin-top:0.5rem;font-size:1.4rem;color:#6b7280;">{{ $seller->name }} - {{ $seller->email }}</p>
        </div>
        <div>
            <span class="badge-status {{ $seller->seller_status ?? 'pending' }}" style="display:inline-block;padding:0.6rem 1.2rem;border-radius:999px;font-size:1.3rem;text-transform:capitalize;">
                {{ ucfirst($seller->seller_status ?? 'pending') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-shopping-bag"></i> Total Orders</h3>
            <div class="number">{{ $totalOrders }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-star"></i> Average Rating</h3>
            <div class="number">{{ number_format($averageRating, 1) }}/5</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-comments"></i> Total Reviews</h3>
            <div class="number">{{ $totalReviews }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-dollar-sign"></i> Total Revenue</h3>
            <div class="number">${{ number_format($totalRevenue, 0) }}</div>
        </div>
    </div>

    <!-- Business Information -->
    <div class="info-card">
        <h3><i class="fas fa-briefcase" style="color:var(--main-color);margin-right:0.5rem;"></i> Business Information</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Seller Name</strong>
                <span>{{ $seller->name }}</span>
            </div>
            <div class="info-item">
                <strong>Email</strong>
                <span>{{ $seller->email }}</span>
            </div>
            <div class="info-item">
                <strong>Shop Name</strong>
                <span>{{ $seller->shop_name ?? '—' }}</span>
            </div>
            <div class="info-item">
                <strong>Phone</strong>
                <span>{{ $seller->phone ?? '—' }}</span>
            </div>
            <div class="info-item">
                <strong>Address</strong>
                <span>{{ $seller->business_address ?? '—' }}</span>
            </div>
            <div class="info-item">
                <strong>Business ID</strong>
                <span>{{ $seller->business_id_number ?? '—' }}</span>
            </div>
            <div class="info-item">
                <strong>Registered Since</strong>
                <span>{{ $seller->created_at ? $seller->created_at->format('M d, Y') : '—' }}</span>
            </div>
            <div class="info-item">
                <strong>Status</strong>
                <span class="badge badge-{{ $seller->seller_status === 'approved' ? 'success' : ($seller->seller_status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($seller->seller_status ?? 'pending') }}
                </span>
            </div>
        </div>
        @if($seller->business_notes)
        <div style="margin-top:1.5rem;padding:1rem;background:#f0f9ff;border-left:4px solid var(--main-color);border-radius:0.4rem;">
            <strong style="display:block;margin-bottom:0.5rem;color:var(--main-color);">Business Notes</strong>
            <p style="margin:0;color:#374151;font-size:1.4rem;white-space:pre-wrap;">{{ $seller->business_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Management Section -->
    <div class="info-card">
        <h3><i class="fas fa-cog" style="color:var(--main-color);margin-right:0.5rem;"></i> Seller Management</h3>
        <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
            <div>
                <label for="seller-status-select" style="font-size:1.4rem;color:#374151;display:block;margin-bottom:0.5rem;">Change Status</label>
                <select id="seller-status-select" style="padding:0.8rem 1.2rem;border:1px solid #d1d5db;border-radius:0.6rem;font-size:1.4rem;">
                    <option value="pending" @selected($seller->seller_status === 'pending')>Pending</option>
                    <option value="approved" @selected($seller->seller_status === 'approved')>Approved</option>
                    <option value="rejected" @selected($seller->seller_status === 'rejected')>Rejected</option>
                </select>
            </div>
            <button type="button" onclick="updateSellerStatus()" style="padding:0.8rem 2rem;background:var(--main-color);color:#fff;border:none;border-radius:0.6rem;font-size:1.4rem;cursor:pointer;font-weight:600;align-self:flex-end;">
                <i class="fas fa-save"></i> Update Status
            </button>
        </div>
    </div>

    <!-- Orders Section -->
    <h2 class="section-title">
        <i class="fas fa-shopping-bag"></i>
        Recent Orders ({{ $totalOrders }})
    </h2>

    <!-- Orders Filters -->
    <div style="background:white;padding:1.5rem;border-radius:1rem;margin-bottom:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Order Status</label>
                <select id="order_status" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Payment Status</label>
                <select id="payment_status" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="">All Payment</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Date Range</label>
                <select id="order_date_range" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="">All Time</option>
                    <option value="7days" {{ request('order_date_range') === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30days" {{ request('order_date_range') === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90days" {{ request('order_date_range') === '90days' ? 'selected' : '' }}>Last 90 Days</option>
                </select>
            </div>
            <button type="button" onclick="applyOrderFilters()" style="padding:0.6rem 1.5rem;background:var(--main-color);color:white;border:none;border-radius:0.5rem;font-size:1.3rem;font-weight:600;cursor:pointer;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button type="button" onclick="resetOrderFilters()" style="padding:0.6rem 1.5rem;background:#9ca3af;color:white;border:none;border-radius:0.5rem;font-size:1.3rem;font-weight:600;cursor:pointer;">
                <i class="fas fa-times"></i> Reset
            </button>
        </div>
    </div>

    <div data-filter="orders">
        @if($orders->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->name }}</td>
                                <td>${{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->status === 'delivered' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ ucfirst($order->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $order->payment_status === 'completed' || $order->payment_status === 'complete' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>{{ $order->placed_on ? date('M d, Y', strtotime($order->placed_on)) : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="table-wrapper">
                <div class="empty-message">
                    <i class="fas fa-inbox"></i>
                    <p>No orders yet from this seller's products</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Reviews Section -->
    <h2 class="section-title">
        <i class="fas fa-star"></i>
        Customer Reviews ({{ $totalReviews }})
    </h2>

    <!-- Reviews Filters -->
    <div style="background:white;padding:1.5rem;border-radius:1rem;margin-bottom:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Rating</label>
                <select id="review_rating" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('review_rating') === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5-Star</option>
                    <option value="4" {{ request('review_rating') === '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4-Star</option>
                    <option value="3" {{ request('review_rating') === '3' ? 'selected' : '' }}>⭐⭐⭐ 3-Star</option>
                    <option value="2" {{ request('review_rating') === '2' ? 'selected' : '' }}>⭐⭐ 2-Star</option>
                    <option value="1" {{ request('review_rating') === '1' ? 'selected' : '' }}>⭐ 1-Star</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Date Range</label>
                <select id="review_date_range" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="latest" {{ request('review_date_range') === 'latest' || !request('review_date_range') ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('review_date_range') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="7days" {{ request('review_date_range') === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30days" {{ request('review_date_range') === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                </select>
            </div>
            <div style="flex:1;min-width:200px;">
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Search Reviews</label>
                <input type="text" id="review_search" placeholder="Search by name or comment..." value="{{ request('review_search') }}" style="width:100%;padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
            </div>
            <button type="button" onclick="applyReviewFilters()" style="padding:0.6rem 1.5rem;background:var(--main-color);color:white;border:none;border-radius:0.5rem;font-size:1.3rem;font-weight:600;cursor:pointer;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button type="button" onclick="resetReviewFilters()" style="padding:0.6rem 1.5rem;background:#9ca3af;color:white;border:none;border-radius:0.5rem;font-size:1.3rem;font-weight:600;cursor:pointer;">
                <i class="fas fa-times"></i> Reset
            </button>
        </div>
    </div>

    <div data-filter="reviews">
        @if($reviews->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>
                                    <strong>{{ $review->user->name ?? 'Unknown' }}</strong>
                                    <br>
                                    <small style="color:#9ca3af;">{{ $review->user->email ?? '—' }}</small>
                                </td>
                                <td>{{ $review->product->name ?? '—' }}</td>
                                <td>
                                    <div class="rating-display">
                                        <span class="stars">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                ★
                                            @endfor
                                            @for($i = $review->rating; $i < 5; $i++)
                                                <span style="color:#d1d5db;">★</span>
                                            @endfor
                                        </span>
                                        <strong>{{ $review->rating }}/5</strong>
                                    </div>
                                </td>
                                <td>
                                    <p style="margin:0;font-size:1.3rem;">{{ Str::limit($review->comment, 50) }}</p>
                                </td>
                                <td>{{ $review->created_at ? $review->created_at->format('M d, Y') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="table-wrapper">
                <div class="empty-message">
                    <i class="fas fa-comment-slash"></i>
                    <p>No reviews yet for this seller's products</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Products Summary -->
    <h2 class="section-title">
        <i class="fas fa-box"></i>
        Products Listed ({{ $sellerProducts->count() }})
    </h2>

    <!-- Products Filters -->
    <div style="background:white;padding:1.5rem;border-radius:1rem;margin-bottom:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Stock Status</label>
                <select id="stock_status" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="">All Stock</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (>10)</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock (1-10)</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Price Min</label>
                <input type="number" id="price_min" placeholder="Min price" value="{{ request('price_min') }}" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;width:120px;">
            </div>
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Price Max</label>
                <input type="number" id="price_max" placeholder="Max price" value="{{ request('price_max') }}" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;width:120px;">
            </div>
            <div>
                <label style="display:block;font-size:1.2rem;font-weight:600;margin-bottom:0.4rem;color:#374151;">Sort By</label>
                <select id="product_sort" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.3rem;">
                    <option value="newest" {{ request('product_sort') === 'newest' || !request('product_sort') ? 'selected' : '' }}>Newest</option>
                    <option value="price_high" {{ request('product_sort') === 'price_high' ? 'selected' : '' }}>Price High to Low</option>
                    <option value="price_low" {{ request('product_sort') === 'price_low' ? 'selected' : '' }}>Price Low to High</option>
                </select>
            </div>
            <button type="button" onclick="applyProductFilters()" style="padding:0.6rem 1.5rem;background:var(--main-color);color:white;border:none;border-radius:0.5rem;font-size:1.3rem;font-weight:600;cursor:pointer;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button type="button" onclick="resetProductFilters()" style="padding:0.6rem 1.5rem;background:#9ca3af;color:white;border:none;border-radius:0.5rem;font-size:1.3rem;font-weight:600;cursor:pointer;">
                <i class="fas fa-times"></i> Reset
            </button>
        </div>
    </div>

    <div data-filter="products">
        @if($filteredProducts->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->type ?? '—' }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $product->stock > 10 ? 'badge-success' : ($product->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                                        {{ $product->stock }} in stock
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="table-wrapper">
                <div class="empty-message">
                    <i class="fas fa-store-slash"></i>
                    <p>This seller hasn't listed any products yet</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const sellerId = {{ $seller->id }};
    const sellerRoute = "{{ route('admin.sellers.show', $seller->id) }}";

    // ===== ORDER FILTERS =====
    function applyOrderFilters() {
        const filters = {
            order_status: document.getElementById('order_status').value,
            payment_status: document.getElementById('payment_status').value,
            order_date_range: document.getElementById('order_date_range').value
        };

        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.append(key, filters[key]);
        });

        const url = sellerRoute + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({ path: url }, '', url);

        fetchFilteredOrders(filters);
    }

    function resetOrderFilters() {
        document.getElementById('order_status').value = '';
        document.getElementById('payment_status').value = '';
        document.getElementById('order_date_range').value = '';
        window.history.pushState({ path: sellerRoute }, '', sellerRoute);
        fetchFilteredOrders({});
    }

    function fetchFilteredOrders(filters) {
        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.append(key, filters[key]);
        });

        fetch(sellerRoute + '?ajax_orders=1&' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            const newTable = newDoc.querySelector('[data-filter="orders"]');
            const oldTable = document.querySelector('[data-filter="orders"]');
            if (newTable && oldTable) {
                oldTable.innerHTML = newTable.innerHTML;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ===== REVIEW FILTERS =====
    function applyReviewFilters() {
        const filters = {
            review_rating: document.getElementById('review_rating').value,
            review_date_range: document.getElementById('review_date_range').value,
            review_search: document.getElementById('review_search').value
        };

        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.append(key, filters[key]);
        });

        const url = sellerRoute + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({ path: url }, '', url);

        fetchFilteredReviews(filters);
    }

    function resetReviewFilters() {
        document.getElementById('review_rating').value = '';
        document.getElementById('review_date_range').value = 'latest';
        document.getElementById('review_search').value = '';
        window.history.pushState({ path: sellerRoute }, '', sellerRoute);
        fetchFilteredReviews({});
    }

    function fetchFilteredReviews(filters) {
        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.append(key, filters[key]);
        });

        fetch(sellerRoute + '?ajax_reviews=1&' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            const newTable = newDoc.querySelector('[data-filter="reviews"]');
            const oldTable = document.querySelector('[data-filter="reviews"]');
            if (newTable && oldTable) {
                oldTable.innerHTML = newTable.innerHTML;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ===== PRODUCT FILTERS =====
    function applyProductFilters() {
        const filters = {
            stock_status: document.getElementById('stock_status').value,
            price_min: document.getElementById('price_min').value,
            price_max: document.getElementById('price_max').value,
            product_sort: document.getElementById('product_sort').value
        };

        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.append(key, filters[key]);
        });

        const url = sellerRoute + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({ path: url }, '', url);

        fetchFilteredProducts(filters);
    }

    function resetProductFilters() {
        document.getElementById('stock_status').value = '';
        document.getElementById('price_min').value = '';
        document.getElementById('price_max').value = '';
        document.getElementById('product_sort').value = 'newest';
        window.history.pushState({ path: sellerRoute }, '', sellerRoute);
        fetchFilteredProducts({});
    }

    function fetchFilteredProducts(filters) {
        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.append(key, filters[key]);
        });

        fetch(sellerRoute + '?ajax_products=1&' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            const newTable = newDoc.querySelector('[data-filter="products"]');
            const oldTable = document.querySelector('[data-filter="products"]');
            if (newTable && oldTable) {
                oldTable.innerHTML = newTable.innerHTML;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateSellerStatus() {
        const select = document.getElementById('seller-status-select');
        const sellerStatus = select.value;
        const url = "{{ url('admin/users/'.$seller->id.'/role') }}";

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ seller_status: sellerStatus })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Seller status updated',
                    text: 'The seller status has been successfully updated.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to update seller status.'
                });
            }
        }).catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while updating the status.'
            });
        });
    }
</script>
@endpush
