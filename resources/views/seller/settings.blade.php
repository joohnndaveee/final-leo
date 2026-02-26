@extends('layouts.seller')

@section('title', 'Seller Settings')

@push('styles')
<style>
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .settings-header {
        margin-bottom: 2rem;
    }

    .settings-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #064e3b;
        margin: 0 0 0.5rem 0;
    }

    .settings-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
    }
    
    /* Horizontal Tabs */
    .tab-nav {
        display: flex;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 0.75rem;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.1);
        margin-bottom: 2rem;
        overflow-x: auto;
        flex-wrap: wrap;
    }

    .tab-nav::-webkit-scrollbar {
        height: 4px;
    }

    .tab-nav::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 2px;
    }
    
    .tab-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.9rem 1.5rem;
        color: #6b7280;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.2s ease;
        white-space: nowrap;
        border: 2px solid transparent;
    }
    
    .tab-link:hover {
        background: #f3f4f6;
        color: #374151;
    }
    
    .tab-link.active {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(5, 150, 105, 0.95));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    .tab-link i {
        font-size: 1.1rem;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(15px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    .settings-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.1);
        margin-bottom: 2rem;
    }
    
    .settings-card h5 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #064e3b;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .settings-card h5 i {
        color: #10b981;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.9rem;
        color: #374151;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s;
        background: #fafafa;
    }
    
    .form-control:focus {
        outline: none;
        border-color: rgba(16, 185, 129, 0.5);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .btn {
        padding: 0.85rem 1.8rem;
        font-size: 0.95rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(5, 150, 105, 0.95));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: white;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        display: flex;
        align-items: start;
        gap: 0.75rem;
    }

    .alert i {
        margin-top: 0.2rem;
    }
    
    .alert-success {
        background: rgba(209, 250, 229, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: rgba(254, 226, 226, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .alert-info {
        background: rgba(219, 234, 254, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
    
    .alert-warning {
        background: rgba(254, 243, 199, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }
    
    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge.bg-success {
        background: #10b981;
        color: white;
    }
    
    .badge.bg-danger {
        background: #ef4444;
        color: white;
    }
    
    .badge.bg-warning {
        background: #f59e0b;
        color: white;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .table thead th {
        background: #f9fafb;
        padding: 1rem;
        text-align: left;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table tbody td {
        padding: 1rem;
        font-size: 0.9rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table tbody tr:hover {
        background: #fafafa;
    }
    
    .wallet-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .wallet-stat {
        background: linear-gradient(135deg, rgba(249, 250, 251, 0.85), rgba(255, 255, 255, 0.85));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        border: 2px solid rgba(16, 185, 129, 0.1);
        transition: all 0.2s;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.05);
    }

    .wallet-stat:hover {
        border-color: rgba(16, 185, 129, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.15);
    }
    
    .wallet-stat p {
        margin: 0 0 0.5rem 0;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .wallet-stat h6 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 800;
        color: #1f2937;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .tab-nav {
            flex-wrap: nowrap;
        }

        .wallet-stats {
            grid-template-columns: 1fr;
        }

        .settings-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <h2 class="settings-title"><i class="fas fa-cog"></i> Account Settings</h2>
        <p class="settings-subtitle">Manage your profile, business, subscription, and wallet</p>
    </div>

    <!-- Error/Success Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Error!</strong>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Horizontal Navigation Tabs -->
    <div class="tab-nav">
        <a href="#" class="tab-link active" data-tab="profile">
            <i class="fas fa-user"></i>Profile
        </a>
        <a href="#" class="tab-link" data-tab="business">
            <i class="fas fa-store"></i>Business
        </a>
        <a href="#" class="tab-link" data-tab="subscription">
            <i class="fas fa-credit-card"></i>Subscription
        </a>
        <a href="#" class="tab-link" data-tab="wallet">
            <i class="fas fa-wallet"></i>Wallet
        </a>
    </div>

    <!-- Content Area -->
    <div>
        <!-- Profile Tab -->
        <div class="tab-content active" id="profile">
            <div class="settings-card">
                    <h5><i class="fas fa-user"></i> Profile Information</h5>
                    <form action="{{ route('seller.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="profile">

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" 
                                   id="name" name="name" value="{{ old('name', $seller->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" 
                                   id="email" name="email" value="{{ old('email', $seller->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number (Optional)</label>
                            <input type="text" class="form-control" 
                                   id="phone" name="phone" value="{{ old('phone', $seller->phone ?? '') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>Save Profile
                        </button>
                    </form>

                    <!-- Change Password -->
                    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">
                    <h6 style="font-size: 1.6rem; margin-bottom: 1rem;">Change Password</h6>
                    <form action="{{ route('seller.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="password">

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" 
                                   id="current_password" name="current_password">
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" 
                                   id="password" name="password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Business Tab -->
            <div class="tab-content" id="business">
                <div class="settings-card">
                    <h5><i class="fas fa-store"></i> Business Information</h5>
                    <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="business">

                        <div class="form-group">
                            <label class="form-label">Current Shop Logo</label>
                            <div style="display:flex;align-items:center;gap:1rem;">
                                <div style="width:64px;height:64px;border-radius:10px;background:#f9fafb;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                    @php
                                        $logoPath = !empty($seller->shop_logo) ? asset('uploaded_img/' . $seller->shop_logo) : ($siteLogoUrl ?? asset('images/logo.png'));
                                    @endphp
                                    <img src="{{ $logoPath }}" alt="Shop Logo" style="width:100%;height:100%;object-fit:contain;">
                                </div>
                                <div style="font-size:1.3rem;color:#6b7280;">
                                    Upload a square logo for best results.
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shop_logo" class="form-label">Update Shop Logo</label>
                            <input type="file" class="form-control" id="shop_logo" name="shop_logo" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="shop_name" class="form-label">Shop Name</label>
                            <input type="text" class="form-control" 
                                   id="shop_name" name="shop_name" value="{{ old('shop_name', $seller->shop_name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="shop_description" class="form-label">Shop Description</label>
                            <textarea class="form-control" 
                                      id="shop_description" name="shop_description" rows="4">{{ old('shop_description', $seller->shop_description ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>Save Business Info
                        </button>
                    </form>
                </div>
            </div>

            <!-- Subscription & Billing Tab -->
            <div class="tab-content" id="subscription">
                <div class="settings-card" style="margin-bottom: 2rem;">
                    <h5><i class="fas fa-credit-card"></i> Subscription Status</h5>
                    @if ($subscription)
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <p style="margin-bottom: 0.5rem; color: #6b7280;"><strong>Status:</strong></p>
                                @if ($subscription->isExpired())
                                    <span class="badge bg-danger">Expired</span>
                                @elseif ($seller->subscription_status === 'suspended')
                                    <span class="badge bg-danger">Suspended</span>
                                @elseif ($subscription->isActive())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Inactive</span>
                                @endif
                            </div>
                            <div>
                                <p style="margin-bottom: 0.5rem; color: #6b7280;"><strong>Subscription Type:</strong></p>
                                <span style="text-transform: capitalize;">{{ $subscription->subscription_type }}</span>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <p style="margin-bottom: 0.5rem; color: #6b7280;"><strong>Monthly Amount:</strong></p>
                                <span style="color: #10b981; font-weight: 700; font-size: 1.8rem;">₱{{ number_format($subscription->amount, 2) }}</span>
                            </div>
                            <div>
                                <p style="margin-bottom: 0.5rem; color: #6b7280;"><strong>Expires On:</strong></p>
                                <span style="color: #6b7280;">{{ $subscription->end_date->format('M d, Y') }}</span>
                            </div>
                        </div>

                        @php
                            $daysUntilExpiry = now()->diffInDays($subscription->end_date, false);
                        @endphp

                        @if ($daysUntilExpiry > 0)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Your subscription expires in <strong>{{ $daysUntilExpiry }} day{{ $daysUntilExpiry !== 1 ? 's' : '' }}</strong>
                            </div>
                        @elseif ($daysUntilExpiry <= 0)
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                Your subscription has expired. Please renew immediately to continue selling.
                            </div>
                        @endif

                        <div class="action-buttons">
                            <a href="{{ route('seller.wallet.pay-rent.form') }}" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i>Pay Rent Now
                            </a>
                            <a href="{{ route('seller.wallet.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                <i class="fas fa-wallet"></i>Check Wallet
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            You don't have an active subscription yet. Please contact support to set up your subscription.
                        </div>
                    @endif
                </div>

                <!-- Payment History -->
                <div class="settings-card">
                    <h5><i class="fas fa-history"></i> Payment History</h5>
                    @if ($payments->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->paid_at?->format('M d, Y') ?? 'N/A' }}</td>
                                            <td><strong>₱{{ number_format($payment->amount, 2) }}</strong></td>
                                            <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                            <td>
                                                @if ($payment->isCompleted())
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">{{ $payment->payment_status }}</span>
                                                @endif
                                            </td>
                                            <td><small style="color: #6b7280;">{{ $payment->reference_number ?? '-' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="display: flex; justify-content: center; margin-top: 1rem;">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <p style="text-align: center; color: #6b7280; padding: 2rem 0;">No payment history available</p>
                    @endif
                </div>
            </div>

            <!-- Wallet Tab -->
            <div class="tab-content" id="wallet">
                <div class="settings-card" style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h5 style="margin: 0;"><i class="fas fa-wallet"></i> Wallet Balance</h5>
                        <h5 style="margin: 0; color: #10b981;">₱{{ number_format($wallet->balance, 2) }}</h5>
                    </div>
                    
                    <div class="wallet-stats">
                        <div class="wallet-stat" style="border-left: 4px solid #10b981;">
                            <p>Total Deposited</p>
                            <h6 style="color: #10b981;">₱{{ number_format($wallet->total_deposited, 2) }}</h6>
                        </div>
                        <div class="wallet-stat" style="border-left: 4px solid #ef4444;">
                            <p>Total Withdrawn</p>
                            <h6 style="color: #ef4444;">₱{{ number_format($wallet->total_withdrawn, 2) }}</h6>
                        </div>
                    </div>

                    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">

                    <div class="action-buttons">
                        <a href="{{ route('seller.wallet.deposit.form') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-plus"></i>Add Funds
                        </a>
                        <a href="{{ route('seller.wallet.pay-rent.form') }}" class="btn btn-primary">
                            <i class="fas fa-money-bill"></i>Pay Rent
                        </a>
                        <a href="{{ route('seller.wallet.withdraw.form') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-arrow-up"></i>Withdraw
                        </a>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="settings-card">
                    <h5><i class="fas fa-receipt"></i> Transaction History</h5>
                    @if ($transactions->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $txn)
                                        <tr>
                                            <td>{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $txn->getTypeBadgeColor() }}">
                                                    {{ $txn->getTypeLabel() }}
                                                </span>
                                            </td>
                                            <td style="font-weight: 700;">₱{{ number_format($txn->amount, 2) }}</td>
                                            <td style="color: #6b7280;">₱{{ number_format($txn->balance_after, 2) }}</td>
                                            <td><small style="color: #6b7280;">{{ $txn->description ?? '-' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="display: flex; justify-content: center; margin-top: 1rem;">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <p style="text-align: center; color: #6b7280; padding: 2rem 0;">No transactions yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Vanilla JavaScript Tab Switching
function activateTab(targetTab) {
    if (!targetTab) return;
    const targetContent = document.getElementById(targetTab);
    if (!targetContent) return;

    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

    const targetLink = document.querySelector(`.tab-link[data-tab="${targetTab}"]`);
    if (targetLink) targetLink.classList.add('active');
    targetContent.classList.add('active');
}

document.querySelectorAll('.tab-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetTab = link.dataset.tab;
        activateTab(targetTab);
        history.replaceState(null, '', `#${targetTab}`);
    });
});

// Activate tab from URL hash (e.g. /seller/settings#subscription)
if (window.location.hash) {
    activateTab(window.location.hash.replace('#', ''));
}
</script>
@endpush
@endsection
