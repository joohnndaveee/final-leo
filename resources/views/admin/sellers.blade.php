@extends('layouts.admin')

@section('title', 'Sellers Management - Admin Panel')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .sellers-container {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid var(--main-color);
    }

    .page-header h1 {
        font-size: 3rem;
        color: var(--black);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header h1 i {
        color: var(--main-color);
    }

    .sellers-count {
        font-size: 1.6rem;
        color: var(--light-color);
        background: rgba(58, 199, 45, 0.1);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .status-filters {
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
    }

    .status-filters a {
        padding: 0.6rem 1.2rem;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        font-size: 1.3rem;
        text-decoration: none;
        color: #374151;
    }

    .status-filters a.active {
        background: var(--main-color);
        color: #fff;
        border-color: var(--main-color);
    }

    .sellers-table-wrapper {
        background: var(--white);
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .sellers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sellers-table thead {
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    }

    .sellers-table thead th {
        padding: 2rem;
        text-align: left;
        font-size: 1.6rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sellers-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .sellers-table tbody tr:hover {
        background: rgba(58, 199, 45, 0.05);
    }

    .sellers-table tbody td {
        padding: 2rem;
        font-size: 1.5rem;
        color: #4b5563;
    }

    .badge-status {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        font-size: 1.2rem;
        text-transform: capitalize;
    }

    .badge-status.pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-status.approved {
        background: #d4edda;
        color: #155724;
    }

    .badge-status.rejected {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@section('content')
<div class="sellers-container" id="sellers">
    <div class="page-header">
        <h1>
            <i class="fas fa-store"></i>
            Sellers
        </h1>
        <div class="sellers-count">
            <i class="fas fa-user-tie"></i>
            {{ $sellers->count() }} {{ $sellers->count() === 1 ? 'Seller' : 'Sellers' }}
        </div>
    </div>

    <div class="status-filters">
        @php $status = request('status'); @endphp
        <a href="{{ route('admin.sellers') }}" class="{{ $status === null ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.sellers', ['status' => 'pending']) }}" class="{{ $status === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('admin.sellers', ['status' => 'approved']) }}" class="{{ $status === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('admin.sellers', ['status' => 'rejected']) }}" class="{{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>
    </div>

    @if($sellers->count() > 0)
        <div class="sellers-table-wrapper">
            <table class="sellers-table">
                <thead>
                    <tr>
                        <th>Shop</th>
                        <th>Owner</th>
                        <th>Application</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sellers as $seller)
                        <tr data-seller-id="{{ $seller->id }}">
                            <td>{{ $seller->shop_name ?? '—' }}</td>
                            <td>{{ $seller->name }}</td>
                            <td>
                                <a href="{{ route('admin.sellers.show', $seller->id) }}" style="font-size:1.3rem;color:var(--main-color);text-decoration:none;">
                                    View full application
                                </a>
                            </td>
                            <td>
                                <span class="badge-status {{ $seller->seller_status ?? 'pending' }}">
                                    {{ ucfirst($seller->seller_status ?? 'pending') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-users">
            <i class="fas fa-user-slash"></i>
            <h2>No Sellers Yet</h2>
            <p>Sellers who apply will appear here.</p>
        </div>
    @endif
</div>
@endsection

