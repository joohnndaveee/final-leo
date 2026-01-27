@extends('layouts.app')

@section('title', 'Shopping Cart - U-KAY HUB')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* Cart Section */
    .cart-section {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 20rem);
    }

    /* Cart Table Container */
    .cart-table-container {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.2rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-top: 3rem;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .cart-table th {
        padding: 1.5rem 2rem;
        text-align: left;
        font-size: 1.4rem;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cart-table td {
        padding: 2rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .cart-table tbody tr:last-child td {
        border-bottom: none;
    }

    .cart-table tbody tr:hover {
        background: #f9fafb;
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .product-image {
        width: 8rem;
        height: 8rem;
        object-fit: contain;
        border-radius: 0.8rem;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .product-name {
        font-size: 1.6rem;
        font-weight: 600;
        color: #111827;
    }

    .product-details {
        font-size: 1.3rem;
        color: #6b7280;
    }

    /* Price Cell */
    .price-cell {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--main-color);
    }

    /* Quantity Control */
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.6rem;
        padding: 0.5rem;
        width: fit-content;
    }

    .quantity-btn {
        width: 3rem;
        height: 3rem;
        border: none;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.4rem;
        cursor: pointer;
        font-size: 1.6rem;
        font-weight: 600;
        color: #374151;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover {
        background: var(--main-color);
        color: white;
        border-color: var(--main-color);
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-input {
        width: 5rem;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
    }

    /* Remove Button */
    .remove-btn {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 0.8rem 1.5rem;
        border-radius: 0.6rem;
        cursor: pointer;
        font-size: 1.4rem;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .remove-btn:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }

    /* Cart Summary */
    .cart-summary {
        background: #f9fafb;
        border-top: 2px solid #e5e7eb;
        padding: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
    }

    .summary-row.total {
        border-top: 2px solid #e5e7eb;
        margin-top: 1rem;
        padding-top: 1.5rem;
    }

    .summary-label {
        font-size: 1.6rem;
        color: #6b7280;
    }

    .summary-value {
        font-size: 1.8rem;
        font-weight: 600;
        color: #111827;
    }

    .summary-row.total .summary-label {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
    }

    .summary-row.total .summary-value {
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--main-color);
    }

    /* Action Buttons */
    .cart-actions {
        display: flex;
        gap: 1.5rem;
        margin-top: 3rem;
        justify-content: space-between;
    }

    .btn-continue {
        background: #ffffff;
        color: #374151;
        border: 1px solid #e5e7eb;
        padding: 1.5rem 3rem;
        border-radius: 0.6rem;
        font-size: 1.6rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-continue:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .btn-checkout {
        background: var(--main-color);
        color: white;
        border: none;
        padding: 1.5rem 4rem;
        border-radius: 0.6rem;
        font-size: 1.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-checkout:hover {
        background: #2da820;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

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
        .cart-table-container {
            overflow-x: auto;
        }

        .cart-table {
            min-width: 800px;
        }

        .cart-actions {
            flex-direction: column;
        }

        .btn-continue,
        .btn-checkout {
            width: 100%;
            justify-content: center;
        }
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
        <div class="cart-table-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr data-cart-id="{{ $item->id }}">
                            <td>
                                <div class="product-cell">
                                    <img src="{{ asset('uploaded_img/' . $item->image) }}" 
                                         alt="{{ $item->name }}" 
                                         class="product-image"
                                         onerror="this.src='{{ asset('images/logo.png') }}'">
                                    <div class="product-info">
                                        <span class="product-name">{{ $item->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="price-cell">₱{{ number_format($item->price, 2) }}</span>
                            </td>
                            <td>
                                <div class="quantity-control">
                                    <button class="quantity-btn decrease-btn" 
                                            data-cart-id="{{ $item->id }}"
                                            data-current-qty="{{ $item->quantity }}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="text" 
                                           class="quantity-input" 
                                           value="{{ $item->quantity }}" 
                                           readonly>
                                    <button class="quantity-btn increase-btn" 
                                            data-cart-id="{{ $item->id }}"
                                            data-current-qty="{{ $item->quantity }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span class="price-cell subtotal" data-price="{{ $item->price }}">
                                    ₱{{ number_format($item->price * $item->quantity, 2) }}
                                </span>
                            </td>
                            <td>
                                <button class="remove-btn" 
                                        data-cart-id="{{ $item->id }}"
                                        data-product-name="{{ $item->name }}">
                                    <i class="fas fa-trash"></i>
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="summary-row">
                    <span class="summary-label">Subtotal:</span>
                    <span class="summary-value" id="subtotal">₱{{ number_format($grandTotal, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span class="summary-label">Grand Total:</span>
                    <span class="summary-value" id="grandTotal">₱{{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="cart-actions">
            <a href="{{ route('shop') }}" class="btn-continue">
                <i class="fas fa-arrow-left"></i>
                Continue Shopping
            </a>
            <a href="{{ route('checkout') }}" class="btn-checkout">
                Proceed to Checkout
                <i class="fas fa-arrow-right"></i>
            </a>
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
        const cartCountElements = document.querySelectorAll('.header .icons a[href*="cart"] span');
        cartCountElements.forEach(element => {
            element.textContent = `(${count})`;
        });
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

    // Increase quantity
    document.querySelectorAll('.increase-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const row = this.closest('tr');
            const input = row.querySelector('.quantity-input');
            const currentQty = parseInt(input.value);
            const newQty = currentQty + 1;

            if (newQty > 99) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Quantity',
                    text: 'You cannot add more than 99 items.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            updateQuantity(cartId, newQty, input, row);
        });
    });

    // Decrease quantity
    document.querySelectorAll('.decrease-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const row = this.closest('tr');
            const input = row.querySelector('.quantity-input');
            const currentQty = parseInt(input.value);
            const newQty = currentQty - 1;

            if (newQty < 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimum Quantity',
                    text: 'Quantity cannot be less than 1. Use Remove button to delete item.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            updateQuantity(cartId, newQty, input, row);
        });
    });

    // Update quantity via AJAX
    function updateQuantity(cartId, newQty, input, row) {
        const buttons = row.querySelectorAll('.quantity-btn');
        buttons.forEach(btn => btn.disabled = true);

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
            buttons.forEach(btn => btn.disabled = false);
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
</script>
@endpush
