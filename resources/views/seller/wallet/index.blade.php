@extends('layouts.seller')

@section('title', 'My Wallet')

@push('styles')
<style>
    .wallet-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .wallet-header {
        margin-bottom: 2.5rem;
    }

    .wallet-title {
        font-size: 2.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .wallet-subtitle {
        color: #6b7280;
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Alert Styles */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(236, 253, 245, 0.9) 0%, rgba(209, 250, 229, 0.9) 100%);
        color: #065f46;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(254, 226, 226, 0.9) 0%, rgba(254, 202, 202, 0.9) 100%);
        color: #991b1b;
        border-color: rgba(239, 68, 68, 0.3);
    }

    /* Balance Cards */
    .balance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .balance-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .balance-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .balance-card.primary::before {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .balance-card.info::before {
        background: linear-gradient(90deg, #3b82f6, #2563eb);
    }

    .balance-card.warning::before {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .balance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(16, 185, 129, 0.2);
    }

    .balance-label {
        color: #6b7280;
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .balance-amount {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1px;
    }

    .balance-card.primary .balance-amount {
        color: #10b981;
    }

    .balance-card.info .balance-amount {
        color: #3b82f6;
    }

    .balance-card.warning .balance-amount {
        color: #ef4444;
    }

    /* Action Section */
    .action-section {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.1);
        margin-bottom: 2.5rem;
    }

    .action-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #064e3b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 1rem 2rem;
        border-radius: 14px;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .action-btn.primary {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(5, 150, 105, 0.95));
        color: white;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    .action-btn.secondary {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.95), rgba(37, 99, 235, 0.95));
        color: white;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }

    .action-btn.tertiary {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.95), rgba(217, 119, 6, 0.95));
        color: white;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    }

    /* Transaction History */
    .transaction-section {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .transaction-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #064e3b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .transaction-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .transaction-table thead th {
        text-align: left;
        padding: 1rem;
        font-weight: 700;
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: linear-gradient(135deg, rgba(240, 253, 244, 0.9) 0%, rgba(220, 252, 231, 0.9) 100%);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 2px solid rgba(16, 185, 129, 0.1);
    }

    .transaction-table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .transaction-table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .transaction-table tbody tr {
        transition: all 0.2s;
    }

    .transaction-table tbody tr:hover {
        background: rgba(240, 253, 244, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .transaction-table tbody td {
        padding: 1.2rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.95rem;
    }

    .type-badge {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
        border: 1px solid;
    }

    .type-badge.deposit {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .type-badge.withdrawal {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .type-badge.rent_payment {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .type-badge.earning {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .amount-text {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .balance-after {
        color: #10b981;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state p {
        font-size: 1.2rem;
        color: #6b7280;
        margin: 1rem 0 0 0;
    }

    @media (max-width: 768px) {
        .wallet-title {
            font-size: 2rem;
        }

        .balance-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }

        .transaction-table {
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
<div class="wallet-container">
    <div class="wallet-header">
        <h1 class="wallet-title">
            <i class="fas fa-wallet"></i>
            My Wallet
        </h1>
        <p class="wallet-subtitle">Manage your balance and transactions</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Balance Cards -->
    <div class="balance-grid">
        <div class="balance-card primary">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">₱{{ number_format($wallet->balance, 2) }}</div>
        </div>
        <div class="balance-card info">
            <div class="balance-label">Total Deposited</div>
            <div class="balance-amount">₱{{ number_format($wallet->total_deposited, 2) }}</div>
        </div>
        <div class="balance-card warning">
            <div class="balance-label">Total Withdrawn</div>
            <div class="balance-amount">₱{{ number_format($wallet->total_withdrawn, 2) }}</div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-section">
        <h3 class="action-title">
            <i class="fas fa-lightning-bolt"></i>
            Quick Actions
        </h3>
        <div class="action-buttons">
            <a href="{{ route('seller.wallet.deposit.form') }}" class="action-btn primary">
                <i class="fas fa-plus"></i>
                Add Funds
            </a>
            <a href="{{ route('seller.wallet.pay-rent.form') }}" class="action-btn secondary">
                <i class="fas fa-credit-card"></i>
                Pay Monthly Rent
            </a>
            <a href="{{ route('seller.wallet.withdraw.form') }}" class="action-btn tertiary">
                <i class="fas fa-arrow-up"></i>
                Withdraw
            </a>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="transaction-section">
        <div class="transaction-header">
            <h2 class="transaction-title">
                <i class="fas fa-history"></i>
                Transaction History
            </h2>
        </div>

        @if ($transactions->count() > 0)
            <table class="transaction-table">
                <thead>
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
                                <div>{{ $txn->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.85rem; color: #9ca3af;">{{ $txn->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="type-badge {{ strtolower(str_replace(' ', '_', $txn->getTypeLabel())) }}">
                                    {{ $txn->getTypeLabel() }}
                                </span>
                            </td>
                            <td class="amount-text">₱{{ number_format($txn->amount, 2) }}</td>
                            <td>
                                <span class="balance-after">₱{{ number_format($txn->balance_after, 2) }}</span>
                            </td>
                            <td style="color: #6b7280;">{{ $txn->description ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No transactions yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
