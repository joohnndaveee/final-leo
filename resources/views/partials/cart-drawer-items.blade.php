@php
    $currency = fn($n) => '₱' . number_format((float) $n, 2);
@endphp

<div class="cart-drawer__header">
    <div class="cart-drawer__title">Shopping Cart</div>
    <button type="button" class="cart-drawer__close" data-cart-drawer-close aria-label="Close cart">
        <i class="fas fa-times"></i>
    </button>
</div>

@if($cartItems->count() > 0)
    <div class="cart-drawer__items">
        @foreach($cartItems as $item)
            <div class="cart-drawer__item" data-cart-id="{{ $item->id }}">
                <img
                    class="cart-drawer__img"
                    src="{{ asset('uploaded_img/' . $item->image) }}"
                    alt="{{ $item->name }}"
                    onerror="this.src='{{ $siteLogoUrl ?? asset('images/logo.png') }}'"
                >
                <div class="cart-drawer__meta">
                    <div class="cart-drawer__name">{{ $item->name }}</div>
                    <div class="cart-drawer__line">
                        <span>{{ (int) $item->quantity }} × {{ $currency($item->price) }}</span>
                    </div>
                </div>
                <button
                    type="button"
                    class="cart-drawer__remove"
                    title="Remove"
                    aria-label="Remove item"
                    data-cart-remove="{{ $item->id }}"
                >
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        @endforeach
    </div>

    <div class="cart-drawer__footer">
        <div class="cart-drawer__subtotal">
            <span>Subtotal:</span>
            <strong>{{ $currency($subtotal) }}</strong>
        </div>
        <div class="cart-drawer__actions">
            <a href="{{ route('cart') }}" class="cart-drawer__btn cart-drawer__btn--outline">View cart</a>
            <a href="{{ route('checkout') }}" class="cart-drawer__btn cart-drawer__btn--outline">Checkout</a>
        </div>
    </div>
@else
    <div class="cart-drawer__empty">
        <div class="cart-drawer__empty-icon"><i class="fas fa-box-open"></i></div>
        <div class="cart-drawer__empty-title">Your cart is empty</div>
        <div class="cart-drawer__empty-text">Add some products to get started.</div>
        <a href="{{ route('shop') }}" class="cart-drawer__btn cart-drawer__btn--outline" style="margin-top:1.2rem;">Continue shopping</a>
    </div>
@endif

