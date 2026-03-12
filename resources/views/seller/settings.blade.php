@extends('layouts.seller')

@section('title', 'Seller Settings')

@push('styles')
<style>
    .settings-container { max-width: 1100px; margin: 0 auto; }
    .tab-nav { display: flex; gap: .5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .tab-link { padding: .8rem 1.2rem; border-radius: 8px; text-decoration: none; color: #374151; background: #f3f4f6; font-weight: 600; }
    .tab-link.active { color: #fff; background: #059669; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem; }
    .form-group { margin-bottom: 1rem; }
    .form-control { width: 100%; padding: .75rem .9rem; border: 1px solid #d1d5db; border-radius: 8px; }
    .btn { border: 0; border-radius: 8px; padding: .75rem 1.1rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; }
    .btn-primary { color: #fff; background: #059669; }
    .btn-secondary { color: #fff; background: #2563eb; }
    .alert { border-radius: 10px; padding: .9rem 1rem; margin-bottom: 1rem; }
    .alert-danger { background: #fee2e2; color: #991b1b; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-info { background: #dbeafe; color: #1e40af; }
    .badge { padding: .25rem .55rem; border-radius: 999px; font-size: .78rem; font-weight: 700; }
    .badge.bg-success { background: #10b981; color: #fff; }
    .badge.bg-danger { background: #ef4444; color: #fff; }
    .badge.bg-warning { background: #f59e0b; color: #fff; }
    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border-bottom: 1px solid #e5e7eb; padding: .75rem; text-align: left; }
    .table th { background: #f9fafb; font-size: .82rem; text-transform: uppercase; color: #6b7280; }
</style>
@endpush

@section('content')
<div class="settings-container">
    <h2 style="margin-bottom:.35rem;"><i class="fas fa-cog"></i> Account Settings</h2>
    <p style="margin-top:0;color:#6b7280;">Manage your profile, business, and subscription billing.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error:</strong>
            <ul style="margin:.4rem 0 0 1.2rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="tab-nav">
        <a href="#" class="tab-link active" data-tab="profile"><i class="fas fa-user"></i> Profile</a>
        <a href="#" class="tab-link" data-tab="business"><i class="fas fa-store"></i> Business</a>
        <a href="#" class="tab-link" data-tab="subscription"><i class="fas fa-credit-card"></i> Subscription</a>
    </div>

    <div class="tab-content active" id="profile">
        <div class="card">
            <h4>Profile Information</h4>
            <form action="{{ route('seller.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="section" value="profile">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $seller->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $seller->email) }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number (Optional)</label>
                    <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $seller->phone ?? '') }}">
                </div>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save Profile</button>
            </form>
        </div>

        <div class="card">
            <h4>Change Password</h4>
            <form action="{{ route('seller.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="section" value="password">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input class="form-control" type="password" id="current_password" name="current_password">
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input class="form-control" type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation">
                </div>
                <button class="btn btn-primary" type="submit"><i class="fas fa-key"></i> Update Password</button>
            </form>
        </div>
    </div>

    <div class="tab-content" id="business">
        <div class="card">
            <h4>Business Information</h4>
            <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="section" value="business">
                <div class="form-group">
                    <label for="shop_logo">Update Shop Logo</label>
                    <input class="form-control" type="file" id="shop_logo" name="shop_logo" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="shop_name">Shop Name</label>
                    <input class="form-control" type="text" id="shop_name" name="shop_name" value="{{ old('shop_name', $seller->shop_name) }}" required>
                </div>
                <div class="form-group">
                    <label for="shop_description">Shop Description</label>
                    <textarea class="form-control" id="shop_description" name="shop_description" rows="4">{{ old('shop_description', $seller->shop_description ?? '') }}</textarea>
                </div>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save Business Info</button>
            </form>
        </div>
    </div>

    <div class="tab-content" id="subscription">
        <div class="card">
            <h4>Subscription Status</h4>
            @if ($subscription)
                <p><strong>Status:</strong>
                    @if ($subscription->isExpired() || $seller->subscription_status === 'suspended')
                        <span class="badge bg-danger">{{ $seller->subscription_status === 'suspended' ? 'Suspended' : 'Expired' }}</span>
                    @elseif ($subscription->isActive())
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-warning">Inactive</span>
                    @endif
                </p>
                <p><strong>Type:</strong> {{ ucfirst($subscription->subscription_type) }}</p>
                <p><strong>Monthly Amount:</strong> PHP {{ number_format($subscription->amount, 2) }}</p>
                <p><strong>Expires On:</strong> {{ $subscription->end_date->format('M d, Y') }}</p>

                @php $daysUntilExpiry = now()->diffInDays($subscription->end_date, false); @endphp
                @if ($daysUntilExpiry <= 0)
                    <div class="alert alert-danger">Your subscription has expired. Submit payment now to restore access.</div>
                @else
                    <div class="alert alert-info">Your subscription expires in {{ $daysUntilExpiry }} day{{ $daysUntilExpiry !== 1 ? 's' : '' }}.</div>
                @endif

                <a href="{{ route('seller.subscription.pay-rent.form') }}" class="btn btn-secondary"><i class="fas fa-money-bill-wave"></i> Pay Monthly Rent (GCash)</a>
            @else
                <div class="alert alert-danger">No subscription record found. Please contact support.</div>
            @endif
        </div>

        <div class="card">
            <h4>Payment History</h4>
            @if ($payments->count() > 0)
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Reference</th>
                                <th>GCash</th>
                                <th>Proof</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ ($payment->paid_at ?? $payment->created_at)?->format('M d, Y') ?? '-' }}</td>
                                    <td>{{ ucfirst($payment->payment_type ?? 'subscription') }}</td>
                                    <td>PHP {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', (string) $payment->payment_method)) }}</td>
                                    <td>
                                        @if ($payment->isCompleted())
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning">{{ ucfirst((string) $payment->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->reference_number ?? '-' }}</td>
                                    <td>{{ $payment->gcash_number_used ?? '-' }}</td>
                                    <td>
                                        @if (!empty($payment->proof_image))
                                            <a href="{{ asset('uploaded_img/' . $payment->proof_image) }}" target="_blank">View</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:1rem;">{{ $payments->links() }}</div>
            @else
                <p style="color:#6b7280;">No payment history available.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
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

if (window.location.hash) {
    activateTab(window.location.hash.replace('#', ''));
}
</script>
@endpush
@endsection

