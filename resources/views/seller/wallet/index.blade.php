@extends('layouts.seller')

@section('title', 'My Wallet')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-wallet me-2"></i>Seller Wallet</h2>
            <small class="text-muted">Manage your balance and transactions</small>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Wallet Balance Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <p class="card-text text-muted mb-2">Current Balance</p>
                    <h3 class="text-success mb-0">${{ number_format($wallet->balance, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <p class="card-text text-muted mb-2">Total Deposited</p>
                    <h4 class="text-info mb-0">${{ number_format($wallet->total_deposited, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <p class="card-text text-muted mb-2">Total Withdrawn</p>
                    <h4 class="text-danger mb-0">${{ number_format($wallet->total_withdrawn, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Actions</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('seller.wallet.deposit.form') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>Add Funds
                        </a>
                        <a href="{{ route('seller.wallet.pay-rent.form') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Pay Monthly Rent
                        </a>
                        <a href="{{ route('seller.wallet.withdraw.form') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-arrow-up me-2"></i>Withdraw
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Transaction History</h5>
                </div>
                <div class="card-body">
                    @if ($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance After</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $txn)
                                        <tr>
                                            <td>
                                                <small>{{ $txn->created_at->format('M d, Y') }}<br>{{ $txn->created_at->format('H:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $txn->getTypeBadgeColor() }}">
                                                    {{ $txn->getTypeLabel() }}
                                                </span>
                                            </td>
                                            <td class="fw-bold">${{ number_format($txn->amount, 2) }}</td>
                                            <td>
                                                <span class="text-success">${{ number_format($txn->balance_after, 2) }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $txn->description ?? '-' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No transactions yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
