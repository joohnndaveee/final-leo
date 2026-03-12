@extends('layouts.seller')

@section('title', 'Order #{{ $order->id }} – Tracking')

@push('styles')
<style>
/* ── Page shell ── */
.oa-page  { max-width: 1100px; margin: 1.4rem auto 2rem; padding: 0 1rem; font-family: 'DM Sans','Segoe UI',sans-serif; }
.oa-head  { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.2rem; flex-wrap:wrap; }
.oa-title { margin:0; font-size:1.75rem; font-weight:800; color:#064e3b; }
.oa-back  { text-decoration:none; background:#64748b; color:#fff; border-radius:999px; padding:.5rem .95rem; font-size:.9rem; font-weight:700; }

/* ── Grid ── */
.oa-grid  { display:grid; grid-template-columns: minmax(0,1.2fr) 320px; gap:1.1rem; align-items:start; }
@media(max-width:820px){ .oa-grid{ grid-template-columns:1fr; } }

/* ── Card ── */
.oa-card  { background:#fff; border:1px solid #d1fae5; border-radius:14px; box-shadow:0 6px 20px rgba(15,23,42,.06); padding:1.1rem; }
.oa-card + .oa-card { margin-top:1rem; }
.oa-card-title { margin:0 0 .8rem; font-size:1.12rem; font-weight:800; color:#065f46; }

/* ── Order summary ── */
.oa-meta  { display:grid; grid-template-columns:1fr 1fr; gap:.4rem .85rem; margin-bottom:.75rem; }
.oa-meta-row { font-size:.9rem; color:#334155; line-height:1.4; }
.oa-meta-row strong { color:#0f172a; }
.oa-item  { display:grid; grid-template-columns:50px 1fr auto; gap:.6rem; align-items:center; padding:.5rem 0; border-bottom:1px dashed #e5e7eb; }
.oa-item:last-child { border-bottom:none; }
.oa-item img { width:50px; height:50px; border-radius:8px; object-fit:cover; border:1px solid #e5e7eb; }
.oa-item-name { font-size:.88rem; font-weight:600; color:#0f172a; }
.oa-item-qty  { font-size:.82rem; color:#64748b; }
.oa-item-sub  { font-size:.9rem; font-weight:700; color:#0f172a; white-space:nowrap; }

/* ── Progress stepper ── */
.oa-stepper { display:flex; align-items:center; overflow-x:auto; padding:.25rem 0 .5rem; scrollbar-width:none; gap:0; }
.oa-stepper::-webkit-scrollbar { display:none; }
.oa-step { display:flex; flex-direction:column; align-items:center; flex:1; min-width:64px; position:relative; }
.oa-step + .oa-step::before {
    content:""; position:absolute; top:13px; left:calc(-50% + 12px); right:calc(50% + 12px);
    height:2px; background:#e2e8f0; z-index:0;
}
.oa-step.done + .oa-step::before  { background:#16a34a; }
.oa-step-dot {
    width:26px; height:26px; border-radius:50%; border:2px solid #e2e8f0;
    background:#fff; display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:800; color:#94a3b8; position:relative; z-index:1;
}
.oa-step.done  .oa-step-dot { background:#16a34a; border-color:#16a34a; color:#fff; }
.oa-step.active .oa-step-dot { background:#fff; border-color:#16a34a; color:#16a34a; box-shadow:0 0 0 4px #dcfce7; }
.oa-step-label { font-size:.7rem; font-weight:600; color:#94a3b8; margin-top:.3rem; text-align:center; line-height:1.2; }
.oa-step.done   .oa-step-label { color:#16a34a; }
.oa-step.active .oa-step-label { color:#065f46; font-weight:800; }

/* ── Next action card ── */
.oa-action-area  { margin-top:.8rem; }
.oa-current-badge {
    display:inline-flex; align-items:center; gap:.4rem;
    background:#f0fdf4; border:1px solid #bbf7d0; border-radius:999px;
    padding:.35rem .8rem; font-size:.88rem; font-weight:700; color:#15803d;
    margin-bottom:.9rem;
}
.oa-status-done { background:#f1f5f9; border-color:#e2e8f0; color:#64748b; }
.oa-status-cancel { background:#fef2f2; border-color:#fecaca; color:#b91c1c; }
.oa-status-return { background:#fffbeb; border-color:#fcd34d; color:#92400e; }

/* Primary action buttons */
.oa-btn-primary {
    display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%;
    padding:.75rem 1rem; border:0; border-radius:10px; font-size:1rem; font-weight:800;
    cursor:pointer; font-family:inherit; background:linear-gradient(135deg,#10b981,#059669);
    color:#fff; box-shadow:0 4px 12px rgba(16,185,129,.3); transition:opacity .15s;
}
.oa-btn-primary:hover { opacity:.9; }
.oa-btn-primary:disabled { opacity:.45; cursor:not-allowed; }

/* Shipped extra fields */
.oa-ship-fields { display:none; margin:.75rem 0; }
.oa-ship-fields.show { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
@media(max-width:500px){ .oa-ship-fields.show{ grid-template-columns:1fr; } }
.oa-input { border:1px solid #cbd5e1; border-radius:9px; padding:.52rem .7rem; font-size:.9rem; width:100%; box-sizing:border-box; font-family:inherit; }
.oa-input:focus { outline:2px solid #16a34a; border-color:#16a34a; }
.oa-textarea { min-height:72px; resize:vertical; }

/* Cancel button — secondary danger */
.oa-btn-cancel {
    display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%;
    padding:.6rem 1rem; border:1.5px solid #fca5a5; border-radius:9px; font-size:.9rem;
    font-weight:700; cursor:pointer; font-family:inherit; background:#fff; color:#b91c1c;
    margin-top:.55rem; transition:background .15s;
}
.oa-btn-cancel:hover { background:#fef2f2; }

/* Date/time row */
.oa-dt-row { display:flex; gap:.5rem; align-items:center; margin:.6rem 0; }
.oa-dt-row input { border:1px solid #cbd5e1; border-radius:8px; padding:.5rem .6rem; font-size:.88rem; font-family:inherit; flex:1; }
.oa-use-now { white-space:nowrap; border:0; border-radius:8px; background:#0ea5e9; color:#fff; padding:.5rem .75rem; font-size:.82rem; font-weight:700; cursor:pointer; font-family:inherit; }

/* Hint / error */
.oa-hint  { font-size:.84rem; color:#64748b; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; padding:.5rem .65rem; margin:.5rem 0; }
.oa-error { font-size:.82rem; font-weight:700; color:#b91c1c; background:#fef2f2; border:1px solid #fecaca; border-radius:7px; padding:.32rem .5rem; margin-bottom:.5rem; }
.oa-no-action { font-size:.9rem; color:#64748b; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; padding:.75rem; text-align:center; }

/* ── Timeline sidebar ── */
.oa-timeline { max-height:75vh; overflow-y:auto; padding-right:.2rem; }
.oa-tl-event { padding:.2rem 0 .75rem .75rem; border-left:2px solid #d1d5db; margin-left:.3rem; }
.oa-tl-event.latest { border-color:#16a34a; }
.oa-tl-title { font-size:.93rem; font-weight:800; color:#0f172a; }
.oa-tl-meta  { font-size:.82rem; color:#64748b; margin-top:.1rem; }
.oa-tl-desc  { font-size:.82rem; color:#334155; margin-top:.1rem; }
.oa-tl-empty { font-size:.88rem; color:#64748b; }
</style>
@endpush

@section('content')
@php
    $orderStatus = strtolower(trim((string) ($order->status ?? 'pending')));

    /* ── Progress stepper stages ── */
    $stages = [
        ['key' => 'placed',           'label' => 'Order\nPlaced'],
        ['key' => 'confirmed',        'label' => 'Confirmed'],
        ['key' => 'packed',           'label' => 'Packed'],
        ['key' => 'shipped',          'label' => 'Shipped'],
        ['key' => 'in_transit',       'label' => 'In Transit'],
        ['key' => 'out_for_delivery', 'label' => 'Out for\nDelivery'],
        ['key' => 'delivered',        'label' => 'Delivered'],
        ['key' => 'completed',        'label' => 'Received'],
    ];
    $stageOrder = ['placed','confirmed','packed','shipped','in_transit','out_for_delivery','delivered','completed'];
    $statusToStage = [
        'pending'          => 'placed',
        'paid'             => 'placed',
        'confirmed'        => 'confirmed',
        'packed'           => 'packed',
        'shipped'          => 'shipped',
        'in_transit'       => 'in_transit',
        'out_for_delivery' => 'out_for_delivery',
        'delivered'        => 'delivered',
        'completed'        => 'completed',
        'complete'         => 'completed',
    ];
    $isDisputeFlow = $orderStatus === 'not_received';
    $isReturnFlow  = in_array($orderStatus, [
        'cancelled','return_requested','return_pickup_scheduled','return_picked_up',
        'return_preparing','return_in_transit_to_seller','returned','refunded'
    ], true);
    $currentStage = $isReturnFlow ? null : ($statusToStage[$orderStatus] ?? 'placed');
    $currentStageIdx = $currentStage ? array_search($currentStage, $stageOrder) : -1;

    /* ── Action hints ── */
    $actionHints = [
        'confirmed'                   => 'You are accepting this order. The customer will be notified to prepare for shipping.',
        'packed'                      => 'Mark the items as packed and ready for courier pickup.',
        'shipped'                     => 'Enter the courier name and tracking number, then confirm shipment.',
        'in_transit'                  => 'The parcel is now moving through the delivery network.',
        'out_for_delivery'            => 'The courier is on the way to deliver the parcel to the customer.',
        'delivered'                   => 'Mark the parcel as delivered. The customer will then confirm receipt.',
        'cancelled'                   => 'Cancel this order. This cannot be undone.',
        // Dispute flow (customer never received the item — no physical return required)
        'refunded'                    => '⚠️ The customer reported this parcel was NOT received. Verify with the courier. If confirmed, click to issue a refund. Stock will be restored automatically.',
        // Return flow (customer received the item and wants to return it)
        'return_pickup_scheduled'     => 'Schedule a courier to pick up the return parcel from the customer.',
        'return_picked_up'            => 'Courier has picked up the return parcel from the customer.',
        'return_preparing'            => 'The return parcel is being processed at the sorting facility.',
        'return_in_transit_to_seller' => 'The return parcel is on its way back to your location.',
        'returned'                    => 'You have received the return parcel.',
    ];

    $showShipFields = old('status') === 'shipped' || $errors->has('shipping_method') || $errors->has('tracking_number');

    /* Current status badge style */
    $isCancelled  = $orderStatus === 'cancelled';
    $isDone       = in_array($orderStatus, ['completed','complete','refunded'], true);
    $badgeClass   = $isCancelled ? 'oa-status-cancel'
                  : ($isDisputeFlow ? 'oa-status-cancel'
                  : ($isReturnFlow  ? 'oa-status-return'
                  : ($isDone        ? 'oa-status-done' : '')));
    $badgeLabel   = $isDisputeFlow
                  ? '⚠️ Dispute — Item Not Received'
                  : ucwords(str_replace('_', ' ', $orderStatus));
@endphp

<section class="oa-page">

    <div class="oa-head">
        <h1 class="oa-title">Order #{{ $order->id }}</h1>
        <a href="{{ route('seller.orders.index') }}" class="oa-back">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="oa-grid">

        {{-- ══════════════ LEFT COLUMN ══════════════ --}}
        <div>

            {{-- Order summary --}}
            <div class="oa-card">
                <p class="oa-card-title"><i class="fas fa-receipt"></i> Order Summary</p>
                <div class="oa-meta">
                    <div class="oa-meta-row"><strong>Customer:</strong> {{ $order->name }}</div>
                    <div class="oa-meta-row"><strong>Email:</strong> {{ $order->email }}</div>
                    <div class="oa-meta-row"><strong>Date:</strong> {{ date('M d, Y', strtotime($order->placed_on)) }}</div>
                    <div class="oa-meta-row"><strong>Total:</strong> &#8369;{{ number_format($order->total_price, 2) }}</div>
                    <div class="oa-meta-row"><strong>Payment:</strong> {{ $order->method ?: 'N/A' }}</div>
                    <div class="oa-meta-row"><strong>Courier:</strong> {{ $order->shipping_method ?: 'Not yet set' }}</div>
                    <div class="oa-meta-row"><strong>Tracking #:</strong> {{ $order->tracking_number ?: 'Not yet set' }}</div>
                </div>

                {{-- Product rows --}}
                @foreach($order->orderItems as $item)
                    <div class="oa-item">
                        <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}">
                        <div>
                            <div class="oa-item-name">{{ $item->name }}</div>
                            <div class="oa-item-qty">Qty: {{ $item->quantity }}</div>
                        </div>
                        <div class="oa-item-sub">&#8369;{{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Progress stepper (only for normal delivery flow) --}}
            @if(!$isReturnFlow && !$isDisputeFlow)
                <div class="oa-card" style="overflow:hidden;">
                    <p class="oa-card-title"><i class="fas fa-map-marker-alt"></i> Delivery Progress</p>
                    <div class="oa-stepper">
                        @foreach($stages as $i => $stage)
                            @php
                                $stIdx = array_search($stage['key'], $stageOrder);
                                $stClass = $stIdx < $currentStageIdx ? 'done' : ($stIdx === $currentStageIdx ? 'active' : '');
                            @endphp
                            <div class="oa-step {{ $stClass }}">
                                <div class="oa-step-dot">
                                    @if($stIdx < $currentStageIdx)
                                        <i class="fas fa-check" style="font-size:.65rem;"></i>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </div>
                                <div class="oa-step-label">{{ str_replace('\n', "\n", $stage['label']) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Tracking action card ── --}}
            <div class="oa-card">
                <p class="oa-card-title"><i class="fas fa-truck"></i> Update Tracking</p>

                {{-- Current status badge --}}
                <div class="oa-current-badge {{ $badgeClass }}">
                    <i class="fas fa-circle" style="font-size:.6rem;"></i>
                    Current: {{ $badgeLabel }}
                </div>

                @if(session('success'))
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.55rem .7rem;margin-bottom:.6rem;font-size:.88rem;font-weight:700;color:#15803d;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="oa-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
                @endif

                @if(count($nextStatuses) === 0)
                    <div class="oa-no-action">
                        @if($isDone)
                            <i class="fas fa-check-circle" style="color:#16a34a;font-size:1.3rem;"></i><br>
                            <strong>Order Complete</strong><br>
                            <span style="font-size:.85rem;">No further actions required for this order.</span>
                        @elseif($isCancelled)
                            <i class="fas fa-times-circle" style="color:#b91c1c;font-size:1.3rem;"></i><br>
                            <strong>Order Cancelled</strong>
                        @else
                            Waiting for the customer to confirm receipt.
                        @endif
                    </div>
                @else
                    {{-- Separate primary next step from "cancel" --}}
                    @php
                        $primaryStatuses = array_filter($nextStatuses, fn($s) => $s !== 'cancelled');
                        $canCancel       = in_array('cancelled', $nextStatuses, true);
                    @endphp

                    @foreach($primaryStatuses as $statusKey)
                        <form action="{{ route('seller.orders.tracking', $order) }}" method="POST"
                              id="form-{{ $statusKey }}"
                              onsubmit="return confirmAction(this, '{{ $statusKey }}')"
                              style="margin-bottom:.5rem;">
                            @csrf

                            {{-- Hint --}}
                            @if(isset($actionHints[$statusKey]))
                                <div class="oa-hint"><i class="fas fa-info-circle"></i> {{ $actionHints[$statusKey] }}</div>
                            @endif

                            {{-- Shipped: extra fields --}}
                            @if($statusKey === 'shipped')
                                <div class="oa-ship-fields {{ $showShipFields ? 'show' : '' }}" id="shipFields">
                                    <div>
                                        <input class="oa-input" type="text" name="shipping_method"
                                               placeholder="Courier (e.g. J&T, LBC)"
                                               value="{{ old('shipping_method', $order->shipping_method) }}">
                                        @error('shipping_method')
                                            <div class="oa-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <input class="oa-input" type="text" name="tracking_number"
                                               placeholder="Tracking number"
                                               value="{{ old('tracking_number', $order->tracking_number) }}">
                                        @error('tracking_number')
                                            <div class="oa-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Date / time --}}
                            <div class="oa-dt-row">
                                <input type="date" name="event_date" id="eventDateInput"
                                       value="{{ old('event_date', now()->toDateString()) }}" required>
                                <input type="time" name="event_time" id="eventTimeInput"
                                       value="{{ old('event_time', now()->format('H:i')) }}" required>
                                <button type="button" class="oa-use-now" onclick="setNow()">Now</button>
                            </div>
                            @error('event_date')<div class="oa-error">{{ $message }}</div>@enderror
                            @error('event_time')<div class="oa-error">{{ $message }}</div>@enderror

                            {{-- Optional description --}}
                            <textarea class="oa-input oa-textarea" name="description"
                                      placeholder="Optional note / description">{{ old('description') }}</textarea>
                            <input type="text" class="oa-input" name="location" style="margin-top:.4rem;"
                                   placeholder="Location (optional)" value="{{ old('location') }}">

                            <button type="submit" name="status" value="{{ $statusKey }}"
                                    class="oa-btn-primary" style="margin-top:.7rem;"
                                    id="btn-{{ $statusKey }}"
                                    @if($statusKey === 'shipped') onclick="showShipFields()" @endif>
                                <i class="fas {{ $statusKey === 'cancelled' ? 'fa-times' : 'fa-arrow-right' }}"></i>
                                {{ $statusText[$statusKey] ?? ucwords(str_replace('_',' ',$statusKey)) }}
                            </button>
                        </form>
                    @endforeach

                    {{-- Cancel button (separate danger zone) --}}
                    @if($canCancel)
                        <form action="{{ route('seller.orders.tracking', $order) }}" method="POST"
                              onsubmit="return confirm('Cancel this order? This cannot be undone.');">
                            @csrf
                            <input type="hidden" name="event_date" value="{{ now()->toDateString() }}">
                            <input type="hidden" name="event_time" value="{{ now()->format('H:i') }}">
                            <button type="submit" name="status" value="cancelled" class="oa-btn-cancel">
                                <i class="fas fa-ban"></i> Cancel Order
                            </button>
                        </form>
                    @endif
                @endif
            </div>

        </div>

        {{-- ══════════════ RIGHT COLUMN: Timeline ══════════════ --}}
        <aside>
            <div class="oa-card" style="position:sticky;top:1.5rem;">
                <p class="oa-card-title"><i class="fas fa-history"></i> Tracking History</p>
                <div class="oa-timeline">
                    @forelse($order->tracking->sortByDesc('id') as $i => $event)
                        <div class="oa-tl-event {{ $i === 0 ? 'latest' : '' }}">
                            <div class="oa-tl-title">{{ $event->title }}</div>
                            <div class="oa-tl-meta">
                                {{ \Carbon\Carbon::parse($event->created_at)->format('M d, Y h:i A') }}
                                @if($event->location) &mdash; {{ $event->location }} @endif
                            </div>
                            @if($event->description)
                                <div class="oa-tl-desc">{{ $event->description }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="oa-tl-empty">No tracking events yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>

    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function pad2(v) { return String(v).padStart(2, '0'); }

function setNow() {
    const n = new Date();
    const d = document.getElementById('eventDateInput');
    const t = document.getElementById('eventTimeInput');
    if (d) d.value = n.getFullYear() + '-' + pad2(n.getMonth()+1) + '-' + pad2(n.getDate());
    if (t) t.value = pad2(n.getHours()) + ':' + pad2(n.getMinutes());
}

function showShipFields() {
    const sf = document.getElementById('shipFields');
    if (sf) sf.classList.add('show');
}

/* Confirmation for irreversible steps */
function confirmAction(form, status) {
    const msgs = {
        delivered : 'Mark this order as delivered? The customer will be asked to confirm receipt.',
        shipped   : 'Confirm order has been handed to courier?',
    };
    if (msgs[status]) return confirm(msgs[status]);
    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    setNow();
    /* Auto-show ship fields if there was a validation error on them */
    @if($showShipFields) showShipFields(); @endif
});

@if(session('success'))
Swal.fire({ toast:true, position:'top-end', icon:'success', title: @json(session('success')),
    showConfirmButton:false, timer:2800, timerProgressBar:true });
@endif
@if(session('error'))
Swal.fire({ icon:'error', title:'Action failed', text: @json(session('error')), confirmButtonColor:'#b91c1c' });
@endif
@if(session('info'))
Swal.fire({ icon:'info', title:'Notice', text: @json(session('info')), confirmButtonColor:'#1d4ed8' });
@endif
@if($errors->any() && !$errors->has('shipping_method') && !$errors->has('tracking_number'))
Swal.fire({ icon:'error', title:'Check the form', text: @json($errors->first()), confirmButtonColor:'#b91c1c' });
@endif
</script>
@endpush
