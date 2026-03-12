@php
    $tabs = [
        'all'          => 'All',
        'to_pay'       => 'To Pay',
        'to_ship'      => 'To Ship',
        'to_receive'   => 'To Receive',
        'completed'    => 'Completed',
        'cancelled'    => 'Cancelled',
        'return_refund'=> 'Return/Refund',
    ];

    /* Returns [badgeText, badgeClass, hintText] */
    $statusMeta = function ($order) {
        $status  = strtolower((string) ($order->status  ?? $order->payment_status ?? 'pending'));
        $payment = strtolower((string) ($order->payment_status ?? ''));

        if ($status === 'cancelled')
            return ['Cancelled', 'po-b-cancelled', ''];
        if ($status === 'not_received')
            return ['⚠️ Dispute: Not Received', 'po-b-cancelled', 'Your dispute has been filed. The seller is reviewing it.'];
        if (in_array($status, ['refunded', 'returned'], true) || $payment === 'refunded')
            return ['Refund Completed', 'po-b-return', ''];
        if (in_array($status, ['return_requested','return_pickup_scheduled','return_picked_up','return_preparing','return_in_transit_to_seller'], true))
            return ['Return In Progress', 'po-b-return', ''];
        if (in_array($status, ['completed', 'complete'], true))
            return ['Completed', 'po-b-completed', 'You have confirmed receipt of this order.'];
        if ($status === 'pending' && $payment === 'pending')
            return ['To Pay', 'po-b-topay', 'Please complete your payment.'];
        if (in_array($status, ['out_for_delivery', 'delivered'], true))
            return ['To Receive', '', 'Confirm receipt after you\'ve checked the received items and made payment.'];
        if (in_array($status, ['shipped', 'in_transit'], true))
            return ['To Receive', '', 'Your parcel is on its way.'];
        if (in_array($status, ['paid', 'confirmed', 'packed', 'processing'], true))
            return ['To Ship', 'po-b-toship', 'Your order is being prepared by the seller.'];
        return [ucwords(str_replace('_', ' ', $status ?: $payment ?: 'Pending')), '', ''];
    };

    $latestTracking = function ($order) {
        return $order->tracking?->sortByDesc('id')->first()?->title;
    };
@endphp

<div class="profile-orders">

    {{-- Tab bar --}}
    <div class="po-tabs-wrap">
        @foreach($tabs as $tabKey => $tabLabel)
            <button
                type="button"
                class="po-tab {{ $tab === $tabKey ? 'active' : '' }}"
                data-order-tab="{{ $tabKey }}"
            >{{ $tabLabel }}@if(($tabCounts[$tabKey] ?? 0) > 0)&nbsp;({{ $tabCounts[$tabKey] }})@endif</button>
        @endforeach
    </div>

    {{-- Search bar --}}
    <div class="po-search-wrap">
        <i class="fas fa-search po-search-icon"></i>
        <input
            type="text"
            id="poSearchInput"
            class="po-search-input"
            placeholder="You can search by Seller Name, Order ID or Product name"
            oninput="poSearch(this.value)"
            autocomplete="off"
        >
        <button type="button" class="po-search-clear" id="poSearchClear" onclick="poClearSearch()" style="display:none;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <style>
    .po-search-wrap {
        position: relative;
        display: flex;
        align-items: center;
        background: #f5f5f5;
        border-radius: 4px;
        margin-bottom: 1rem;
        padding: 0 .85rem;
        border: 1px solid #ebebeb;
    }
    .po-search-wrap:focus-within {
        border-color: #16a34a;
        background: #fff;
    }
    .po-search-icon { color: #999; font-size: 1.3rem; flex-shrink: 0; }
    .po-search-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: .72rem .6rem;
        font-size: 1.25rem;
        font-family: inherit;
        color: #333;
        outline: none;
    }
    .po-search-input::placeholder { color: #aaa; }
    .po-search-clear {
        background: none;
        border: none;
        color: #aaa;
        font-size: 1.2rem;
        cursor: pointer;
        padding: .3rem;
        flex-shrink: 0;
    }
    .po-search-clear:hover { color: #555; }
    .po-no-results {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
        font-size: 1.3rem;
        display: none;
        border-radius: 4px;
    }
    </style>

    <div id="poNoResults" class="po-no-results">
        <i class="fas fa-search" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
        No orders match your search.
    </div>

    <script>
    function poSearch(q) {
        const val = q.trim().toLowerCase();
        const clearBtn = document.getElementById('poSearchClear');
        if (clearBtn) clearBtn.style.display = val ? 'block' : 'none';

        const cards = document.querySelectorAll('.po-card');
        let visible = 0;
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const show = !val || text.includes(val);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        const noRes = document.getElementById('poNoResults');
        if (noRes) noRes.style.display = (val && visible === 0) ? 'block' : 'none';
    }
    function poClearSearch() {
        const inp = document.getElementById('poSearchInput');
        if (inp) { inp.value = ''; poSearch(''); inp.focus(); }
    }
    </script>

    @if($orders->isEmpty())
        <div class="po-empty">
            <i class="fas fa-box-open" style="font-size:2.5rem;margin-bottom:.7rem;display:block;"></i>
            No orders in this category yet.
        </div>
    @else
        <div class="po-list">
            @foreach($orders as $order)
                @php
                    [$badgeText, $badgeClass, $hintText] = $statusMeta($order);
                    $trackingTitle = $latestTracking($order);
                    $os = strtolower((string) ($order->status ?? ''));
                    $isOutForDelivery = in_array($os, ['out_for_delivery', 'delivered'], true);
                    $isCompleted      = in_array($os, ['completed', 'complete'], true);
                        $inReturn         = in_array($os, ['not_received','return_requested','return_pickup_scheduled','return_picked_up','return_preparing','return_in_transit_to_seller','returned','refunded'], true);
                    $canReturn        = $isCompleted && !$inReturn;

                    /* Seller from first item */
                    $firstItem = $order->orderItems->first();
                    $seller    = $firstItem?->product?->seller;
                @endphp

                <article class="po-card">

                    {{-- ── Header: shop info + status ── --}}
                    <div class="po-head">
                        <div class="po-shop-left">
                            <i class="fas fa-store po-shop-icon"></i>
                            <span class="po-shop-name">{{ $seller?->shop_name ?? 'Unknown Shop' }}</span>
                            <span class="po-vline"></span>
                            @if($seller)
                                <a class="po-btn-chat"
                                   href="{{ Auth::check() ? route('user.seller.chat.show', $seller->id) : route('login') }}">
                                    <i class="fas fa-comment-dots"></i> Chat
                                </a>
                                <a class="po-btn-shop" href="{{ route('seller.shop', $seller->id) }}">
                                    <i class="fas fa-store-alt"></i> View Shop
                                </a>
                            @endif
                        </div>
                        <div class="po-shop-right">
                            @if($trackingTitle)
                                <span class="po-tracking-txt">
                                    <i class="fas fa-truck" style="font-size:1.1rem;"></i>
                                    {{ $trackingTitle }}
                                </span>
                                <span class="po-vline"></span>
                            @endif
                            <span class="po-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                        </div>
                    </div>

                    {{-- ── Product rows ── --}}
                    @foreach($order->orderItems as $item)
                        <div class="po-item">
                            <img class="po-item-img"
                                 src="{{ asset('uploaded_img/' . $item->image) }}"
                                 alt="{{ $item->name }}">
                            <div class="po-item-info">
                                <div class="po-item-name">{{ $item->name }}</div>
                                @if($item->variation ?? null)
                                    <div class="po-item-var">Variation: {{ $item->variation }}</div>
                                @endif
                                <div class="po-item-qty">x{{ $item->quantity }}</div>
                            </div>
                            <div class="po-item-price">&#8369;{{ number_format($item->price, 2) }}</div>
                        </div>
                    @endforeach

                    {{-- ── Order total ── --}}
                    <div class="po-total-row">
                        Order Total:
                        <span class="po-total-amt">&#8369;{{ number_format($order->total_price, 2) }}</span>
                    </div>

                    {{-- ── Action footer ── --}}
                    <div class="po-actions-row">
                        <span class="po-hint">{{ $hintText }}</span>
                        <div class="po-action-btns">
                            {{-- Contact Seller --}}
                            @if($seller)
                                <a class="po-btn-action po-btn-contact"
                                   href="{{ Auth::check() ? route('user.seller.chat.show', $seller->id) : route('login') }}">
                                    <i class="fas fa-comment"></i> Contact Seller
                                </a>
                            @endif

                            <button type="button"
                                    class="po-btn-action po-btn-details"
                                    data-load-order="{{ $order->id }}">
                                View Details
                            </button>
                        </div>
                    </div>

                </article>
            @endforeach
        </div>
    @endif

</div>
