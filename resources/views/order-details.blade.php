@extends('layouts.app')

@section('title', 'Order Details - U-KAY HUB')

@push('styles')
<style>
    .order-details-section {
        padding: 2rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.5rem;
        background: white;
        color: #27ae60;
        border: 2px solid #27ae60;
        border-radius: 6px;
        text-decoration: none;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background: #27ae60;
        color: white;
    }

    .order-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .order-header h1 {
        font-size: 2.5rem;
        color: #27ae60;
        margin-bottom: 0.5rem;
    }

    .order-header .order-date {
        font-size: 1.4rem;
        color: #666;
    }

    .order-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
    }

    .info-card h2 {
        font-size: 1.8rem;
        color: #27ae60;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 0.8rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        font-size: 1.3rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 600;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 1.2rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-complete,
    .status-completed,
    .status-delivered {
        background: #d4edda;
        color: #155724;
    }

    .products-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
        grid-column: 1 / -1;
    }

    .products-card h2 {
        font-size: 1.8rem;
        color: #27ae60;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 0.8rem;
    }

    .product-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        align-items: center;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #27ae60;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .product-name {
        font-size: 1.6rem;
        color: #333;
        font-weight: 600;
    }

    .product-meta {
        font-size: 1.3rem;
        color: #666;
    }

    .product-price-section {
        text-align: right;
    }

    .product-price {
        font-size: 1.4rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .product-subtotal {
        font-size: 1.8rem;
        color: #27ae60;
        font-weight: 700;
    }

    .order-summary {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #27ae60;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        font-size: 1.5rem;
    }

    .summary-row.grand-total {
        font-size: 2rem;
        font-weight: 700;
        color: #27ae60;
        border-top: 2px solid #27ae60;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .review-button {
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.3rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .review-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .review-button:disabled,
    .reviewed-badge {
        background: #95a5a6;
        cursor: not-allowed;
        opacity: 0.6;
    }

    @media (max-width: 968px) {
        .order-content {
            grid-template-columns: 1fr;
        }

        .product-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .product-image {
            width: 80px;
            height: 80px;
        }

        .product-price-section,
        .review-button {
            grid-column: 2;
            text-align: left;
            margin-top: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .product-item {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .product-image {
            margin: 0 auto;
        }

        .product-price-section {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<section class="order-details-section">
    <a href="{{ route('orders') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to My Orders
    </a>

    <div class="order-header">
        <h1>Order #{{ $order->id }}</h1>
        <p class="order-date">Placed on {{ date('F d, Y', strtotime($order->placed_on)) }}</p>
    </div>

    <div class="order-content">
        <!-- Order Information -->
        <div class="info-card">
            <h2>Order Information</h2>
            <div class="info-row">
                <span class="info-label">Order ID:</span>
                <span class="info-value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ date('M d, Y', strtotime($order->placed_on)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ strtolower($order->status ?? $order->payment_status) }}">
                    {{ strtoupper($order->status ?? $order->payment_status) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ $order->method }}</span>
            </div>
            @if($order->tracking_number)
                <div class="info-row">
                    <span class="info-label">Tracking #:</span>
                    <span class="info-value">{{ $order->tracking_number }} ({{ $order->shipping_method }})</span>
                </div>
            @endif
        </div>

        <!-- Delivery Information -->
        <div class="info-card">
            <h2>Delivery Information</h2>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $order->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $order->number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $order->address }}</span>
            </div>
        </div>
    </div>

    <!-- Products Ordered -->
    <div class="products-card">
        <h2>Products Ordered</h2>
        @foreach($order->orderItems as $item)
            @php
                $statusValue = strtolower($order->status ?? $order->payment_status);
                $isCompleted = in_array($statusValue, ['completed', 'complete', 'delivered', 'paid']);
                $hasReviewed = false;
                if (Auth::check()) {
                    $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                                                      ->where('product_id', $item->product_id)
                                                      ->where('order_id', $order->id)
                                                      ->exists();
                }
            @endphp
            <div class="product-item">
                <img src="{{ asset('uploaded_img/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
                
                <div class="product-info">
                    <div class="product-name">{{ $item->name }}</div>
                    <div class="product-meta">
                        Price: ₱{{ number_format($item->price, 2) }} × Quantity: {{ $item->quantity }}
                    </div>
                </div>

                <div class="product-price-section">
                    <div class="product-price">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</div>
                    <div class="product-subtotal">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
                </div>

                @if(Auth::check())
                    @if($isCompleted && !$hasReviewed)
                        <button class="review-button" onclick="openReviewModal({{ $item->product_id }}, '{{ $item->name }}', {{ $order->id }})">
                            <i class="fas fa-star"></i> Review
                        </button>
                    @elseif($hasReviewed)
                        <span class="review-button reviewed-badge">
                            <i class="fas fa-check"></i> Reviewed
                        </span>
                    @endif
                @endif
            </div>
        @endforeach

        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₱{{ number_format($order->total_price, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Delivery Fee:</span>
                <span>FREE</span>
            </div>
            <div class="summary-row grand-total">
                <span>Grand Total:</span>
                <span>₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Review Modal -->
<div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 3rem; border-radius: 15px; max-width: 500px; width: 90%; position: relative;">
        <button onclick="closeReviewModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 2rem; cursor: pointer; color: #666;">&times;</button>
        
        <h2 style="color: #27ae60; margin-bottom: 2rem; font-size: 2rem;">Rate this Product</h2>
        <p id="reviewProductName" style="font-size: 1.6rem; color: #333; margin-bottom: 2rem; font-weight: 600;"></p>
        
        <div style="margin-bottom: 2rem;">
            <p style="font-size: 1.4rem; margin-bottom: 1rem; color: #666;">Your Rating:</p>
            <div id="starRating" style="font-size: 3rem; color: #ffd700; cursor: pointer; display: flex; gap: 0.5rem;">
                <span onclick="setRating(1)">☆</span>
                <span onclick="setRating(2)">☆</span>
                <span onclick="setRating(3)">☆</span>
                <span onclick="setRating(4)">☆</span>
                <span onclick="setRating(5)">☆</span>
            </div>
        </div>
        
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 1.4rem; margin-bottom: 0.8rem; color: #666;">Your Review (Optional):</label>
            <textarea id="reviewComment" rows="5" style="width: 100%; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; font-size: 1.4rem; font-family: inherit;" placeholder="Share your experience with this product..."></textarea>
        </div>
        
        <button onclick="submitReview()" style="width: 100%; padding: 1.2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 1.6rem; font-weight: 600; cursor: pointer;">
            Submit Review
        </button>
    </div>
</div>

@push('scripts')
<script>
let currentProductId = null;
let currentOrderId = null;
let currentRating = 5;

function openReviewModal(productId, productName, orderId) {
    currentProductId = productId;
    currentOrderId = orderId;
    currentRating = 5;
    
    document.getElementById('reviewProductName').textContent = productName;
    document.getElementById('reviewModal').style.display = 'flex';
    document.getElementById('reviewComment').value = '';
    
    // Set default 5 stars
    setRating(5);
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function setRating(rating) {
    currentRating = rating;
    const stars = document.querySelectorAll('#starRating span');
    stars.forEach((star, index) => {
        star.textContent = index < rating ? '★' : '☆';
    });
}

function submitReview() {
    const comment = document.getElementById('reviewComment').value;
    const submitButton = document.querySelector('#reviewModal button[onclick="submitReview()"]');
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!token) {
        alert('Security token not found. Please refresh the page.');
        return;
    }

    // Debug log
    console.log('Submitting review:', {
        product_id: currentProductId,
        order_id: currentOrderId,
        rating: currentRating,
        comment: comment
    });
    
    // Disable submit button
    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';
    
    fetch('{{ route("reviews.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: currentProductId,
            order_id: currentOrderId,
            rating: currentRating,
            comment: comment
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to submit review');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            closeReviewModal();
            location.reload(); // Reload to show updated review status
        } else {
            alert(data.message || 'Failed to submit review');
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Review';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred. Please try again.');
        submitButton.disabled = false;
        submitButton.textContent = 'Submit Review';
    });
}

// Close modal when clicking outside
document.getElementById('reviewModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewModal();
    }
});
</script>
@endpush
@endsection
