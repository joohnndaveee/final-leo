@extends('layouts.admin')

@section('title', 'Manage Orders - Admin Panel')

@push('styles')
<style>
        .orders-table-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 2rem 0;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table thead {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }

        .orders-table th {
            padding: 1.5rem;
            text-align: left;
            font-size: 1.4rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .orders-table td {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #eee;
            font-size: 1.3rem;
            vertical-align: middle;
        }

        .orders-table tbody tr:hover {
            background: #f8f9fa;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-id {
            font-weight: 700;
            color: #27ae60;
            font-size: 1.5rem;
        }

        .customer-info {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .customer-name {
            font-weight: 600;
            color: #333;
        }

        .customer-phone {
            color: #666;
            font-size: 1.2rem;
        }

        .order-date {
            color: #666;
        }

        .order-total {
            font-weight: 700;
            color: #27ae60;
            font-size: 1.5rem;
        }

        .status-dropdown {
            padding: 0.6rem 1rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1.3rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: capitalize;
        }

        .status-dropdown:focus {
            outline: none;
            border-color: #27ae60;
        }

        .status-dropdown.pending {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
        }

        .status-dropdown.completed {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .status-dropdown.cancelled {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            padding: 0.6rem 1.2rem;
            background: #17a2b8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            background: #138496;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-state i {
            font-size: 8rem;
            color: #ddd;
            margin-bottom: 2rem;
        }

        .empty-state h2 {
            font-size: 2.5rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.5rem;
            color: #999;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #27ae60;
        }

        .stat-card h3 {
            font-size: 1.4rem;
            color: #666;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #27ae60;
        }

        @media (max-width: 1200px) {
            .orders-table {
                font-size: 1.2rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 1rem;
            }
        }

        @media (max-width: 768px) {
            .orders-table-container {
                overflow-x: auto;
            }

            .orders-table {
                min-width: 800px;
            }

            .stats-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <h1 class="heading">Manage Customer Orders</h1>

    <!-- Statistics Summary -->
    <div class="stats-summary">
        <div class="stat-card">
            <h3>Total Orders</h3>
            <div class="stat-value">{{ $orders->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Pending Orders</h3>
            <div class="stat-value">{{ $orders->where('payment_status', 'pending')->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Total Sales</h3>
            <div class="stat-value">₱{{ number_format($orders->sum('total_price'), 2) }}</div>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="fas fa-shopping-bag"></i>
            <h2>No Orders Yet</h2>
            <p>Customer orders will appear here once they start placing orders.</p>
        </div>
    @else
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="order-id">#{{ $order->id }}</td>
                            <td>
                                <div class="customer-info">
                                    <span class="customer-name">{{ $order->name }}</span>
                                    <span class="customer-phone"><i class="fas fa-phone"></i> {{ $order->number }}</span>
                                </div>
                            </td>
                            <td class="order-date">{{ date('M d, Y', strtotime($order->placed_on)) }}</td>
                            <td class="order-total">₱{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->method }}</td>
                            <td>
                                <select class="status-dropdown {{ $order->payment_status }}" 
                                        data-order-id="{{ $order->id }}"
                                        onchange="updateOrderStatus(this)">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.order.details', $order->id) }}" class="view-btn">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateOrderStatus(selectElement) {
        const orderId = selectElement.dataset.orderId;
        const newStatus = selectElement.value;
        const oldClass = selectElement.className.split(' ')[1]; // Get old status class

        // Show loading state
        selectElement.disabled = true;

        fetch(`/admin/orders/${orderId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update the dropdown class
                selectElement.classList.remove(oldClass);
                selectElement.classList.add(newStatus);

                // Show success message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to update order status'
            });

            // Revert the selection
            selectElement.value = oldClass;
        })
        .finally(() => {
            selectElement.disabled = false;
        });
    }
</script>
@endpush
