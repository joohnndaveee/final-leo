@extends('layouts.app')

@section('title', 'Checkout - U-KAY HUB')

@push('styles')
<style>
    .checkout-section {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .checkout-section .heading {
        font-size: 3rem;
        color: #27ae60;
        text-align: center;
        margin-bottom: 3rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }

    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }

    /* Checkout Form */
    .checkout-form-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2.5rem;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(39, 174, 96, 0.2);
    }

    .checkout-form-card h2 {
        font-size: 2rem;
        color: #27ae60;
        margin-bottom: 2rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 0.8rem;
        font-weight: 500;
    }

    .form-group label span {
        color: #e74c3c;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 1.2rem;
        font-size: 1.4rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #27ae60;
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Order Summary */
    .order-summary-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2.5rem;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(39, 174, 96, 0.2);
        height: fit-content;
    }

    .order-summary-card h2 {
        font-size: 2rem;
        color: #27ae60;
        margin-bottom: 2rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 1rem;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid #eee;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #27ae60;
    }

    .order-item-details {
        flex: 1;
    }

    .order-item-name {
        font-size: 1.5rem;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .order-item-meta {
        font-size: 1.3rem;
        color: #666;
    }

    .order-item-meta span {
        color: #27ae60;
        font-weight: 600;
    }

    .order-total {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #27ae60;
    }

    .order-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .order-total-row.grand-total {
        font-size: 2rem;
        font-weight: 700;
        color: #27ae60;
    }

    /* Place Order Button */
    .place-order-btn {
        width: 100%;
        padding: 1.5rem;
        font-size: 1.6rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
    }

    .place-order-btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        transform: translateY(-2px);
    }

    .place-order-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .back-to-cart {
        display: inline-block;
        text-align: center;
        padding: 1rem 2rem;
        font-size: 1.4rem;
        color: #27ae60;
        border: 2px solid #27ae60;
        border-radius: 8px;
        text-decoration: none;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
    }

    .back-to-cart:hover {
        background: #27ae60;
        color: white;
    }
</style>
@endpush

@section('content')
<section class="checkout-section">
    <h1 class="heading">Checkout</h1>

    <div class="checkout-container">
        <!-- Checkout Form -->
        <div class="checkout-form-card">
            <h2>Delivery Information</h2>
            <form id="checkout-form">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name <span>*</span></label>
                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number <span>*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="e.g., 09123456789" required>
                </div>

                <div class="form-group">
                    <label for="address">Complete Address <span>*</span></label>
                    <textarea id="address" name="address" placeholder="House/Unit No., Street, Barangay, City, Province" required>{{ Auth::user()->address ?? '' }}</textarea>
                </div>

                <button type="submit" class="place-order-btn" id="place-order-btn">
                    Place Order (Cash on Delivery)
                </button>

                <a href="{{ route('cart') }}" class="back-to-cart">← Back to Cart</a>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-card">
            <h2>Order Summary</h2>
            <div class="order-items-list">
                @foreach($cartItems as $item)
                    <div class="order-item">
                        <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}" class="order-item-image">
                        <div class="order-item-details">
                            <div class="order-item-name">{{ $item->name }}</div>
                            <div class="order-item-meta">
                                ₱{{ number_format($item->price, 2) }} × <span>{{ $item->quantity }}</span> = 
                                ₱{{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="order-total">
                <div class="order-total-row">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($grandTotal, 2) }}</span>
                </div>
                <div class="order-total-row">
                    <span>Delivery Fee:</span>
                    <span>FREE</span>
                </div>
                <div class="order-total-row grand-total">
                    <span>Grand Total:</span>
                    <span>₱{{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout-form');
        const placeOrderBtn = document.getElementById('place-order-btn');

        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Disable the button to prevent double submission
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Processing...';

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

                    // Update cart count in header
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(el => el.textContent = '0');

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
                placeOrderBtn.textContent = 'Place Order (Cash on Delivery)';
            }
        });
    });
</script>
@endpush
