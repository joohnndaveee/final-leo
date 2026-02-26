@extends('layouts.app')

@section('title', 'Checkout - U-KAY HUB')

@push('styles')
<style>
    .checkout-section {
        padding: 4rem 1.4rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 20rem);
    }

    .checkout-layout {
        margin-top: 2.5rem;
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 3rem;
        align-items: start;
    }

    @media (max-width: 980px) {
        .checkout-layout { grid-template-columns: 1fr; }
    }

    .panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 2.2rem;
    }

    .panel h2 {
        font-size: 1.8rem;
        color: #111827;
        font-weight: 600;
        margin: 0 0 1.2rem;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #111827;
        margin: 1.8rem 0 1rem;
    }

    .field {
        display: grid;
        gap: .6rem;
        margin-bottom: 1.4rem;
    }

    .field label {
        font-size: 1.3rem;
        font-weight: 600;
        color: #374151;
    }

    .field input,
    .field textarea,
    .field select {
        width: 100%;
        height: 44px;
        padding: 0 12px;
        border: 1px solid #e5e7eb;
        font-size: 1.4rem;
        background: #fff;
        outline: none;
    }

    .field textarea {
        height: auto;
        min-height: 110px;
        padding: 10px 12px;
        resize: vertical;
    }

    .field input:focus,
    .field textarea:focus,
    .field select:focus {
        border-color: #111827;
        box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.08);
    }

    .order-items {
        display: grid;
        gap: 1.2rem;
        padding: 0 0 1.4rem;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.4rem;
    }

    .order-item {
        display: grid;
        grid-template-columns: 54px 1fr auto;
        gap: 1.2rem;
        align-items: center;
    }

    .order-item-image {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .order-item-name {
        font-size: 1.4rem;
        font-weight: 500;
        color: #111827;
        line-height: 1.2;
    }

    .order-item-meta {
        font-size: 1.25rem;
        color: #6b7280;
        margin-top: .25rem;
    }

    .order-item-price {
        font-size: 1.35rem;
        color: #111827;
        font-weight: 600;
        white-space: nowrap;
    }

    .voucher-row {
        display: grid;
        grid-template-columns: 1fr 110px;
        gap: .8rem;
        margin-bottom: 1.4rem;
    }

    .voucher-row input {
        height: 44px;
        border: 1px solid #e5e7eb;
        padding: 0 12px;
        font-size: 1.4rem;
        text-transform: uppercase;
    }

    .btn-apply {
        height: 44px;
        border: 1px solid #111827;
        background: #e6dccb;
        color: #111827;
        font-size: 1.3rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
        cursor: pointer;
    }

    .btn-apply:hover { background: #d9cbb5; }

    .totals {
        display: grid;
        gap: .9rem;
        font-size: 1.4rem;
        color: #111827;
    }

    .totals .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .totals .grand { font-weight: 800; }

    .place-order-btn {
        width: 100%;
        margin-top: 2rem;
        height: 56px;
        border: 1px solid #111827;
        background: #d9cbb5;
        color: #111827;
        font-size: 1.4rem;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .8rem;
    }

    .place-order-btn:hover { background: #cbbca5; }

    .place-order-btn:disabled { opacity: .6; cursor: not-allowed; }

    .back-to-cart {
        display: inline-block;
        margin-top: 1.6rem;
        text-decoration: none;
        color: #111827;
        font-weight: 600;
        font-size: 1.35rem;
    }
</style>
@endpush

@section('content')
<section class="checkout-section">
    <h1 class="heading">Checkout</h1>

    <div class="checkout-layout">
        <div class="panel">
            <h2>Billing Details</h2>
            <form id="checkout-form">
                @csrf
                <input type="hidden" name="voucher_code" id="voucher_code_hidden">

                <div class="section-title">Contact</div>
                <div class="field">
                    <label>Email address</label>
                    <input type="email" value="{{ Auth::user()->email }}" readonly>
                </div>
                <div class="field">
                    <label for="phone">Phone <span style="color:#dc2626">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="e.g., 09123456789" required>
                </div>

                <div class="section-title">Delivery</div>
                <div class="field">
                    <label for="name">Full name <span style="color:#dc2626">*</span></label>
                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>
                </div>
                <div class="field">
                    <label for="address">Complete address <span style="color:#dc2626">*</span></label>
                    <textarea id="address" name="address" placeholder="House/Unit No., Street, Barangay, City, Province" required>{{ Auth::user()->address ?? '' }}</textarea>
                </div>

                <div class="section-title">Additional Information</div>
                <div class="field">
                    <label for="order_notes">Order notes (optional)</label>
                    <textarea id="order_notes" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                </div>

                <div class="field">
                    <label for="shipping_method">Shipping method</label>
                    <select id="shipping_method" name="shipping_method">
                        <option value="Standard">Standard (Free)</option>
                        <option value="Express">Express</option>
                    </select>
                </div>

                <div class="field">
                    <label for="payment_method">Payment method</label>
                    <select id="payment_method" name="payment_method">
                        <option value="Cash on Delivery">Cash on Delivery</option>
                        <option value="Mock Wallet">Mock Wallet</option>
                    </select>
                </div>

                <button type="submit" class="place-order-btn" id="place-order-btn">
                    <i class="fas fa-lock"></i>
                    <span id="place_order_label">Place Order ₱{{ number_format((float) $grandTotal, 2) }}</span>
                </button>

                <a href="{{ route('cart') }}" class="back-to-cart">← Back to cart</a>
            </form>
        </div>

        <aside class="panel">
            <h2>Your Order</h2>

            <div class="order-items">
                @foreach($cartItems as $item)
                    <div class="order-item">
                        <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}" class="order-item-image"
                             onerror="this.src='{{ $siteLogoUrl ?? asset('images/logo.png') }}'">
                        <div>
                            <div class="order-item-name">{{ $item->name }}</div>
                            <div class="order-item-meta">{{ (int) $item->quantity }} × ₱{{ number_format((float) $item->price, 2) }}</div>
                        </div>
                        <div class="order-item-price">₱{{ number_format((float) $item->price * (int) $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="voucher-row">
                <input type="text" id="voucher_code_input" placeholder="Coupon Code" autocomplete="off">
                <button type="button" class="btn-apply" id="voucher_apply_btn">Apply</button>
            </div>
            <div id="voucher_msg" style="font-size:1.25rem;margin-top:-.6rem;margin-bottom:1.2rem;"></div>

            <div class="totals">
                <div class="row">
                    <span>{{ ($itemDiscount ?? 0) > 0 ? 'Original Subtotal' : 'Subtotal' }}</span>
                    <span style="{{ ($itemDiscount ?? 0) > 0 ? 'text-decoration:line-through;color:#6b7280' : '' }}">₱{{ number_format((float) $subtotalRegular, 2) }}</span>
                </div>
                @if(($itemDiscount ?? 0) > 0)
                <div class="row" style="color:#16a34a">
                    <span>Item Discount</span>
                    <span>-₱{{ number_format((float) $itemDiscount, 2) }}</span>
                </div>
                <div class="row">
                    <span>Subtotal</span>
                    <span>₱{{ number_format((float) $subtotal, 2) }}</span>
                </div>
                @endif
                @if(isset($seasonalPromotion) && $seasonalPromotion)
                <div class="row" style="color:#16a34a">
                    <span>
                        Seasonal Sale
                        <span style="color:#6b7280;font-weight:600">
                            ({{ $seasonalPromotion->name }}
                            @if($seasonalPromotion->type === 'percentage')
                                {{ rtrim(rtrim(number_format((float) $seasonalPromotion->value, 2), '0'), '.') }}%
                            @else
                                ₱{{ number_format((float) $seasonalPromotion->value, 2) }}
                            @endif
                            )
                        </span>
                    </span>
                    <span>-₱{{ number_format((float) ($seasonalDiscount ?? 0), 2) }}</span>
                </div>
                @endif
                <div class="row" id="voucher_discount_row" style="display:none;color:#16a34a">
                    <span>Discount</span>
                    <span id="voucher_discount_display">-₱0.00</span>
                </div>
                <div class="row grand">
                    <span>Total</span>
                    <span id="grand_total_display">₱{{ number_format((float) $grandTotal, 2) }}</span>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout-form');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const placeOrderLabel = document.getElementById('place_order_label');

        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Disable the button to prevent double submission
            placeOrderBtn.disabled = true;
            if (placeOrderLabel) placeOrderLabel.textContent = 'Processing...';

            const formData = new FormData(checkoutForm);

            try {
                const response = await fetch('{{ route("order.place") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Show success toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    await Toast.fire({
                        icon: 'success',
                        title: data.message
                    });

                    // Clear cart badge in header
                    const cartLink = document.getElementById('cart-link');
                    const badge = cartLink ? cartLink.querySelector('.cart-badge') : null;
                    if (badge) badge.remove();

                    // Redirect to thank you page
                    window.location.href = '{{ url("/order/thank-you") }}/' + data.order_id;
                } else {
                    // Show error with detailed message if available
                    throw new Error(data.error || data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Failed to place order. Please try again.',
                    footer: 'Please check your internet connection and try again.'
                });

                // Re-enable the button
                placeOrderBtn.disabled = false;
                if (placeOrderLabel) placeOrderLabel.textContent = `Place Order ${document.getElementById('grand_total_display')?.textContent || ''}`.trim();
            }
        });

        // Auto-apply voucher from cart (?voucher=CODE) or remembered voucher
        const params = new URLSearchParams(window.location.search);
        const fromCart = (params.get('voucher') || '').trim();
        const remembered = (localStorage.getItem('checkout_voucher') || '').trim();
        const code = (fromCart || remembered).toUpperCase();
        if (code) {
            const input = document.getElementById('voucher_code_input');
            if (input) input.value = code;
            setTimeout(() => { if (typeof applyVoucher === 'function') applyVoucher(); }, 50);
        }
    });

    // Voucher application
    const baseTotal = {{ $grandTotal }};
    let appliedDiscount = 0;

    function applyVoucher() {
        const code = document.getElementById('voucher_code_input').value.trim().toUpperCase();
        const msg  = document.getElementById('voucher_msg');
        if (!code) { msg.style.color='#dc2626'; msg.textContent='Please enter a voucher code.'; return; }

        fetch('{{ route("voucher.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ code: code, order_total: baseTotal })
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                appliedDiscount = parseFloat(data.discount_amount ?? data.discount) || 0;
                document.getElementById('voucher_code_hidden').value = code;
                document.getElementById('voucher_discount_display').textContent = '-₱' + appliedDiscount.toFixed(2);
                document.getElementById('voucher_discount_row').style.display = 'flex';
                document.getElementById('grand_total_display').textContent = '₱' + Math.max(0, baseTotal - appliedDiscount).toFixed(2);
                msg.style.color = '#16a34a';
                msg.textContent = '✓ ' + (data.message || 'Voucher applied!');
                localStorage.setItem('checkout_voucher', code);
                const label = document.getElementById('place_order_label');
                if (label) label.textContent = `Place Order ₱${Math.max(0, baseTotal - appliedDiscount).toFixed(2)}`;
            } else {
                appliedDiscount = 0;
                document.getElementById('voucher_code_hidden').value = '';
                document.getElementById('voucher_discount_row').style.display = 'none';
                document.getElementById('grand_total_display').textContent = '₱' + baseTotal.toFixed(2);
                msg.style.color = '#dc2626';
                msg.textContent = data.message || 'Invalid voucher code.';
                localStorage.removeItem('checkout_voucher');
                const label = document.getElementById('place_order_label');
                if (label) label.textContent = `Place Order ₱${baseTotal.toFixed(2)}`;
            }
        })
        .catch(() => { msg.style.color='#dc2626'; msg.textContent='Error validating voucher.'; });
    }

    document.getElementById('voucher_apply_btn')?.addEventListener('click', applyVoucher);
</script>
@endpush
