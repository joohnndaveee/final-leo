@extends('layouts.app')

@section('title', 'Notifications')

@push('styles')
<style>
.notifications-wrap { max-width:700px; margin:2rem auto; }
.notif-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
.notif-header h2 { font-size:1.5rem; font-weight:700; }
.btn-text { background:none; border:none; color:#2d5016; font-size:.9rem; font-weight:600; cursor:pointer; padding:.4rem .8rem; border-radius:8px; }
.btn-text:hover { background:#f0fdf4; }
.notif-list { display:flex; flex-direction:column; gap:.8rem; }
.notif-item { display:flex; gap:1rem; align-items:flex-start; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1rem 1.2rem; transition:.15s; }
.notif-item.unread { border-left:4px solid #2d5016; background:#f0fdf4; }
.notif-item:hover { box-shadow:0 2px 8px rgba(0,0,0,.07); }
.notif-icon { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1rem; }
.ni-order    { background:#dcfce7; color:#166534; }
.ni-shipped  { background:#dbeafe; color:#1e40af; }
.ni-payment  { background:#fef3c7; color:#92400e; }
.ni-promo    { background:#ede9fe; color:#5b21b6; }
.ni-default  { background:#f3f4f6; color:#6b7280; }
.notif-body h4 { font-size:.95rem; font-weight:600; color:#111827; margin:0 0 .2rem; }
.notif-body p  { font-size:.85rem; color:#6b7280; margin:0 0 .3rem; line-height:1.5; }
.notif-time    { font-size:.75rem; color:#9ca3af; }
.empty-state   { text-align:center; padding:4rem 2rem; color:#9ca3af; }
.empty-state i { font-size:3rem; margin-bottom:1rem; display:block; }
</style>
@endpush

@section('content')
<div class="notifications-wrap">
    <div class="notif-header">
        <h2><i class="fas fa-bell"></i> Notifications</h2>
        @if($notifications->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" class="btn-text"><i class="fas fa-check-double"></i> Mark All Read</button>
        </form>
        @endif
    </div>

    @if($notifications->count() > 0)
    <div class="notif-list">
        @foreach($notifications as $n)
        @php
            $iconClass = match($n->type) {
                'order_placed','order_completed' => 'fas fa-box ni-order',
                'order_shipped','order_delivered' => 'fas fa-truck ni-shipped',
                'payment' => 'fas fa-credit-card ni-payment',
                'promo','discount','voucher' => 'fas fa-tag ni-promo',
                default => 'fas fa-bell ni-default',
            };
            $iconBg = match(true) {
                str_contains($iconClass,'ni-order')   => 'ni-order',
                str_contains($iconClass,'ni-shipped') => 'ni-shipped',
                str_contains($iconClass,'ni-payment') => 'ni-payment',
                str_contains($iconClass,'ni-promo')   => 'ni-promo',
                default => 'ni-default',
            };
        @endphp
        <div class="notif-item {{ $n->is_read ? '' : 'unread' }}">
            <div class="notif-icon {{ $iconBg }}">
                <i class="{{ explode(' ', $iconClass)[0] }} {{ explode(' ', $iconClass)[1] ?? '' }}"></i>
            </div>
            <div class="notif-body" style="flex:1">
                <h4>{{ $n->title }}</h4>
                <p>{{ $n->message }}</p>
                <span class="notif-time"><i class="fas fa-clock"></i> {{ $n->created_at->diffForHumans() }}</span>
            </div>
            @if(!$n->is_read)
            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:.2rem" title="Mark as read">
                    <i class="fas fa-check"></i>
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-bell-slash"></i>
        <p>You have no notifications yet.</p>
    </div>
    @endif
</div>
@endsection
