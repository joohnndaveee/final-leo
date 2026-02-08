@extends('layouts.seller')

@section('title', 'Seller Orders')

@section('content')
<section style="max-width:1100px;margin:2rem auto;">
    <h1 style="font-size:2.4rem;font-weight:700;margin-bottom:1rem;">Orders</h1>

    @if(session('success'))
        <div style="background:#e6ffed;padding:1rem;border:1px solid #b7f5c4;border-radius:0.6rem;margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background:#fff;padding:1rem;border-radius:0.8rem;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:0.8rem;">Order #</th>
                    <th style="text-align:left;padding:0.8rem;">Items</th>
                    <th style="text-align:left;padding:0.8rem;">Status</th>
                    <th style="text-align:left;padding:0.8rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="padding:0.8rem;">{{ $order->id }}</td>
                        <td style="padding:0.8rem;">
                            <ul style="margin:0;padding-left:1.2rem;">
                                @foreach($order->orderItems as $item)
                                    <li>{{ $item->name }} (x{{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="padding:0.8rem;text-transform:capitalize;">{{ $order->status ?? $order->payment_status }}</td>
                        <td style="padding:0.8rem;">
                            <form action="{{ route('seller.orders.ship', $order) }}" method="POST" style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                                @csrf
                                <input type="text" name="tracking_number" placeholder="Tracking #" required style="padding:0.4rem 0.6rem;border:1px solid #ddd;border-radius:0.4rem;">
                                <input type="text" name="shipping_method" placeholder="Courier" required style="padding:0.4rem 0.6rem;border:1px solid #ddd;border-radius:0.4rem;">
                                <button type="submit" style="padding:0.5rem 1rem;background:#27ae60;color:#fff;border:none;border-radius:0.4rem;">Mark Shipped</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding:0.8rem;">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:1rem;">
            {{ $orders->links() }}
        </div>
    </div>
</section>
@endsection
