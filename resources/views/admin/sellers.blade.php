@extends('layouts.admin')

@section('title', 'Sellers Management - Admin Panel')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    :root {
        --panel-bg: #ffffff;
        --page-bg: #f6f7f9;
        --text: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;
        --shadow: 0 1px 2px rgba(17, 24, 39, 0.06);
        --radius: 10px;
        --radius-sm: 8px;
    }

    .sellers-container {
        padding: 1.6rem;
        background: var(--panel-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.6rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid var(--border);
    }

    .page-header h1 {
        font-size: 2.2rem;
        color: var(--text);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin: 0;
        line-height: 1.2;
    }

    .page-header h1 i {
        color: var(--text);
        opacity: 0.9;
    }

    .sellers-count {
        font-size: 1.3rem;
        color: var(--muted);
        background: #f9fafb;
        border: 1px solid var(--border);
        padding: 0.7rem 1.1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        white-space: nowrap;
    }

    .status-filters {
        margin-bottom: 1.2rem;
        display: inline-flex;
        gap: 0.4rem;
        flex-wrap: wrap;
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 0.4rem;
    }

    .status-filters a {
        padding: 0.6rem 1.0rem;
        border-radius: 7px;
        border: 1px solid transparent;
        font-size: 1.3rem;
        text-decoration: none;
        color: #374151;
        font-weight: 600;
        background: transparent;
    }

    .status-filters a:hover {
        background: #ffffff;
        border-color: var(--border);
    }

    .status-filters a.active {
        background: rgba(58, 199, 45, 0.14);
        color: #166534;
        border-color: rgba(58, 199, 45, 0.22);
    }

    .sellers-table-wrapper {
        background: var(--panel-bg);
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
    }

    .sellers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sellers-table thead {
        background: #f3f4f6;
    }

    .sellers-table thead th {
        padding: 1.2rem 1.4rem;
        text-align: left;
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        text-transform: none;
        letter-spacing: 0.02em;
        border-bottom: 1px solid var(--border);
    }

    .sellers-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s ease;
    }

    .sellers-table tbody tr:hover {
        background: #f9fafb;
    }

    .sellers-table tbody td {
        padding: 1.2rem 1.4rem;
        font-size: 1.4rem;
        color: #374151;
        vertical-align: middle;
    }

    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        font-size: 1.2rem;
        text-transform: capitalize;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .badge-status.pending {
        background: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }

    .badge-status.approved {
        background: rgba(58, 199, 45, 0.10);
        color: #166534;
        border-color: rgba(58, 199, 45, 0.22);
    }

    .badge-status.rejected {
        background: rgba(231, 76, 60, 0.10);
        color: #991b1b;
        border-color: rgba(231, 76, 60, 0.24);
    }

    /* Link in table */
    #sellers a {
        color: #166534 !important;
        font-weight: 600;
        text-decoration: none;
    }
    #sellers a:hover {
        text-decoration: underline;
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
                        <th>Status</th>
                        <th>Profile</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sellers as $seller)
                        <tr data-seller-id="{{ $seller->id }}">
                            <td>{{ $seller->shop_name ?? '—' }}</td>
                            <td>{{ $seller->name }}</td>
                            <td>
                                <span class="badge-status {{ $seller->status ?? 'pending' }}">
                                    {{ ucfirst($seller->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sellers.show', $seller->id) }}" style="font-size:1.3rem;color:var(--main-color);text-decoration:none;">
                                    View Profile
                                </a>
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
