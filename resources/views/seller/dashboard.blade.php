@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')
<section class="container" style="max-width:1100px;margin:2rem auto;">
    {{-- Subscription Status Warning Banner --}}
    @if($seller->isSubscriptionDisabled())
        <div style="background: linear-gradient(135deg, #dc3545, #e74c3c); color: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.4rem;">⚠️ Subscription Status: {{ ucfirst($seller->subscription_status) }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 1.2rem;">Your subscription has {{ $seller->subscription_status }}. You cannot access selling features until you renew your subscription.</p>
                </div>
                <a href="{{ route('seller.settings') }}" style="background: white; color: #dc3545; padding: 0.8rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 1.1rem; white-space: nowrap;">
                    Renew Now
                </a>
            </div>
        </div>
    @elseif($seller->isSubscriptionExpiringSoon())
        <div style="background: linear-gradient(135deg, #ffc107, #ff9800); color: #fff; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3); position: sticky; top: 80px; z-index: 50;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.4rem;">⏰ Subscription Expiring Soon</h3>
                    <p style="margin: 0; opacity: 0.95; font-size: 1.2rem;">Your subscription expires in {{ $seller->getDaysUntilExpiration() }} days. Renew now to avoid service interruption.</p>
                </div>
                <a href="{{ route('seller.settings') }}" style="background: white; color: #ff9800; padding: 0.8rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 1.1rem; white-space: nowrap;">
                    Renew Now
                </a>
            </div>
        </div>
    @endif

    <h1 style="font-size:2.4rem;font-weight:700;margin-bottom:1.5rem;">Seller Dashboard</h1>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;">
        <div style="background:#fff;padding:1.5rem;border-radius:0.8rem;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <p style="color:#666;font-size:1.4rem;">Products</p>
            <h3 style="font-size:2.4rem;font-weight:700;">{{ $productsCount }}</h3>
        </div>
        <div style="background:#fff;padding:1.5rem;border-radius:0.8rem;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <p style="color:#666;font-size:1.4rem;">Orders</p>
            <h3 style="font-size:2.4rem;font-weight:700;">{{ $ordersCount }}</h3>
        </div>
        <div style="background:#fff;padding:1.5rem;border-radius:0.8rem;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <p style="color:#666;font-size:1.4rem;">Sales</p>
            <h3 style="font-size:2.4rem;font-weight:700;">₱{{ number_format($salesTotal, 2) }}</h3>
        </div>
        <div style="background:#fff;padding:1.5rem;border-radius:0.8rem;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <p style="color:#666;font-size:1.4rem;">Wallet Balance</p>
            <h3 style="font-size:2.4rem;font-weight:700; color: #27ae60;">₱{{ number_format($seller->getWalletBalance(), 2) }}</h3>
        </div>
    </div>

    <div style="margin-top:2rem;">
        <h2 style="font-size:2rem;font-weight:700;margin-bottom:1rem;">Recent Orders</h2>
        <div style="background:#fff;padding:1rem;border-radius:0.8rem;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:0.8rem;">Order #</th>
                        <th style="text-align:left;padding:0.8rem;">Status</th>
                        <th style="text-align:left;padding:0.8rem;">Total</th>
                        <th style="text-align:left;padding:0.8rem;">Placed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td style="padding:0.8rem;">{{ $order->id }}</td>
                            <td style="padding:0.8rem;text-transform:capitalize;">{{ $order->status ?? $order->payment_status }}</td>
                            <td style="padding:0.8rem;">₱{{ number_format($order->total_price, 2) }}</td>
                            <td style="padding:0.8rem;">{{ $order->placed_on }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:0.8rem;">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
