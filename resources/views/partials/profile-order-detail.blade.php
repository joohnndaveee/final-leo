@php
    $orderStatus = strtolower((string) ($order->status ?? $order->payment_status ?? 'pending'));
    $canMarkReceived = in_array($orderStatus, ['out_for_delivery', 'delivered'], true);
    $isCompleted     = in_array($orderStatus, ['completed', 'complete'], true);
    $isDispute = $orderStatus === 'not_received';
    $hasReturnProcess = $isDispute || in_array($orderStatus, [
        'return_requested','return_pickup_scheduled','return_picked_up',
        'return_preparing','return_in_transit_to_seller','returned','refunded',
    ], true);
    $canRequestReturn = $isCompleted && !$hasReturnProcess;
    $timelineEvents   = $order->tracking->sortByDesc('id')->values();

    $baseDate  = $order->shipped_at ? \Carbon\Carbon::parse($order->shipped_at) : \Carbon\Carbon::parse($order->placed_on);
    $etaStart  = $baseDate->copy()->addDays(4);
    $etaEnd    = $baseDate->copy()->addDays(8);
    $isCod     = str_contains(strtolower((string) $order->method), 'cash')
              || str_contains(strtolower((string) $order->method), 'cod');
@endphp

<style>
/* ── Inline order detail styles ── */
.pod-wrap { padding: .5rem 0 1rem; font-family: 'DM Sans','Segoe UI',sans-serif; }
.pod-back {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: 1.28rem; font-weight: 700; color: #0f172a;
    background: none; border: none; cursor: pointer; padding: 0; margin-bottom: 1rem;
    font-family: inherit;
}
.pod-back:hover { color: #16a34a; }
.pod-title { font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0 0 1.1rem; }

.pod-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    margin-bottom: .85rem; overflow: hidden;
}
/* ETA */
.pod-eta { padding: 1rem 1.2rem; background: linear-gradient(120deg, #f8fafc, #fff); }
.pod-eta-date { margin: 0 0 .35rem; font-size: 1.42rem; font-weight: 800; color: #0f172a; }
.pod-eta-date span { color: #16a34a; }
.pod-cod { font-size: .88rem; color: #155e75; background: #cffafe; border-radius: 7px; padding: .4rem .6rem; display: inline-block; }
/* Ship */
.pod-ship { padding: 1rem 1.2rem; }
.pod-ship-head { font-size: 1.1rem; font-weight: 800; color: #111827; }
.pod-ship-meta { margin-top: .3rem; font-size: .9rem; color: #64748b; }
/* Timeline */
.pod-timeline {
    --tx: 1.2rem; --tc: 88px; --dc: 20px; --gc: .65rem;
    position: relative; padding: .4rem 1.2rem .7rem;
}
.pod-timeline::before {
    content: ""; position: absolute;
    left: calc(var(--tx) + var(--tc) + var(--gc) + 9px);
    top: .8rem; bottom: .8rem; width: 2px; background: #e2e8f0; z-index: 0;
}
.pod-event {
    display: grid; grid-template-columns: 88px 20px 1fr;
    gap: .65rem; position: relative; padding: .8rem 0; z-index: 1;
}
.pod-event-time { color: #334155; font-size: .84rem; line-height: 1.2; }
.pod-event-line { position: relative; }
.pod-event-dot {
    width: 12px; height: 12px; border-radius: 50%; background: #94a3b8;
    position: absolute; left: 3px; top: 6px; z-index: 1; border: 2px solid #fff;
}
.pod-event.latest .pod-event-dot { background: #16a34a; box-shadow: 0 0 0 4px #dcfce7; }
.pod-event-title { margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; }
.pod-event-desc  { margin: .22rem 0 0; color: #334155; font-size: .9rem; }
.pod-event-loc   { margin-top: .3rem; color: #16a34a; font-size: .84rem; background: #f0fdf4; display: inline-block; padding: .22rem .42rem; border-radius: 6px; }
/* Order items card */
.pod-items { padding: 1rem 1.2rem; }
.pod-order-head { display: flex; justify-content: space-between; align-items: center; gap: .7rem; margin-bottom: .8rem; flex-wrap: wrap; }
.pod-order-no { font-size: 1.05rem; font-weight: 800; color: #111827; }
.pod-item { display: grid; grid-template-columns: 68px 1fr auto; gap: .75rem; padding: .7rem 0; align-items: center; }
.pod-item + .pod-item { border-top: 1px dashed #e2e8f0; }
.pod-item img { width: 68px; height: 68px; border-radius: 9px; object-fit: cover; border: 1px solid #e2e8f0; }
.pod-item-name { font-size: .94rem; font-weight: 700; color: #0f172a; }
.pod-item-meta { color: #64748b; font-size: .83rem; margin-top: .18rem; }
.pod-item-sub  { font-size: .94rem; font-weight: 700; color: #0f172a; white-space: nowrap; }
.pod-review-btn { margin-top: .3rem; border: 0; border-radius: 7px; padding: .32rem .58rem; font-size: .8rem; font-weight: 700; cursor: pointer; background: #16a34a; color: #fff; font-family: inherit; }
.pod-reviewed   { margin-top: .3rem; border-radius: 7px; padding: .32rem .58rem; font-size: .8rem; font-weight: 700; background: #e2e8f0; color: #1e293b; display: inline-block; }
.pod-summary { border-top: 1px solid #e2e8f0; margin-top: .5rem; padding-top: .5rem; font-size: .9rem; color: #334155; }
.pod-summary-row { display: flex; justify-content: space-between; padding: .2rem 0; }
.pod-summary-row.total { font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-top: .2rem; }
/* Action buttons inside detail */
.pod-action-received { background: #16a34a; color: #fff; border: 0; border-radius: 8px; padding: .45rem .85rem; font-size: .85rem; font-weight: 700; cursor: pointer; font-family: inherit; }
.pod-action-notrecv  { background: #b91c1c; color: #fff; border: 0; border-radius: 8px; padding: .45rem .85rem; font-size: .85rem; font-weight: 700; cursor: pointer; font-family: inherit; }
</style>

<div class="pod-wrap">
    {{-- Back button (reloads the orders list) --}}
    <button type="button" class="pod-back" id="podBackBtn">
        <i class="fas fa-arrow-left"></i> Back to My Purchase
    </button>

    <h2 class="pod-title">Order Details</h2>

    {{-- ETA --}}
    <div class="pod-card pod-eta">
        <p class="pod-eta-date">Estimated delivery: <span>{{ $etaStart->format('M j') }} – {{ $etaEnd->format('M j') }}</span></p>
        @if($isCod)
            <span class="pod-cod">COD: Please prepare &#8369;{{ number_format($order->total_price, 2) }} in cash.</span>
        @endif
    </div>

    {{-- Shipping info --}}
    <div class="pod-card pod-ship">
        <div class="pod-ship-head">{{ $order->shipping_method ?: 'Standard Shipping' }}</div>
        <div class="pod-ship-meta">Tracking #: {{ $order->tracking_number ?: 'Pending assignment' }}</div>
    </div>

    {{-- Timeline --}}
    <div class="pod-card pod-timeline">
        @forelse($timelineEvents as $i => $event)
            <div class="pod-event {{ $i === 0 ? 'latest' : '' }}">
                <div class="pod-event-time">
                    {{ \Carbon\Carbon::parse($event->created_at)->format('M j') }}<br>
                    {{ \Carbon\Carbon::parse($event->created_at)->format('g:i A') }}
                </div>
                <div class="pod-event-line"><div class="pod-event-dot"></div></div>
                <div>
                    <p class="pod-event-title">{{ $event->title }}</p>
                    @if($event->description)<p class="pod-event-desc">{{ $event->description }}</p>@endif
                    @if($event->location)<div class="pod-event-loc">{{ $event->location }}</div>@endif
                </div>
            </div>
        @empty
            <div style="padding:1rem 0;color:#64748b;font-size:.92rem;">No tracking events yet.</div>
        @endforelse
    </div>

    {{-- Order items card --}}
    <div class="pod-card pod-items">
        <div class="pod-order-head">
            <div class="pod-order-no">Order #{{ $order->id }} &mdash; {{ date('M d, Y', strtotime($order->placed_on)) }}</div>

            @if($canMarkReceived)
                <div style="display:flex;gap:.45rem;align-items:center;flex-wrap:wrap;">
                    <form action="{{ route('order.received', $order->id) }}" method="POST"
                          onsubmit="return confirm('Confirm you have received this order?');" style="margin:0;">
                        @csrf
                        <button type="submit" class="pod-action-received">
                            <i class="fas fa-check"></i> Order Received
                        </button>
                    </form>
                    <button type="button" class="pod-action-notrecv" onclick="podToggleNotReceived()">
                        <i class="fas fa-times"></i> Not Received
                    </button>
                </div>
            @elseif($isCompleted)
                <span style="color:#166534;font-size:.85rem;font-weight:700;">
                    <i class="fas fa-check-circle"></i> Order received
                </span>
            @elseif($isDispute)
                <span style="color:#b91c1c;font-size:.85rem;font-weight:700;">
                    <i class="fas fa-exclamation-triangle"></i> Dispute filed — awaiting seller review
                </span>
            @elseif($hasReturnProcess)
                <span style="color:#92400e;font-size:.85rem;font-weight:700;">
                    Return: {{ strtoupper(str_replace('_',' ',$orderStatus)) }}
                </span>
            @endif
        </div>

        {{-- Not Received form --}}
        @if($canMarkReceived)
            <div id="podNotReceivedForm" style="display:none;margin:.6rem 0 .8rem;">
                <form action="{{ route('order.not.received', $order->id) }}" method="POST">
                    @csrf
                    <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:.75rem;display:grid;gap:.5rem;">
                        <p style="margin:0;font-size:.85rem;color:#991b1b;font-weight:600;">
                            <i class="fas fa-exclamation-triangle"></i> Report as Not Received
                        </p>
                        <input type="text" name="reason"
                               placeholder="Describe the issue (e.g. parcel not arrived, wrong address...)"
                               style="border:1px solid #d1d5db;border-radius:6px;padding:.45rem .6rem;font-size:.88rem;width:100%;box-sizing:border-box;">
                        <div style="display:flex;gap:.45rem;justify-content:flex-end;">
                            <button type="button" onclick="podToggleNotReceived()"
                                    style="background:#e5e7eb;color:#374151;border:0;border-radius:6px;padding:.4rem .7rem;font-size:.84rem;cursor:pointer;font-family:inherit;">Cancel</button>
                            <button type="submit"
                                    style="background:#b91c1c;color:#fff;border:0;border-radius:6px;padding:.4rem .75rem;font-size:.84rem;font-weight:700;cursor:pointer;font-family:inherit;">Submit Report</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- Return Order form --}}
        @if($canRequestReturn)
            <div style="margin:0 0 .8rem;">
                <button type="button" onclick="podToggleReturn()"
                        style="background:#fff;color:#b91c1c;border:1px solid #b91c1c;border-radius:8px;padding:.4rem .8rem;font-size:.84rem;font-weight:600;cursor:pointer;font-family:inherit;">
                    <i class="fas fa-undo-alt"></i> Return Order
                </button>
                <div id="podReturnForm" style="display:none;margin-top:.5rem;">
                    <form action="{{ route('order.return.request', $order->id) }}" method="POST">
                        @csrf
                        <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:.75rem;display:grid;gap:.5rem;">
                            <p style="margin:0;font-size:.85rem;color:#92400e;font-weight:600;">
                                <i class="fas fa-box-open"></i> Return Request
                            </p>
                            <input type="text" name="reason"
                                   placeholder="Reason for return (e.g. damaged item, wrong product...)"
                                   style="border:1px solid #d1d5db;border-radius:6px;padding:.45rem .6rem;font-size:.88rem;width:100%;box-sizing:border-box;">
                            <div style="display:flex;gap:.45rem;justify-content:flex-end;">
                                <button type="button" onclick="podToggleReturn()"
                                        style="background:#e5e7eb;color:#374151;border:0;border-radius:6px;padding:.4rem .7rem;font-size:.84rem;cursor:pointer;font-family:inherit;">Cancel</button>
                                <button type="submit"
                                        style="background:#b45309;color:#fff;border:0;border-radius:6px;padding:.4rem .75rem;font-size:.84rem;font-weight:700;cursor:pointer;font-family:inherit;">Submit Return</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Item rows --}}
        @foreach($order->orderItems as $item)
            @php
                $hasReviewed = false;
                if (Auth::check()) {
                    $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                        ->where('product_id', $item->product_id)
                        ->where('order_id', $order->id)
                        ->exists();
                }
            @endphp
            <div class="pod-item">
                <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}">
                <div>
                    <div class="pod-item-name">{{ $item->name }}</div>
                    <div class="pod-item-meta">Qty: {{ $item->quantity }} &mdash; &#8369;{{ number_format($item->price, 2) }}</div>
                    @if(Auth::check())
                        @if($isCompleted && !$hasReviewed)
                            <button class="pod-review-btn"
                                    onclick="podOpenReview({{ $item->product_id }}, '{{ addslashes($item->name) }}', {{ $order->id }})">
                                <i class="fas fa-star"></i> Review
                            </button>
                        @elseif($hasReviewed)
                            <span class="pod-reviewed"><i class="fas fa-check"></i> Reviewed</span>
                        @endif
                    @endif
                </div>
                <div class="pod-item-sub">&#8369;{{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>
        @endforeach

        {{-- Summary --}}
        <div class="pod-summary">
            <div class="pod-summary-row"><span>Subtotal</span><span>&#8369;{{ number_format($order->total_price, 2) }}</span></div>
            @if($order->voucher_discount > 0)
                <div class="pod-summary-row" style="color:#15803d;">
                    <span>Voucher Discount</span><span>-&#8369;{{ number_format($order->voucher_discount, 2) }}</span>
                </div>
            @endif
            <div class="pod-summary-row"><span>Delivery Fee</span><span>FREE</span></div>
            <div class="pod-summary-row total"><span>Total</span><span>&#8369;{{ number_format($order->total_price, 2) }}</span></div>
        </div>
    </div>

    @auth
        @if($order->orderItems->count() > 0)
            <div style="text-align:right;margin-top:.6rem;">
                <a href="{{ route('report.create', ['type'=>'product','id'=>$order->orderItems->first()->product_id]) }}"
                   style="font-size:.82rem;color:#dc2626;text-decoration:none;">
                    <i class="fas fa-flag"></i> Report a product in this order
                </a>
            </div>
        @endif
    @endauth
</div>

{{-- Review Modal --}}
<div id="podReviewModal"
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.7);z-index:9999;justify-content:center;align-items:center;">
    <div style="background:#fff;padding:2.5rem;border-radius:14px;max-width:480px;width:90%;position:relative;">
        <button onclick="podCloseReview()"
                style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:2rem;cursor:pointer;color:#666;">&times;</button>
        <h2 style="color:#16a34a;margin-bottom:1.5rem;font-size:1.7rem;">Rate this Product</h2>
        <p id="podReviewProductName" style="font-size:1.4rem;color:#333;margin-bottom:1.5rem;font-weight:600;"></p>
        <div style="margin-bottom:1.5rem;">
            <p style="font-size:1.25rem;margin-bottom:.8rem;color:#666;">Your Rating:</p>
            <div id="podStarRating" style="font-size:2.8rem;color:#ffd700;cursor:pointer;display:flex;gap:.4rem;">
                <span onclick="podSetRating(1)">&#9734;</span>
                <span onclick="podSetRating(2)">&#9734;</span>
                <span onclick="podSetRating(3)">&#9734;</span>
                <span onclick="podSetRating(4)">&#9734;</span>
                <span onclick="podSetRating(5)">&#9734;</span>
            </div>
        </div>
        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-size:1.25rem;margin-bottom:.6rem;color:#666;">Your Review (Optional):</label>
            <textarea id="podReviewComment" rows="4"
                      style="width:100%;padding:.9rem;border:2px solid #ddd;border-radius:8px;font-size:1.25rem;font-family:inherit;box-sizing:border-box;"
                      placeholder="Share your experience with this product..."></textarea>
        </div>
        <button id="podSubmitReviewBtn" onclick="podSubmitReview()"
                style="width:100%;padding:1rem;background:#16a34a;color:#fff;border:none;border-radius:8px;font-size:1.4rem;font-weight:700;cursor:pointer;font-family:inherit;">
            Submit Review
        </button>
    </div>
</div>

<script>
(function () {
    /* ── Back button: reload the orders list ── */
    const backBtn = document.getElementById('podBackBtn');
    if (backBtn) {
        backBtn.addEventListener('click', function () {
            if (typeof window.loadOrders === 'function') {
                window.loadOrders('all');
            }
        });
    }

    /* ── Toggle helpers ── */
    window.podToggleNotReceived = function () {
        const f = document.getElementById('podNotReceivedForm');
        if (f) f.style.display = f.style.display === 'none' ? 'block' : 'none';
    };
    window.podToggleReturn = function () {
        const f = document.getElementById('podReturnForm');
        if (f) f.style.display = f.style.display === 'none' ? 'block' : 'none';
    };

    /* ── Review modal ── */
    let _prodId, _orderId, _rating = 5;

    window.podOpenReview = function (productId, productName, orderId) {
        _prodId  = productId;
        _orderId = orderId;
        _rating  = 5;
        document.getElementById('podReviewProductName').textContent = productName;
        document.getElementById('podReviewComment').value = '';
        document.getElementById('podReviewModal').style.display = 'flex';
        podSetRating(5);
    };
    window.podCloseReview = function () {
        document.getElementById('podReviewModal').style.display = 'none';
    };
    window.podSetRating = function (r) {
        _rating = r;
        document.querySelectorAll('#podStarRating span').forEach((s, i) => {
            s.textContent = i < r ? '\u2605' : '\u2606';
        });
    };
    window.podSubmitReview = function () {
        const comment = document.getElementById('podReviewComment').value;
        const btn     = document.getElementById('podSubmitReviewBtn');
        const token   = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) { alert('Security token not found. Please refresh the page.'); return; }
        btn.disabled = true; btn.textContent = 'Submitting...';

        fetch('{{ route("reviews.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: _prodId, order_id: _orderId, rating: _rating, comment: comment })
        })
        .then(r => r.ok ? r.json() : r.json().then(d => { throw new Error(d.message || 'Failed'); }))
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                podCloseReview();
                // Reload the same order detail
                if (typeof window.loadOrderDetail === 'function') window.loadOrderDetail(_orderId);
            } else {
                alert(data.message || 'Failed to submit review');
                btn.disabled = false; btn.textContent = 'Submit Review';
            }
        })
        .catch(e => { alert(e.message || 'An error occurred.'); btn.disabled = false; btn.textContent = 'Submit Review'; });
    };

    document.getElementById('podReviewModal')?.addEventListener('click', function (e) {
        if (e.target === this) podCloseReview();
    });
})();
</script>
