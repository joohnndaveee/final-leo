@extends('layouts.app')

@section('title', 'Shop - U-KAY HUB')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* Shop Section */
    .shop-section {
        padding: 2rem 2rem 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 20rem);
    }

    /* Unified Sticky Header */
    .shop-header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 20px 0 rgba(31, 38, 135, 0.1);
        border-radius: 1rem;
        padding: 2rem 3rem;
        margin-bottom: 3rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        transition: all 0.3s ease;
    }

    /* Add shadow on scroll */
    .shop-header.scrolled {
        box-shadow: 0 6px 30px 0 rgba(31, 38, 135, 0.2);
    }

    /* Left Side - Category Title */
    .category-title-wrapper {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex: 1;
    }

    .category-title-wrapper i {
        font-size: 2.5rem;
        color: var(--main-color);
    }

    .category-title-wrapper h1 {
        font-size: 2.4rem;
        color: var(--black);
        font-weight: 700;
        margin: 0;
        text-transform: capitalize;
    }

    .category-title-wrapper .category-name {
        color: var(--main-color);
        font-weight: 800;
    }

    /* Right Side - Back Navigation */
    .back-navigation {
        display: flex;
        align-items: center;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.2rem 2.5rem;
        background: rgba(58, 199, 45, 0.1);
        color: var(--black);
        text-decoration: none;
        border-radius: 0.8rem;
        font-size: 1.6rem;
        font-weight: 600;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .back-btn i {
        font-size: 1.8rem;
        transition: transform 0.3s ease;
    }

    .back-btn:hover {
        background: var(--main-color);
        color: var(--white);
        border-color: var(--main-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(58, 199, 45, 0.3);
    }

    .back-btn:hover i {
        transform: translateX(-5px);
    }

    /* Category Filter Pills */
    .category-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 1.2rem;
        margin-bottom: 3rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem 2rem;
        background: rgba(255, 255, 255, 0.9);
        color: var(--black);
        text-decoration: none;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        font-size: 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .category-pill i {
        font-size: 1.6rem;
        color: var(--main-color);
        transition: all 0.3s ease;
    }

    .category-pill:hover {
        background: rgba(58, 199, 45, 0.1);
        border-color: var(--main-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(58, 199, 45, 0.2);
    }

    .category-pill.active {
        background: linear-gradient(135deg, var(--main-color) 0%, #27ae60 100%);
        color: white;
        border-color: var(--main-color);
        box-shadow: 0 4px 15px rgba(58, 199, 45, 0.3);
    }

    .category-pill.active i {
        color: white;
    }

    .filter-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
        align-items: end;
    }

    .filter-bar label {
        font-size: 1.3rem;
        color: var(--light-color);
        display: block;
        margin-bottom: 0.4rem;
    }

    .filter-bar input,
    .filter-bar select {
        width: 100%;
        padding: 0.9rem 1.2rem;
        border-radius: 0.6rem;
        border: 1px solid #e5e7eb;
        font-size: 1.4rem;
    }

    .filter-bar button {
        padding: 1rem 1.2rem;
        background: var(--main-color);
        border: none;
        color: #fff;
        border-radius: 0.6rem;
        font-size: 1.5rem;
        cursor: pointer;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(28rem, 1fr));
        gap: 2.5rem;
        margin-top: 3rem;
    }

    /* Product Card */
    .product-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        border-radius: 1rem;
        padding: 2rem;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        cursor: pointer;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.25);
        border-color: var(--main-color);
    }

    .product-card .product-image {
        width: 100%;
        height: 25rem;
        object-fit: contain;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        background: rgba(245, 245, 245, 0.5);
    }

    .product-card .thumb-strip {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 1rem;
    }

    .product-card .thumb-strip img {
        width: 42px;
        height: 42px;
        border-radius: 0.4rem;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 1.4rem;
    }

    .product-rating .stars {
        color: #ffd700;
    }

    .product-rating .rating-text {
        color: #666;
    }

    .product-card .product-name {
        font-size: 2rem;
        color: var(--black);
        font-weight: 600;
        margin-bottom: 0.8rem;
        display: block;
        min-height: 2.5rem;
    }

    .product-card .product-seller {
        font-size: 1.35rem;
        color: var(--light-color);
        margin-bottom: 0.6rem;
    }

    .product-card .product-details {
        font-size: 1.4rem;
        color: var(--light-color);
        margin-bottom: 1rem;
        line-height: 1.6;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card .product-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .product-card .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.3rem;
        color: var(--light-color);
        background: rgba(58, 199, 45, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }

    .product-card .meta-item i {
        color: var(--main-color);
    }

    .product-card .product-price {
        font-size: 2.4rem;
        color: var(--main-color);
        font-weight: 700;
        margin: 1rem 0 1.5rem 0;
    }

    .product-card .btn-add-cart {
        width: 100%;
        background: var(--main-color);
        color: var(--white);
        padding: 1.2rem 2rem;
        border-radius: 0.5rem;
        font-size: 1.7rem;
        text-align: center;
        text-transform: capitalize;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        margin-top: auto;
    }

    .product-card .btn-add-cart:hover {
        background: var(--black);
        transform: translateY(-2px);
    }

    /* Out of Stock Badge */
    .out-of-stock-badge {
        width: 100%;
        background: #f8d7da;
        color: #721c24;
        padding: 1.2rem 2rem;
        border-radius: 0.5rem;
        font-size: 1.7rem;
        text-align: center;
        font-weight: 600;
        border: 2px solid #f5c6cb;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        margin-top: auto;
    }

    /* Low Stock Warning */
    .low-stock-warning {
        background: #fff3cd;
        color: #856404;
        padding: 0.8rem 1.2rem;
        border-radius: 0.5rem;
        font-size: 1.4rem;
        text-align: center;
        font-weight: 600;
        border: 1px solid #ffeaa7;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Empty State */
    .empty-products {
        text-align: center;
        padding: 8rem 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-products i {
        font-size: 10rem;
        color: var(--light-color);
        margin-bottom: 3rem;
    }

    .empty-products p {
        font-size: 2.5rem;
        color: var(--black);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-products .sub-text {
        font-size: 1.8rem;
        color: var(--light-color);
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(25rem, 1fr));
            gap: 2rem;
        }

        .shop-header {
            padding: 1.5rem 2rem;
            gap: 1.5rem;
        }

        .category-title-wrapper h1 {
            font-size: 1.9rem;
        }

        .category-title-wrapper i {
            font-size: 2rem;
        }

        .back-btn {
            padding: 1rem 1.5rem;
            font-size: 1.4rem;
        }

        .back-btn i {
            font-size: 1.6rem;
        }

        .category-filter {
            padding: 1.5rem;
            gap: 1rem;
            justify-content: center;
        }

        .category-pill {
            padding: 0.8rem 1.5rem;
            font-size: 1.4rem;
        }

        .category-pill i {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 450px) {
        .products-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .product-card .product-image {
            height: 20rem;
        }

        .shop-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
            gap: 1.5rem;
        }

        .category-title-wrapper {
            width: 100%;
        }

        .back-navigation {
            width: 100%;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .category-filter {
            padding: 1rem;
            gap: 0.8rem;
        }

        .category-pill {
            padding: 0.7rem 1.2rem;
            font-size: 1.3rem;
            gap: 0.6rem;
        }

        .category-pill i {
            font-size: 1.3rem;
        }
    }
</style>
@endpush

@section('content')

<section class="shop-section">
    {{-- Unified Sticky Header --}}
    <div class="shop-header" id="shopHeader">
        {{-- Left Side: Category Title --}}
        <div class="category-title-wrapper">
            <i class="fas fa-tags"></i>
            <h1>
                Showing: 
                @if($category)
                    <span class="category-name">{{ $category }}</span>
                @else
                    <span class="category-name">All Products</span>
                @endif
            </h1>
        </div>

        {{-- Right Side: Back Navigation --}}
        <div class="back-navigation">
            <a href="{{ route('home') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Home</span>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('shop') }}" class="filter-bar">
        @if($category)
            <input type="hidden" name="category" value="{{ $category }}">
        @endif
        <div>
            <label for="search">Search</label>
            <input type="text" id="search" name="q" value="{{ $search }}" placeholder="Product name or keyword">
        </div>
        <div>
            <label for="price_min">Min Price</label>
            <input type="number" step="0.01" id="price_min" name="price_min" value="{{ $priceMin }}" placeholder="0">
        </div>
        <div>
            <label for="price_max">Max Price</label>
            <input type="number" step="0.01" id="price_max" name="price_max" value="{{ $priceMax }}" placeholder="1000">
        </div>
        <div>
            <label for="sort">Sort</label>
            <select name="sort" id="sort">
                <option value="newest" @selected($sort === 'newest')>Newest</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
                <option value="rating" @selected($sort === 'rating')>Top Rated</option>
            </select>
        </div>
        <div>
            <button type="submit">Apply</button>
        </div>
    </form>

    {{-- Category Filter Pills --}}
    <div class="category-filter">
        <a href="{{ route('shop') }}" class="category-pill {{ !$category ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            All Products
        </a>
        <a href="{{ route('shop') }}?category=Polo" class="category-pill {{ $category === 'Polo' ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            Polo
        </a>
        <a href="{{ route('shop') }}?category=T-Shirt" class="category-pill {{ $category === 'T-Shirt' ? 'active' : '' }}">
            <i class="fas fa-tshirt"></i>
            T-Shirt
        </a>
        <a href="{{ route('shop') }}?category=Dress" class="category-pill {{ $category === 'Dress' ? 'active' : '' }}">
            <i class="fas fa-user-nurse"></i>
            Dress
        </a>
        <a href="{{ route('shop') }}?category=Pants" class="category-pill {{ $category === 'Pants' ? 'active' : '' }}">
            <i class="fas fa-socks"></i>
            Pants
        </a>
        <a href="{{ route('shop') }}?category=Jacket" class="category-pill {{ $category === 'Jacket' ? 'active' : '' }}">
            <i class="fas fa-vest"></i>
            Jacket
        </a>
        <a href="{{ route('shop') }}?category=Heels" class="category-pill {{ $category === 'Heels' ? 'active' : '' }}">
            <i class="fas fa-shoe-prints"></i>
            Heels
        </a>
        <a href="{{ route('shop') }}?category=Shoes" class="category-pill {{ $category === 'Shoes' ? 'active' : '' }}">
            <i class="fas fa-running"></i>
            Shoes
        </a>
        <a href="{{ route('shop') }}?category=Cap" class="category-pill {{ $category === 'Cap' ? 'active' : '' }}">
            <i class="fas fa-hat-cowboy"></i>
            Cap
        </a>
    </div>

    @if($products->count() > 0)
        {{-- Products Grid --}}
        <div class="products-grid">
            @foreach($products as $product)
                @php
                    $averageRating = $product->reviews()->avg('rating') ?? 0;
                    $totalReviews = $product->reviews()->count();
                @endphp
                <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->id) }}'">
                    {{-- Product Image --}}
                    <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                         alt="{{ $product->name }}" 
                         class="product-image"
                         loading="lazy"
                         onerror="this.src='{{ $siteLogoUrl ?? asset('images/logo.png') }}'">

                    {{-- Thumbnail strip for additional images --}}
                    @if($product->image_02 || $product->image_03)
                        <div class="thumb-strip" onclick="event.stopPropagation();">
                            @if($product->image_01)
                                <img src="{{ asset('uploaded_img/' . $product->image_01) }}" alt="">
                            @endif
                            @if($product->image_02)
                                <img src="{{ asset('uploaded_img/' . $product->image_02) }}" alt="">
                            @endif
                            @if($product->image_03)
                                <img src="{{ asset('uploaded_img/' . $product->image_03) }}" alt="">
                            @endif
                        </div>
                    @endif

                    {{-- Product Name --}}
                    <span class="product-name">{{ $product->name }}</span>

                    {{-- Seller Shop Name + Logo --}}
                    @if($product->seller)
                        @php
                            $sellerLogo = !empty($product->seller->shop_logo)
                                ? asset('uploaded_img/' . $product->seller->shop_logo)
                                : ($siteLogoUrl ?? asset('images/logo.png'));
                        @endphp
                        <div class="product-seller" style="display:flex;align-items:center;gap:0.6rem;">
                            <img
                                src="{{ $sellerLogo }}"
                                alt="{{ $product->seller->shop_name ?? 'Shop' }} logo"
                                style="width:22px;height:22px;border-radius:999px;object-fit:cover;border:1px solid #e5e7eb;background:#fff;"
                                onerror="this.src='{{ $siteLogoUrl ?? asset('images/logo.png') }}'"
                            >
                            <span style="color:#6b7280;">Sold by:</span>
                            <strong>{{ $product->seller->shop_name ?? '—' }}</strong>
                        </div>
                    @endif

                    {{-- Product Rating --}}
                    <div class="product-rating">
                        <span class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($averageRating))
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </span>
                        <span class="rating-text">
                            @if($totalReviews > 0)
                                {{ number_format($averageRating, 1) }} ({{ $totalReviews }})
                            @else
                                No reviews yet
                            @endif
                        </span>
                    </div>

                    {{-- Product Details --}}
                    @if($product->details)
                        <p class="product-details">{{ $product->details }}</p>
                    @endif

                    {{-- Product Meta (Type) --}}
                    @if($product->type)
                        <div class="product-meta">
                            <span class="meta-item">
                                <i class="fas fa-tag"></i>
                                {{ $product->type }}
                            </span>
                        </div>
                    @endif

                    {{-- Product Price --}}
                    <div class="product-price">₱{{ number_format($product->price, 2) }}</div>

                    {{-- Stock Status & Add to Cart Button --}}
                    @php
                        $stock = $product->stock ?? 0;
                    @endphp
                    @if($stock <= 0)
                        <div class="out-of-stock-badge">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </div>
                    @else
                        @if($stock <= 10)
                            <div class="low-stock-warning">
                                <i class="fas fa-exclamation-triangle"></i> Only {{ $stock }} left!
                            </div>
                        @endif
                        <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="pid" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="image" value="{{ $product->image_01 }}">
                            <button type="submit" class="btn-add-cart">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination Links --}}
        @if($products->hasPages())
            <div class="pagination-wrapper" style="margin-top: 4rem; display: flex; justify-content: center;">
                {{ $products->appends([
                    'category' => $category,
                    'q' => $search,
                    'sort' => $sort,
                    'price_min' => $priceMin,
                    'price_max' => $priceMax,
                ])->links() }}
            </div>
        @endif
    @else
        {{-- Empty State --}}
        <div class="empty-products">
            <i class="fas fa-box-open"></i>
            <p>No items found{{ $category ? ' in this category' : '' }} yet.</p>
            <p class="sub-text">Check back later!</p>
        </div>
    @endif
</section>

@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Sticky header scroll effect
    const shopHeader = document.getElementById('shopHeader');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        // Add shadow when scrolled
        if (currentScroll > 20) {
            shopHeader.classList.add('scrolled');
        } else {
            shopHeader.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // Add to cart functionality with AJAX
    function addToCart(productId, productName, button) {
        // Disable button to prevent double clicks
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count in header
                updateCartCount(data.cart_count);

                // Show success notification
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
                // User not logged in, redirect to login
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
                // Handle other errors (e.g., out of stock)
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
            // Re-enable button
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        });
    }

    // Update cart count in header
    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.header .icons a[href*="cart"] span');
        cartCountElements.forEach(element => {
            element.textContent = `(${count})`;
        });
    }

    // Add event listeners to all Add to Cart buttons
    document.querySelectorAll('.product-card form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = this.querySelector('[name="pid"]').value;
            const productName = this.querySelector('[name="name"]').value;
            const button = this.querySelector('button[type="submit"]');
            
            addToCart(productId, productName, button);
        });
    });
</script>
@endpush
