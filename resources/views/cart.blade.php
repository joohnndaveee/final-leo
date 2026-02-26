@extends('layouts.app')

@section('title', 'Shopping Cart - U-KAY HUB')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .cart-section {
        padding: 4rem 1.4rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 20rem);
    }

    .cart-layout {
        margin-top: 3rem;
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 3rem;
        align-items: start;
    }

    @media (max-width: 900px) {
        .cart-layout { grid-template-columns: 1fr; }
    }

    .cart-table-container {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0;
        overflow: hidden;
        box-shadow: none;
    }

    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table thead { background: #fff; border-bottom: 1px solid #e5e7eb; }

    .cart-table th {
        padding: 1.6rem 1.6rem;
        text-align: left;
        font-size: 1.35rem;
        font-weight: 700;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .cart-table td {
        padding: 2.2rem 1.6rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .cart-table tbody tr:last-child td { border-bottom: none; }
    .cart-table tbody tr:hover { background: #fafafa; }

    .product-cell { display: flex; align-items: center; gap: 1.4rem; }

    .product-image {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 0;
        background: #fff;
        border: 1px solid #e5e7eb;
    }

    .product-name { font-size: 1.6rem; font-weight: 500; color: #111827; }
    .price-cell { font-size: 1.55rem; font-weight: 500; color: #111827; }

    .quantity-input {
        width: 66px;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #e5e7eb;
        border-radius: 0;
        font-size: 1.4rem;
        font-weight: 600;
        color: #111827;
        background: #fff;
        text-align: center;
    }

    .remove-btn {
        border: none;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        font-size: 1.9rem;
        padding: .2rem;
    }
    .remove-btn:hover { color: #111827; }

    .cart-underbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.6rem;
        padding: 1.6rem;
        border: 1px solid #e5e7eb;
        border-top: none;
        background: #fff;
    }

    .coupon-box {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .coupon-box input {
        height: 40px;
        width: 260px;
        max-width: 100%;
        border: 1px solid #e5e7eb;
        padding: 0 12px;
        font-size: 1.4rem;
    }

    .btn-outline {
        height: 40px;
        padding: 0 18px;
        border: 1px solid #111827;
        background: #fff;
        color: #111827;
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        cursor: pointer;
    }

    .btn-outline:hover { background: #111827; color: #fff; }

    .cart-totals {
        border: 1px solid #e5e7eb;
        background: #fff;
        padding: 2rem;
        height: fit-content;
    }

    .cart-totals h2 {
        font-size: 2rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 1.6rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
    }

    .summary-row.total {
        border-top: 1px solid #e5e7eb;
        margin-top: 1rem;
        padding-top: 1.5rem;
    }

    .summary-label { font-size: 1.5rem; color: #111827; }
    .summary-value { font-size: 1.5rem; font-weight: 500; color: #111827; }
    .summary-row.total .summary-label { font-size: 1.6rem; font-weight: 600; }
    .summary-row.total .summary-value { font-size: 1.6rem; font-weight: 600; }

    .btn-proceed {
        width: 100%;
        margin-top: 1.8rem;
        height: 52px;
        border: 1px solid #111827;
        background: #fff;
        color: #111827;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-proceed:hover { background: #111827; color: #fff; }

    /* Empty Cart State */
    .empty-cart {
        text-align: center;
        padding: 5rem 2rem 3rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 1.2rem;
        border: 1px solid #e5e7eb;
        margin-top: 3rem;
    }

    .empty-cart i {
        font-size: 10rem;
        color: #d1d5db;
        margin-bottom: 2rem;
    }

    .empty-cart h2 {
        font-size: 2.5rem;
        color: #111827;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-cart p {
        font-size: 1.6rem;
        color: #6b7280;
        margin-bottom: 3rem;
    }

    .empty-cart .btn {
        display: inline-block;
        padding: 1rem 2rem;
        font-size: 1.4rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: auto;
        max-width: 250px;
    }

    .empty-cart .btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }

    .empty-cart .btn i {
        font-size: 1.4rem;
        margin-right: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cart-table-container { overflow-x: auto; }
        .cart-table { min-width: 760px; }
        .cart-underbar { flex-direction: column; align-items: stretch; }
        .coupon-box { justify-content: flex-start; }
    }

    @media (max-width: 450px) {
        .cart-section {
            padding: 2rem 1rem;
        }

        .product-cell {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')

<section class="cart-section">
    <h1 class="heading">Shopping Cart</h1>

    @if($cartItems->count() > 0)
        <div class="cart-layout">
            <div>
                <div class="cart-table-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Product</th>
                        <th style="width:140px;">Price</th>
                        <th style="width:160px;">Quantity</th>
                        <th style="width:160px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr data-cart-id="{{ $item->id }}">
                            <td>
                                <button class="remove-btn"
                                        data-cart-id="{{ $item->id }}"
                                        data-product-name="{{ $item->name }}"
                                        aria-label="Remove">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </td>
                            <td>
                                <div class="product-cell">
                                    <img src="{{ asset('uploaded_img/' . $item->image) }}" 
                                         alt="{{ $item->name }}" 
                                         class="product-image"
                                         onerror="this.src='{{ $siteLogoUrl ?? asset('images/logo.png') }}'">
                                    <div class="product-info">
                                        <span class="product-name">{{ $item->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="price-cell">₱{{ number_format($item->price, 2) }}</span>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    class="quantity-input"
                                    min="1"
                                    max="99"
                                    value="{{ (int) $item->quantity }}"
                                    data-cart-id="{{ $item->id }}"
                                >
                            </td>
                            <td>
                                <span class="price-cell subtotal" data-price="{{ $item->price }}">
                                    ₱{{ number_format($item->price * $item->quantity, 2) }}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            </div>

            <div class="cart-underbar">
                <div class="coupon-box">
                    <input type="text" id="coupon_code" placeholder="Coupon code" autocomplete="off">
                    <button type="button" class="btn-outline" id="apply_coupon_btn">Apply coupon</button>
                </div>
                <button type="button" class="btn-outline" id="update_cart_btn">Update cart</button>
            </div>
        </div>

            <aside class="cart-totals">
                <h2>Cart totals</h2>
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subtotal">₱{{ number_format((float) $grandTotal, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span class="summary-label">Total</span>
                    <span class="summary-value" id="grandTotal">₱{{ number_format((float) $grandTotal, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn-proceed">Proceed to checkout</a>
            </aside>
        </div>
    @else
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h2>Your cart is empty!</h2>
            <p>Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('home') }}" class="btn">
                <i class="fas fa-home"></i>
                Continue Shopping
            </a>
        </div>
    @endif
</section>

@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Update cart count in header
    function updateCartCount(count) {
        const cartLink = document.getElementById('cart-link');
        if (!cartLink) return;
        const existing = cartLink.querySelector('.cart-badge');
        if (count > 0) {
            if (existing) {
                existing.textContent = String(count);
            } else {
                const badge = document.createElement('span');
                badge.className = 'cart-badge';
                badge.textContent = String(count);
                cartLink.appendChild(badge);
            }
        } else if (existing) {
            existing.remove();
        }
    }

    // Calculate and update totals
    function updateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.cart-table tbody tr').forEach(row => {
            const priceElement = row.querySelector('.subtotal');
            if (priceElement) {
                const price = parseFloat(priceElement.dataset.price);
                const quantity = parseInt(row.querySelector('.quantity-input').value);
                const itemSubtotal = price * quantity;
                
                priceElement.textContent = `₱${itemSubtotal.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                subtotal += itemSubtotal;
            }
        });

        document.getElementById('subtotal').textContent = `₱${subtotal.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('grandTotal').textContent = `₱${subtotal.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    }

    // Quantity input change
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const row = this.closest('tr');
            let newQty = parseInt(this.value || '1', 10);
            if (!Number.isFinite(newQty) || newQty < 1) newQty = 1;
            if (newQty > 99) newQty = 99;
            this.value = String(newQty);
            updateQuantity(cartId, newQty, this, row);
        });
    });

    // Update quantity via AJAX
    function updateQuantity(cartId, newQty, input, row) {
        input.disabled = true;

        fetch(`/cart/${cartId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: newQty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = newQty;
                updateTotals();
                updateCartCount(data.cart_count);
                if (window.refreshCartDrawer) window.refreshCartDrawer();

                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update quantity',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        })
        .finally(() => {
            input.disabled = false;
        });
    }

    // Remove item from cart
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const productName = this.dataset.productName;
            const row = this.closest('tr');

            Swal.fire({
                title: 'Remove Item?',
                text: `Are you sure you want to remove "${productName}" from your cart?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeItem(cartId, row);
                }
            });
        });
    });

    // Remove item via AJAX
    function removeItem(cartId, row) {
        fetch(`/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row with animation
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                
                setTimeout(() => {
                    row.remove();
                    updateTotals();
                    updateCartCount(data.cart_count);
                    if (window.refreshCartDrawer) window.refreshCartDrawer();

                    // Check if cart is empty
                    if (document.querySelectorAll('.cart-table tbody tr').length === 0) {
                        location.reload();
                    }
                }, 300);

                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to remove item',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    // Coupon UI -> pass to checkout as query param (checkout page will auto-apply)
    const applyCouponBtn = document.getElementById('apply_coupon_btn');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', () => {
            const code = (document.getElementById('coupon_code')?.value || '').trim();
            if (!code) return;
            window.location.href = `{{ route('checkout') }}?voucher=${encodeURIComponent(code)}`;
        });
    }

    const updateCartBtn = document.getElementById('update_cart_btn');
    if (updateCartBtn) {
        updateCartBtn.addEventListener('click', () => window.location.reload());
    }
</script>
@endpush
