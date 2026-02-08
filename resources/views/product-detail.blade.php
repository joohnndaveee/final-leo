@extends('layouts.app')

@section('title', $product->name . ' - U-KAY HUB')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .product-detail-section {
        padding: 2rem;
        max-width: 1400px;
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

    .product-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 3rem;
    }

    .product-images {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .main-image-container {
        width: 100%;
        height: 500px;
        background: #f8f9fa;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #27ae60;
    }

    .main-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .thumbnail-images {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .thumbnail {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #ddd;
        transition: all 0.3s ease;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #27ae60;
        transform: scale(1.05);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .product-title {
        font-size: 2.8rem;
        color: #333;
        font-weight: 700;
        margin: 0;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.6rem;
    }

    .stars {
        color: #ffd700;
        font-size: 2rem;
    }

    .rating-summary {
        color: #666;
    }

    .product-price {
        font-size: 3.5rem;
        color: #27ae60;
        font-weight: 700;
    }

    .product-stock {
        font-size: 1.6rem;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        display: inline-block;
    }

    .in-stock {
        background: #d4edda;
        color: #155724;
    }

    .low-stock {
        background: #fff3cd;
        color: #856404;
    }

    .out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    .product-details {
        font-size: 1.6rem;
        color: #666;
        line-height: 1.8;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .product-meta {
        display: flex;
        gap: 2rem;
        font-size: 1.5rem;
        color: #666;
    }

    .add-to-cart-btn {
        padding: 1.5rem 3rem;
        background: linear-gradient(135deg, #27ae60 0%, #219150 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
    }

    .add-to-cart-btn:disabled {
        background: #95a5a6;
        cursor: not-allowed;
    }

    /* Reviews Section */
    .reviews-section {
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .reviews-header {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 2rem;
        border-bottom: 3px solid #27ae60;
        padding-bottom: 1rem;
    }

    .reviews-overview {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .average-rating {
        text-align: center;
    }

    .rating-number {
        font-size: 5rem;
        font-weight: 700;
        color: #27ae60;
        margin-bottom: 0.5rem;
    }

    .rating-stars-large {
        font-size: 2.5rem;
        color: #ffd700;
        margin-bottom: 0.5rem;
    }

    .total-reviews {
        font-size: 1.4rem;
        color: #666;
    }

    .rating-distribution {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .rating-bar-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.4rem;
    }

    .star-label {
        color: #ffd700;
        min-width: 60px;
    }

    .progress-bar {
        flex: 1;
        height: 12px;
        background: #e0e0e0;
        border-radius: 6px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        transition: width 0.3s ease;
    }

    .count {
        min-width: 60px;
        text-align: right;
        color: #666;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .review-item {
        padding: 2rem;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .review-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .reviewer-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
    }

    .reviewer-top {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .reviewer-name {
        font-size: 1.6rem;
        font-weight: 600;
        color: #333;
    }

    .review-stars {
        color: #ffd700;
        font-size: 1.6rem;
    }

    .review-quantity {
        font-size: 1.3rem;
        color: #666;
        background: #f0f0f0;
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
    }

    .review-date {
        font-size: 1.3rem;
        color: #999;
        white-space: nowrap;
    }

    .review-comment {
        font-size: 1.5rem;
        color: #666;
        line-height: 1.6;
    }

    .no-reviews {
        text-align: center;
        padding: 3rem;
        color: #999;
        font-size: 1.6rem;
    }

    @media (max-width: 968px) {
        .product-main {
            grid-template-columns: 1fr;
        }

        .reviews-overview {
            grid-template-columns: 1fr;
        }

        .main-image-container {
            height: 400px;
        }

        .review-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .reviewer-top {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="product-detail-section">
    <a href="{{ route('shop') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Shop
    </a>

    <!-- Product Main Info -->
    <div class="product-main">
        <div class="product-images">
            <div class="main-image-container">
                <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                     alt="{{ $product->name }}" 
                     class="main-image" 
                     id="mainImage">
            </div>
            <div class="thumbnail-images">
                <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                     class="thumbnail active" 
                     onclick="changeImage(this)">
                <img src="{{ asset('uploaded_img/' . $product->image_02) }}" 
                     class="thumbnail" 
                     onclick="changeImage(this)">
                <img src="{{ asset('uploaded_img/' . $product->image_03) }}" 
                     class="thumbnail" 
                     onclick="changeImage(this)">
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title">{{ $product->name }}</h1>
            
            <div class="product-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($averageRating))
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <span class="rating-summary">
                    {{ number_format($averageRating, 1) }} / 5.0 ({{ $totalReviews }} {{ $totalReviews == 1 ? 'review' : 'reviews' }})
                </span>
            </div>

            <div class="product-price">₱{{ number_format($product->price, 2) }}</div>

            <div>
                @php
                    $stock = $product->stock ?? 0;
                @endphp
                @if($stock > 0)
                    @if($stock <= 10)
                        <span class="product-stock low-stock">
                            <i class="fas fa-exclamation-triangle"></i> Low Stock ({{ $stock }} left)
                        </span>
                    @else
                        <span class="product-stock in-stock">
                            <i class="fas fa-check-circle"></i> In Stock ({{ $stock }} available)
                        </span>
                    @endif
                @else
                    <span class="product-stock out-of-stock">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </span>
                @endif
            </div>

            <div class="product-details">
                <strong>Product Details:</strong><br>
                {{ $product->details }}
            </div>

            @if($product->type)
            <div class="product-meta">
                <div><strong>Type:</strong> {{ $product->type }}</div>
            </div>
            @endif

            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_name" value="{{ $product->name }}">
                <input type="hidden" name="product_price" value="{{ $product->price }}">
                <input type="hidden" name="product_image" value="{{ $product->image_01 }}">
                <input type="hidden" name="quantity" value="1">
                
                <button type="submit" class="add-to-cart-btn" {{ $stock <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-shopping-cart"></i>
                    {{ $stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2 class="reviews-header">Customer Reviews</h2>

        @if($totalReviews > 0)
            <div class="reviews-overview">
                <div class="average-rating">
                    <div class="rating-number">{{ number_format($averageRating, 1) }}</div>
                    <div class="rating-stars-large">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($averageRating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <div class="total-reviews">{{ $totalReviews }} {{ $totalReviews == 1 ? 'review' : 'reviews' }}</div>
                </div>

                <div class="rating-distribution">
                    @foreach($starDistribution as $star => $data)
                        <div class="rating-bar-row">
                            <span class="star-label">{{ $star }} ★</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $data['percentage'] }}%"></div>
                            </div>
                            <span class="count">{{ $data['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="reviews-list">
                @foreach($reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-top">
                                    <span class="reviewer-name">{{ $review->user->name }}</span>
                                    <span class="review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </span>
                                    @if($review->orderItem)
                                        <span class="review-quantity">
                                            <i class="fas fa-shopping-bag"></i> Purchased: {{ $review->orderItem->quantity }} {{ $review->orderItem->quantity > 1 ? 'items' : 'item' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($review->comment)
                            <div class="review-comment">{{ $review->comment }}</div>
                        @else
                            <div class="review-comment" style="color: #999; font-style: italic;">No written review</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-reviews">
                <i class="fas fa-star" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                <p>No reviews yet. Be the first to review this product!</p>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Function to change the main product image when a thumbnail is clicked
    function changeImage(element) {
        // Update main image source
        document.getElementById('mainImage').src = element.src;
        
        // Update the 'active' class on thumbnails
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.classList.add('active');
    }

    // Intercept the 'Add to Cart' form submission
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const form = this;
        const button = form.querySelector('.add-to-cart-btn');
        const formData = new FormData(form);

        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                // The 'X-CSRF-TOKEN' is crucial for Laravel's security
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count in the header
                updateCartCount(data.cart_count);

                // Show a success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            } else if (data.redirect) {
                // This handles cases where the user is not logged in
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: data.message,
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                // Handle other server-side errors (e.g., out of stock)
                Swal.fire({
                    icon: 'error',
                    title: 'Action Failed',
                    text: data.message || 'Could not add item to cart.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong. Please try again.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        })
        .finally(() => {
            // Re-enable the button and restore its text
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        });
    });

    // Function to update the cart count in the header
    function updateCartCount(count) {
        // This selector might need to be adjusted based on your header's HTML structure
        const cartCountElements = document.querySelectorAll('.header .icons a[href*="cart"] span');
        cartCountElements.forEach(element => {
            element.textContent = `(${count})`;
        });
    }
</script>
@endpush
@endsection
