<div class="profile-notif-wrap">
    <div class="profile-notif-head">
        <h3><i class="fas fa-bell"></i> Notifications</h3>
        @if($notifications->count() > 0)
            <button type="button" class="profile-notif-btn" data-notif-mark-all="1">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="profile-notif-list">
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

                <div class="profile-notif-item {{ $n->is_read ? '' : 'unread' }}">
                    <div class="profile-notif-icon {{ $iconBg }}">
                        <i class="{{ explode(' ', $iconClass)[0] }} {{ explode(' ', $iconClass)[1] ?? '' }}"></i>
                    </div>
                    <div class="profile-notif-body">
                        <h4>{{ $n->title }}</h4>
                        <p>{{ $n->message }}</p>
                        <span class="profile-notif-time"><i class="fas fa-clock"></i> {{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    @if(!$n->is_read)
                        <button type="button" class="profile-notif-mark" data-notif-mark="{{ $n->id }}" title="Mark as read">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="profile-notif-empty">
            <i class="fas fa-bell-slash"></i>
            <p>You have no notifications yet.</p>
        </div>
    @endif
</div>
