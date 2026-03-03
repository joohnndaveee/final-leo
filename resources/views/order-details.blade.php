@extends('layouts.app')

@section('title', 'Track Package - U-KAY HUB')

@push('styles')
<style>
    .track-page {
        max-width: 900px;
        margin: 1.25rem auto 2rem;
        padding: 0 1rem;
    }
    .track-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .back-link {
        color: #111827;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
    }
    .track-title {
        font-size: 1.35rem;
        font-weight: 800;
        margin: 0;
        color: #0f172a;
    }
    .eta-card,
    .ship-card,
    .timeline-card,
    .order-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-top: 0.9rem;
        overflow: hidden;
    }
    .eta-card {
        padding: 1rem;
        background: linear-gradient(120deg, #f8fafc, #ffffff);
    }
    .eta-date {
        margin: 0 0 0.4rem;
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
    }
    .eta-date span {
        color: #0f766e;
    }
    .cod-note {
        font-size: 0.88rem;
        color: #155e75;
        background: #cffafe;
        border-radius: 8px;
        padding: 0.45rem 0.6rem;
        display: inline-block;
    }
    .ship-card {
        padding: 1rem;
    }
    .ship-head {
        font-size: 1rem;
        font-weight: 800;
        color: #111827;
    }
    .ship-meta {
        margin-top: 0.35rem;
        font-size: 0.88rem;
        color: #64748b;
    }
    .timeline-card {
        --pad-x: 1rem;
        --time-col: 92px;
        --dot-col: 20px;
        --gap-col: 0.7rem;
        position: relative;
        padding: 0.4rem 1rem 0.7rem;
    }
    .timeline-card::before {
        content: "";
        position: absolute;
        left: calc(var(--pad-x) + var(--time-col) + var(--gap-col) + 9px);
        top: 0.8rem;
        bottom: 0.8rem;
        width: 2px;
        background: #e2e8f0;
        z-index: 0;
    }
    .event {
        display: grid;
        grid-template-columns: 92px 20px 1fr;
        gap: 0.7rem;
        position: relative;
        padding: 0.85rem 0;
        z-index: 1;
    }
    .event-time {
        color: #334155;
        font-size: 0.84rem;
        line-height: 1.2;
    }
    .event-line {
        position: relative;
    }
    .event-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #94a3b8;
        position: absolute;
        left: 3px;
        top: 6px;
        z-index: 1;
        border: 2px solid #fff;
    }
    .event.latest .event-dot {
        background: #0f766e;
        box-shadow: 0 0 0 4px #ccfbf1;
    }
    .event-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 800;
        color: #0f172a;
    }
    .event-desc {
        margin: 0.25rem 0 0;
        color: #334155;
        font-size: 0.9rem;
    }
    .event-loc {
        margin-top: 0.35rem;
        color: #0f766e;
        font-size: 0.84rem;
        background: #f0fdfa;
        display: inline-block;
        padding: 0.25rem 0.45rem;
        border-radius: 7px;
    }
    .order-card {
        padding: 1rem;
    }
    .order-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.7rem;
        margin-bottom: 0.8rem;
    }
    .order-no {
        font-size: 1rem;
        font-weight: 800;
        color: #111827;
    }
    .received-btn {
        border: 0;
        background: #0f766e;
        color: #fff;
        border-radius: 9px;
        font-weight: 700;
        font-size: 0.86rem;
        padding: 0.5rem 0.8rem;
        cursor: pointer;
    }
    .item {
        display: grid;
        grid-template-columns: 70px 1fr auto;
        gap: 0.75rem;
        padding: 0.7rem 0;
        align-items: center;
    }
    .item + .item {
        border-top: 1px dashed #e2e8f0;
    }
    .item img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }
    .item-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
    }
    .item-meta {
        color: #64748b;
        font-size: 0.82rem;
    }
    .item-subtotal {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
    }
    .review-button {
        margin-top: 0.3rem;
        border: 0;
        border-radius: 8px;
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        background: #e2e8f0;
        color: #1e293b;
    }
    .review-button.review-action {
        background: #1d4ed8;
        color: #fff;
    }
    .summary {
        border-top: 1px solid #e2e8f0;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        font-size: 0.88rem;
        color: #334155;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.2rem 0;
    }
    .summary-row.total {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 0.2rem;
    }
</style>
@endpush

@section('content')
@php
    $orderStatus = strtolower((string) ($order->status ?? $order->payment_status ?? 'pending'));
    $canMarkReceived = $orderStatus === 'delivered';
    $isReceived = in_array($orderStatus, ['completed', 'complete'], true);
    $canRequestReturn = in_array($orderStatus, ['completed', 'complete'], true);
    $hasReturnProcess = in_array($orderStatus, [
        'return_requested',
        'return_pickup_scheduled',
        'return_picked_up',
        'return_preparing',
        'return_in_transit_to_seller',
        'returned',
        'refunded',
    ], true);
    $timelineEvents = $order->tracking->sortByDesc('id')->values();

    $baseDate = $order->shipped_at ? \Carbon\Carbon::parse($order->shipped_at) : \Carbon\Carbon::parse($order->placed_on);
    $etaStart = $baseDate->copy()->addDays(4);
    $etaEnd = $baseDate->copy()->addDays(8);
    $isCod = str_contains(strtolower((string) $order->method), 'cash') || str_contains(strtolower((string) $order->method), 'cod');
@endphp

<section class="track-page">
    <div class="track-top">
        <a href="{{ route('orders') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back</a>
        <h1 class="track-title">Track Package</h1>
        <span style="width: 44px;"></span>
    </div>

    <div class="eta-card">
        <p class="eta-date">Estimated delivery: <span>{{ $etaStart->format('M j') }} - {{ $etaEnd->format('M j') }}</span></p>
        @if($isCod)
            <span class="cod-note">COD: Please prepare &#8369;{{ number_format($order->total_price, 2) }} in cash.</span>
        @endif
    </div>

    <div class="ship-card">
        <div class="ship-head">{{ $order->shipping_method ?: 'Standard Shipping' }}</div>
        <div class="ship-meta">
            Tracking #: {{ $order->tracking_number ?: 'Pending assignment' }}
        </div>
    </div>

    <div class="timeline-card">
        @forelse($timelineEvents as $i => $event)
            <div class="event {{ $i === 0 ? 'latest' : '' }}">
                <div class="event-time">
                    {{ \Carbon\Carbon::parse($event->created_at)->format('M j') }}<br>
                    {{ \Carbon\Carbon::parse($event->created_at)->format('g:i A') }}
                </div>
                <div class="event-line">
                    <div class="event-dot"></div>
                </div>
                <div>
                    <p class="event-title">{{ $event->title }}</p>
                    @if($event->description)
                        <p class="event-desc">{{ $event->description }}</p>
                    @endif
                    @if($event->location)
                        <div class="event-loc">{{ $event->location }}</div>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 1rem 0; color:#64748b; font-size: 0.92rem;">No tracking events yet.</div>
        @endforelse
    </div>

    <div class="order-card">
        <div class="order-head">
            <div class="order-no">Order #{{ $order->id }} - {{ date('M d, Y', strtotime($order->placed_on)) }}</div>
            @if($canMarkReceived)
                <form action="{{ route('order.received', $order->id) }}" method="POST" onsubmit="return confirm('Mark this order as received?');">
                    @csrf
                    <button type="submit" class="received-btn">Mark as Received</button>
                </form>
            @elseif($isReceived)
                <span style="color:#166534;font-size:0.84rem;font-weight:700;">Order marked as received</span>
            @elseif($hasReturnProcess)
                <span style="color:#92400e;font-size:0.84rem;font-weight:700;">Return process: {{ strtoupper(str_replace('_', ' ', $orderStatus)) }}</span>
            @endif
        </div>

        @if($canRequestReturn && !$hasReturnProcess)
            <form action="{{ route('order.return.request', $order->id) }}" method="POST" style="margin:0 0 .8rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr auto;gap:.45rem;">
                    <input type="text" name="reason" placeholder="Reason (e.g. parcel not received)" style="border:1px solid #d1d5db;border-radius:8px;padding:.45rem .6rem;font-size:.88rem;">
                    <button type="submit" style="border:0;background:#b91c1c;color:#fff;border-radius:8px;padding:.45rem .75rem;font-size:.84rem;font-weight:700;">Not Received / Return</button>
                </div>
            </form>
        @endif

        @foreach($order->orderItems as $item)
            @php
                $statusValue = strtolower($order->status ?? $order->payment_status ?? '');
                $isCompleted = in_array($statusValue, ['completed', 'complete'], true) ||
                              in_array(strtolower($order->payment_status ?? ''), ['completed', 'complete'], true);
                $hasReviewed = false;
                if (Auth::check()) {
                    $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                        ->where('product_id', $item->product_id)
                        ->where('order_id', $order->id)
                        ->exists();
                }
            @endphp
            <div class="item">
                <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}">
                <div>
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-meta">Qty: {{ $item->quantity }} - &#8369;{{ number_format($item->price, 2) }}</div>
                    @if(Auth::check())
                        @if($isCompleted && !$hasReviewed)
                            <button class="review-button review-action" onclick="openReviewModal({{ $item->product_id }}, '{{ $item->name }}', {{ $order->id }})">Review</button>
                        @elseif($hasReviewed)
                            <span class="review-button">Reviewed</span>
                        @endif
                    @endif
                </div>
                <div class="item-subtotal">&#8369;{{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>
        @endforeach

        <div class="summary">
            <div class="summary-row"><span>Subtotal</span><span>&#8369;{{ number_format($order->total_price, 2) }}</span></div>
            @if($order->voucher_discount > 0)
                <div class="summary-row" style="color:#15803d;"><span>Voucher Discount</span><span>-&#8369;{{ number_format($order->voucher_discount, 2) }}</span></div>
            @endif
            <div class="summary-row"><span>Delivery Fee</span><span>FREE</span></div>
            <div class="summary-row total"><span>Total</span><span>&#8369;{{ number_format($order->total_price, 2) }}</span></div>
        </div>
    </div>

    @auth
        @if($order->orderItems->count() > 0)
            <div style="text-align:right;margin-top:0.8rem;">
                <a href="{{ route('report.create', ['type'=>'product', 'id'=>$order->orderItems->first()->product_id]) }}" style="font-size:.82rem;color:#dc2626;text-decoration:none;">
                    <i class="fas fa-flag"></i> Report a product in this order
                </a>
            </div>
        @endif
    @endauth
</section>

<div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 3rem; border-radius: 15px; max-width: 500px; width: 90%; position: relative;">
        <button onclick="closeReviewModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 2rem; cursor: pointer; color: #666;">&times;</button>
        <h2 style="color: #27ae60; margin-bottom: 2rem; font-size: 2rem;">Rate this Product</h2>
        <p id="reviewProductName" style="font-size: 1.6rem; color: #333; margin-bottom: 2rem; font-weight: 600;"></p>
        <div style="margin-bottom: 2rem;">
            <p style="font-size: 1.4rem; margin-bottom: 1rem; color: #666;">Your Rating:</p>
            <div id="starRating" style="font-size: 3rem; color: #ffd700; cursor: pointer; display: flex; gap: 0.5rem;">
                <span onclick="setRating(1)">&#9734;</span>
                <span onclick="setRating(2)">&#9734;</span>
                <span onclick="setRating(3)">&#9734;</span>
                <span onclick="setRating(4)">&#9734;</span>
                <span onclick="setRating(5)">&#9734;</span>
            </div>
        </div>
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 1.4rem; margin-bottom: 0.8rem; color: #666;">Your Review (Optional):</label>
            <textarea id="reviewComment" rows="5" style="width: 100%; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; font-size: 1.4rem; font-family: inherit;" placeholder="Share your experience with this product..."></textarea>
        </div>
        <button onclick="submitReview()" style="width: 100%; padding: 1.2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 1.6rem; font-weight: 600; cursor: pointer;">
            Submit Review
        </button>
    </div>
</div>

@push('scripts')
<script>
let currentProductId = null;
let currentOrderId = null;
let currentRating = 5;

function openReviewModal(productId, productName, orderId) {
    currentProductId = productId;
    currentOrderId = orderId;
    currentRating = 5;
    document.getElementById('reviewProductName').textContent = productName;
    document.getElementById('reviewModal').style.display = 'flex';
    document.getElementById('reviewComment').value = '';
    setRating(5);
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function setRating(rating) {
    currentRating = rating;
    const stars = document.querySelectorAll('#starRating span');
    stars.forEach((star, index) => {
        star.textContent = index < rating ? '\u2605' : '\u2606';
    });
}

function submitReview() {
    const comment = document.getElementById('reviewComment').value;
    const submitButton = document.querySelector('#reviewModal button[onclick="submitReview()"]');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!token) {
        alert('Security token not found. Please refresh the page.');
        return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';

    fetch('{{ route("reviews.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: currentProductId,
            order_id: currentOrderId,
            rating: currentRating,
            comment: comment
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to submit review');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            closeReviewModal();
            location.reload();
        } else {
            alert(data.message || 'Failed to submit review');
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Review';
        }
    })
    .catch(error => {
        alert(error.message || 'An error occurred. Please try again.');
        submitButton.disabled = false;
        submitButton.textContent = 'Submit Review';
    });
}

document.getElementById('reviewModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewModal();
    }
});
</script>
@endpush
@endsection
