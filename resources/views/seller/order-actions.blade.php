@extends('layouts.seller')

@section('title', 'Order Actions')

@push('styles')
<style>
    .actions-page {
        max-width: 1080px;
        margin: 1.2rem auto 1.8rem;
        padding: 0 1rem;
    }
    .actions-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .actions-title {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 800;
        color: #064e3b;
    }
    .back-btn {
        text-decoration: none;
        background: #64748b;
        color: #fff;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.95rem;
        font-weight: 700;
    }
    .actions-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
        gap: 1rem;
        align-items: start;
    }
    .card {
        background: #fff;
        border: 1px solid rgba(16, 185, 129, 0.16);
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        padding: 1rem;
    }
    .card + .card { margin-top: 1rem; }
    .card h3 {
        margin: 0 0 0.75rem;
        color: #065f46;
        font-size: 1.25rem;
        font-weight: 800;
    }
    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem 0.85rem;
    }
    .meta {
        font-size: 0.95rem;
        color: #334155;
        line-height: 1.4;
    }
    .status-row {
        margin-bottom: 0.8rem;
    }
    .status-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }
    .status-btn {
        border: 1px solid #0f766e;
        background: #ecfeff;
        color: #0f766e;
        border-radius: 999px;
        padding: 0.38rem 0.72rem;
        font-size: 0.87rem;
        font-weight: 800;
        cursor: pointer;
    }
    .status-btn.active {
        background: #0f766e;
        color: #fff;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.58rem;
    }
    .full { grid-column: 1 / -1; }
    .field-stack {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    .form-grid input,
    .form-grid textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.56rem 0.7rem;
        font-size: 0.95rem;
        background: #fff;
    }
    .form-grid textarea {
        min-height: 84px;
        resize: vertical;
    }
    .hint-box {
        margin: 0;
        padding: 0.56rem 0.68rem;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        font-size: 0.9rem;
        color: #475569;
    }
    .field-error {
        color: #b91c1c;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 0.34rem 0.5rem;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .time-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .use-now {
        border: 0;
        border-radius: 8px;
        background: #0ea5e9;
        color: #fff;
        padding: 0.46rem 0.7rem;
        font-size: 0.83rem;
        font-weight: 800;
        cursor: pointer;
        white-space: nowrap;
    }
    .ship-fields { display: none; }
    .ship-fields.visible { display: contents; }
    .ship-submit { display: none; }
    .ship-submit.visible { display: inline-flex; }
    .submit-btn {
        border: 0;
        border-radius: 10px;
        padding: 0.64rem 0.9rem;
        font-size: 0.95rem;
        font-weight: 800;
        color: #fff;
        background: linear-gradient(135deg, #10b981, #059669);
        cursor: pointer;
        justify-content: center;
    }
    .timeline {
        max-height: 72vh;
        overflow: auto;
        padding-right: 0.2rem;
    }
    .event {
        border-left: 2px solid #d1d5db;
        padding: 0.2rem 0 0.75rem 0.7rem;
        margin-left: 0.25rem;
    }
    .event-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
    }
    .event-meta {
        font-size: 0.84rem;
        color: #64748b;
    }
    .empty-note {
        font-size: 0.9rem;
        color: #64748b;
    }
    @media (max-width: 980px) {
        .actions-shell {
            grid-template-columns: 1fr;
        }
        .meta-grid,
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $showShipFields = old('status') === 'shipped' || $errors->has('shipping_method') || $errors->has('tracking_number');
@endphp

<section class="actions-page">
    <div class="actions-head">
        <h1 class="actions-title">Order #{{ $order->id }} Actions</h1>
        <a href="{{ route('seller.orders.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Orders</a>
    </div>

    <div class="actions-shell">
        <div>
            <div class="card">
                <h3>Order Info</h3>
                <div class="meta-grid">
                    <div class="meta"><strong>Customer:</strong> {{ $order->name }}</div>
                    <div class="meta"><strong>Email:</strong> {{ $order->email }}</div>
                    <div class="meta"><strong>Order Date:</strong> {{ date('M d, Y', strtotime($order->placed_on)) }}</div>
                    <div class="meta"><strong>Status:</strong> {{ ucfirst($order->status ?? $order->payment_status) }}</div>
                    <div class="meta"><strong>Total:</strong> &#8369;{{ number_format($order->total_price, 2) }}</div>
                    <div class="meta"><strong>Courier:</strong> {{ $order->shipping_method ?: 'N/A' }}</div>
                    <div class="meta"><strong>Tracking #:</strong> {{ $order->tracking_number ?: 'N/A' }}</div>
                </div>
            </div>

            <div class="card">
                <h3>Tracking Update</h3>
                <form action="{{ route('seller.orders.tracking', $order) }}" method="POST" class="form-grid">
                    @csrf

                    <div class="status-row full">
                        @error('status')
                            <div class="field-error" style="margin-bottom:.45rem;">{{ $message }}</div>
                        @enderror
                        @if(count($nextStatuses) > 0)
                            <div class="status-buttons">
                                @foreach($nextStatuses as $statusKey)
                                    @if($statusKey === 'shipped')
                                        <button type="button"
                                                class="status-btn {{ $showShipFields ? 'active' : '' }}"
                                                data-status-btn
                                                data-value="{{ $statusKey }}"
                                                onclick="setNextStatus(this)">
                                            {{ $statusText[$statusKey] ?? $statusKey }}
                                        </button>
                                    @else
                                        <button type="submit"
                                                name="status"
                                                value="{{ $statusKey }}"
                                                class="status-btn">
                                            {{ $statusText[$statusKey] ?? $statusKey }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="hint-box">No next timeline action is available for this order status.</div>
                        @endif
                    </div>

                    <div id="shipFields" class="ship-fields full {{ $showShipFields ? 'visible' : '' }}">
                        <div class="form-grid">
                            <div class="field-stack">
                                <input type="text" name="shipping_method" placeholder="Courier (e.g., J&T)" value="{{ old('shipping_method') }}">
                                @error('shipping_method')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field-stack">
                                <input type="text" name="tracking_number" placeholder="Tracking number" value="{{ old('tracking_number') }}">
                                @error('tracking_number')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="field-stack full">
                        <input type="text" name="location" placeholder="Current location (optional)" value="{{ old('location') }}">
                        @error('location')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <p class="hint-box full">Only the next valid step is enabled. Sequence is enforced.</p>

                    <div class="field-stack">
                        <input id="eventDateInput" type="date" name="event_date" value="{{ old('event_date', now()->toDateString()) }}" required>
                        @error('event_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-stack">
                        <div class="time-row">
                            <input id="eventTimeInput" type="time" name="event_time" value="{{ old('event_time', now()->format('H:i')) }}" required>
                            <button type="button" class="use-now" onclick="setTrackingNow()">Use Current</button>
                        </div>
                        @error('event_time')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-stack full">
                        <textarea name="description" placeholder="Description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button id="shipSubmitBtn"
                            type="submit"
                            name="status"
                            value="shipped"
                            class="submit-btn full ship-submit {{ $showShipFields ? 'visible' : '' }}"
                            {{ count($nextStatuses) === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-location-arrow"></i> Save Order Shipped
                    </button>
                </form>
            </div>
        </div>

        <aside>
            <div class="card">
                <h3>Tracking Timeline</h3>
                <div class="timeline">
                    @forelse($order->tracking->sortByDesc('id') as $event)
                        <div class="event">
                            <div class="event-title">{{ $event->title }}</div>
                            <div class="event-meta">{{ \Carbon\Carbon::parse($event->created_at)->format('M d, Y h:i A') }} @if($event->location)- {{ $event->location }} @endif</div>
                            @if($event->description)
                                <div class="event-meta">{{ $event->description }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="empty-note">No tracking events yet.</p>
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
function pad2(value) {
    return String(value).padStart(2, '0');
}

function setTrackingNow() {
    const now = new Date();
    const dateInput = document.getElementById('eventDateInput');
    const timeInput = document.getElementById('eventTimeInput');
    if (!dateInput || !timeInput) return;

    dateInput.value = now.getFullYear() + '-' + pad2(now.getMonth() + 1) + '-' + pad2(now.getDate());
    timeInput.value = pad2(now.getHours()) + ':' + pad2(now.getMinutes());
}

function setNextStatus(button) {
    const shipFields = document.getElementById('shipFields');
    const shipSubmitBtn = document.getElementById('shipSubmitBtn');

    document.querySelectorAll('[data-status-btn]').forEach((btn) => btn.classList.remove('active'));
    button.classList.add('active');

    const selected = button.getAttribute('data-value');
    if (shipFields) shipFields.classList.toggle('visible', selected === 'shipped');
    if (shipSubmitBtn) shipSubmitBtn.classList.toggle('visible', selected === 'shipped');
}

document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('eventDateInput');
    const timeInput = document.getElementById('eventTimeInput');
    if (dateInput && timeInput && (!dateInput.value || !timeInput.value)) {
        setTrackingNow();
    }
    const active = document.querySelector('[data-status-btn].active');
    if (active) setNextStatus(active);
});

@if(session('success'))
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: @json(session('success')),
    showConfirmButton: false,
    timer: 2800,
    timerProgressBar: true
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Action failed',
    text: @json(session('error')),
    confirmButtonColor: '#b91c1c'
});
@endif

@if(session('info'))
Swal.fire({
    icon: 'info',
    title: 'Notice',
    text: @json(session('info')),
    confirmButtonColor: '#1d4ed8'
});
@endif

@if($errors->any() && !$errors->has('shipping_method') && !$errors->has('tracking_number'))
Swal.fire({
    icon: 'error',
    title: 'Please check the form',
    text: @json($errors->first()),
    confirmButtonColor: '#b91c1c'
});
@endif
</script>
@endpush
