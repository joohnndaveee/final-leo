@extends('layouts.app')

@section('title', 'Home - U-KAY HUB')

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* Featured Products Section */
    .featured-section {
        padding: 4rem 2rem 2rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }

    /* Featured Slider Wrapper */
    .featured-slider-wrapper {
        margin-top: 3rem;
        position: relative;
        width: 100%;
    }

    /* Featured Slider Container */
    .featured-slider {
        position: relative;
        width: 100%;
    }

    .featured-slider .swiper-slide {
        height: auto;
        display: flex;
        width: 100%;
    }

    .featured-slider .swiper-wrapper {
        align-items: stretch;
    }

    /* All Products Slider Section */
    .products-slider-section {
        padding: 2rem 2rem 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }

    .section-header-with-button {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .section-header-with-button .heading {
        margin-bottom: 0;
        flex: 1;
    }

    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1.2rem 2.5rem;
        background: linear-gradient(135deg, var(--main-color) 0%, #27ae60 100%);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-size: 1.6rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(58, 199, 45, 0.3);
    }

    .view-all-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(58, 199, 45, 0.4);
    }

    .view-all-btn i {
        font-size: 1.8rem;
    }

    /* Swiper Container */
    .products-slider {
        margin-top: 2rem;
        padding: 2rem 0;
    }

    .swiper-slide {
        height: auto;
        display: flex;
    }

    /* Product Card for Slider */
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
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.25);
    }

    .product-card .product-image {
        width: 100%;
        height: 25rem;
        object-fit: contain;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        background: rgba(245, 245, 245, 0.5);
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
        margin-bottom: 1rem;
        display: block;
        min-height: 2.5rem;
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
        width: 100%;
        background: #fff3cd;
        color: #856404;
        padding: 0.8rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 1.4rem;
        text-align: center;
        font-weight: 600;
        border: 2px solid #ffeaa7;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Swiper Navigation Buttons */
    .swiper-button-next,
    .swiper-button-prev {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        width: 5rem;
        height: 5rem;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: var(--main-color);
        transform: scale(1.1);
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 2rem;
        font-weight: 900;
        color: var(--black);
    }

    .swiper-button-next:hover::after,
    .swiper-button-prev:hover::after {
        color: var(--white);
    }

    /* Swiper Pagination */
    .swiper-pagination-bullet {
        width: 1rem;
        height: 1rem;
        background: var(--light-color);
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet-active {
        background: var(--main-color);
        opacity: 1;
        width: 2.5rem;
        border-radius: 0.5rem;
    }

    /* Landscape Product Card - Professional Ecommerce Style */
    .featured-product-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border-radius: 1.2rem;
        padding: 3rem;
        display: grid;
        grid-template-columns: 45% 55%;
        gap: 3rem;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        width: 100%;
        overflow: hidden;
    }

    .featured-slider .swiper-slide {
        width: 100%;
    }

    .featured-product-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }

    /* Featured Slider Navigation Buttons - Persistent Fixed Position */
    .featured-nav-next,
    .featured-nav-prev {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        opacity: 0.7;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-nav-prev {
        left: 2rem;
    }

    .featured-nav-next {
        right: 2rem;
    }

    .featured-nav-next:hover,
    .featured-nav-prev:hover {
        background: rgba(255, 255, 255, 1);
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(58, 199, 45, 0.3);
    }

    .featured-nav-next::after,
    .featured-nav-prev::after {
        font-size: 2.2rem;
        font-weight: 900;
        color: var(--main-color);
        font-family: 'swiper-icons';
    }

    .featured-nav-prev::after {
        content: 'prev';
    }

    .featured-nav-next::after {
        content: 'next';
    }

    .featured-nav-next:hover::after,
    .featured-nav-prev:hover::after {
        color: var(--main-color);
    }

    /* Featured Pagination */
    .featured-slider .swiper-pagination {
        bottom: -3rem;
    }

    .featured-slider .swiper-pagination-bullet {
        width: 1.2rem;
        height: 1.2rem;
        background: var(--light-color);
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .featured-slider .swiper-pagination-bullet-active {
        background: var(--main-color);
        opacity: 1;
        width: 3rem;
        border-radius: 0.6rem;
    }

    /* Left Side - Image */
    .featured-image-container {
        position: relative;
        width: 100%;
        height: 45rem;
        border-radius: 0.8rem;
        overflow: hidden;
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .featured-product-card:hover .featured-product-image {
        transform: scale(1.05);
    }

    /* Right Side - Details */
    .featured-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 2rem;
    }

    .featured-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        background: var(--main-color);
        color: var(--white);
        padding: 0.6rem 1.6rem;
        border-radius: 0.6rem;
        font-size: 1.3rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: fit-content;
        box-shadow: 0 2px 4px rgba(58, 199, 45, 0.2);
    }

    .featured-badge i {
        font-size: 1.6rem;
    }

    .featured-product-name {
        font-size: 3.5rem;
        color: var(--black);
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
    }

    .featured-product-description {
        font-size: 1.7rem;
        color: var(--light-color);
        line-height: 1.8;
        margin: 0;
    }

    .featured-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-tag {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        padding: 0.8rem 1.5rem;
        border-radius: 0.6rem;
        font-size: 1.4rem;
        color: #374151;
        font-weight: 500;
    }

    .meta-tag i {
        color: #6b7280;
        font-size: 1.5rem;
    }

    .featured-price {
        font-size: 4rem;
        color: var(--main-color);
        font-weight: 800;
        margin: 1rem 0;
    }


    /* Empty State */
    .empty-products {
        text-align: center;
        padding: 8rem 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 3rem;
    }

    .empty-products i {
        font-size: 10rem;
        color: var(--light-color);
        margin-bottom: 3rem;
    }

    .empty-products p {
        font-size: 2.5rem;
        color: var(--black);
        margin-bottom: 2rem;
        font-weight: 600;
    }

    .empty-products .btn {
        display: inline-block;
        width: auto;
        margin-top: 1rem;
    }

    /* Categories Section */
    .categories-section {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        margin-top: 4rem;
    }

    .categories-section .section-title {
        font-size: 2.5rem;
        color: var(--black);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding-bottom: 1.5rem;
        margin-bottom: 3rem;
        border-bottom: 3px solid var(--main-color);
        display: inline-block;
    }

    /* Categories Grid */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    /* Category Card */
    .category-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        padding: 1.5rem;
        background: var(--white);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .category-card:hover {
        transform: scale(1.05);
        border-color: var(--main-color);
        box-shadow: 0 4px 15px rgba(58, 199, 45, 0.2);
    }

    .category-card:hover .category-name {
        color: var(--main-color);
    }

    /* Category Image Container (Circular) */
    .category-image-wrapper {
        width: 8rem;
        height: 8rem;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(58, 199, 45, 0.1) 0%, rgba(58, 199, 45, 0.05) 100%);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-image-wrapper {
        background: linear-gradient(135deg, rgba(58, 199, 45, 0.2) 0%, rgba(58, 199, 45, 0.1) 100%);
    }

    .category-image {
        width: 6rem;
        height: 6rem;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.1);
    }

    /* Category Name */
    .category-name {
        font-size: 1.4rem;
        color: var(--black);
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        line-height: 1.4;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .featured-product-card {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 2.5rem;
        }

        .featured-image-container {
            height: 40rem;
        }

        .featured-product-name {
            font-size: 3rem;
        }

        .featured-price {
            font-size: 3.5rem;
        }

        /* Adjust navigation buttons for tablets */
        .featured-nav-next,
        .featured-nav-prev {
            width: 5rem;
            height: 5rem;
        }

        .featured-nav-next::after,
        .featured-nav-prev::after {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .featured-section {
            padding: 3rem 1.5rem;
        }

        .featured-product-card {
            padding: 2rem;
        }

        .featured-image-container {
            height: 35rem;
        }

        .featured-product-name {
            font-size: 2.5rem;
        }

        .featured-product-description {
            font-size: 1.5rem;
        }

        .featured-price {
            font-size: 3rem;
        }


        /* Smaller navigation buttons for mobile */
        .featured-nav-next,
        .featured-nav-prev {
            width: 4.5rem;
            height: 4.5rem;
        }

        .featured-nav-prev {
            left: 1rem;
        }

        .featured-nav-next {
            right: 1rem;
        }

        .featured-nav-next::after,
        .featured-nav-prev::after {
            font-size: 1.6rem;
        }

        .categories-grid {
            grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr));
            gap: 1.5rem;
        }

        .category-image-wrapper {
            width: 7rem;
            height: 7rem;
        }

        .category-image {
            width: 5rem;
            height: 5rem;
        }
    }

    @media (max-width: 450px) {
        .featured-image-container {
            height: 30rem;
        }

        .featured-product-name {
            font-size: 2.2rem;
        }

        .featured-price {
            font-size: 2.8rem;
        }


        /* Even smaller navigation buttons for small mobile */
        .featured-nav-next,
        .featured-nav-prev {
            width: 4rem;
            height: 4rem;
            opacity: 0.9;
        }

        .featured-nav-prev {
            left: 0.5rem;
        }

        .featured-nav-next {
            right: 0.5rem;
        }

        .featured-nav-next::after,
        .featured-nav-prev::after {
            font-size: 1.4rem;
        }

        .categories-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .category-card {
            padding: 1rem;
        }

        .category-image-wrapper {
            width: 6rem;
            height: 6rem;
        }

        .category-image {
            width: 4.5rem;
            height: 4.5rem;
        }

        .category-name {
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@section('content')

{{-- Featured Products Section --}}
<section class="featured-section">
    <h1 class="heading">Featured Products</h1>

    @if($featuredProducts->count() > 0)
        {{-- Featured Slider Wrapper - Parent Container --}}
        <div class="featured-slider-wrapper">
            {{-- Featured Slider --}}
            <div class="swiper featured-slider">
                <div class="swiper-wrapper">
                    @foreach($featuredProducts as $product)
                        <div class="swiper-slide">
                            <div class="featured-product-card">
                                {{-- Left Side: Image --}}
                                <div class="featured-image-container">
                                    <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                                         alt="{{ $product->name }}" 
                                         class="featured-product-image"
                                         onerror="this.src='{{ asset('images/logo.png') }}'">
                                </div>

                                {{-- Right Side: Details --}}
                                <div class="featured-details">
                                    {{-- Featured Badge --}}
                                    <span class="featured-badge">
                                        <i class="fas fa-star"></i>
                                        Featured Pick
                                    </span>

                                    {{-- Product Name --}}
                                    <h2 class="featured-product-name">{{ $product->name }}</h2>

                                    {{-- Product Description --}}
                                    @if($product->details)
                                        <p class="featured-product-description">{{ $product->details }}</p>
                                    @endif

                                    {{-- Product Meta Info --}}
                                    <div class="featured-meta">
                                        @if($product->type)
                                            <span class="meta-tag">
                                                <i class="fas fa-tag"></i>
                                                {{ $product->type }}
                                            </span>
                                        @endif
                                    </div>

                                {{-- Price --}}
                                <div class="featured-price">₱{{ number_format($product->price, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="swiper-pagination"></div>
            </div>
            
            {{-- Persistent Navigation Buttons (Outside Swiper, Inside Wrapper) --}}
            <div class="featured-nav-prev"></div>
            <div class="featured-nav-next"></div>
        </div>
    @else
        <div class="empty-products">
            <i class="fas fa-box-open"></i>
            <p>No products added yet!</p>
            <a href="{{ route('admin.products.index') }}" class="btn">Go to Admin Panel</a>
        </div>
    @endif
</section>

{{-- Categories Section --}}
<section class="categories-section">
    <div class="section-title">CATEGORIES</div>

    <div class="categories-grid">
        {{-- Polo --}}
        <a href="{{ route('shop') }}?category=Polo" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-user-tie category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Polo</span>
        </a>

        {{-- T-Shirt --}}
        <a href="{{ route('shop') }}?category=T-Shirt" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-tshirt category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">T-Shirt</span>
        </a>

        {{-- Dress --}}
        <a href="{{ route('shop') }}?category=Dress" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-user-nurse category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Dress</span>
        </a>

        {{-- Pants --}}
        <a href="{{ route('shop') }}?category=Pants" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-socks category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Pants</span>
        </a>

        {{-- Jacket --}}
        <a href="{{ route('shop') }}?category=Jacket" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-vest category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Jacket</span>
        </a>

        {{-- Heels --}}
        <a href="{{ route('shop') }}?category=Heels" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-shoe-prints category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Heels</span>
        </a>

        {{-- Shoes --}}
        <a href="{{ route('shop') }}?category=Shoes" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-running category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Shoes</span>
        </a>

        {{-- Cap --}}
        <a href="{{ route('shop') }}?category=Cap" class="category-card">
            <div class="category-image-wrapper">
                <i class="fas fa-hat-cowboy category-image" style="font-size: 5rem; color: var(--main-color);"></i>
            </div>
            <span class="category-name">Cap</span>
        </a>
    </div>
</section>

{{-- All Products Slider Section --}}
<section class="products-slider-section">
    <h1 class="heading">All Products</h1>

    @if($products->count() > 0)
        {{-- Swiper Slider --}}
        <div class="swiper products-slider">
            <div class="swiper-wrapper">
                @foreach($products as $product)
                    @php
                        $averageRating = $product->reviews()->avg('rating') ?? 0;
                        $totalReviews = $product->reviews()->count();
                    @endphp
                    <div class="swiper-slide">
                        <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->id) }}'">
                            {{-- Product Image --}}
                            <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image"
                                 onerror="this.src='{{ asset('images/logo.png') }}'">

                            {{-- Product Name --}}
                            <span class="product-name">{{ $product->name }}</span>

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
                                <form action="#" method="POST" onclick="event.stopPropagation();">
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
                    </div>
                @endforeach
            </div>
            
            {{-- Navigation Buttons --}}
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            {{-- Pagination --}}
            <div class="swiper-pagination"></div>
        </div>
    @endif
</section>

@endsection

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Initialize Featured Products Slider with Persistent Navigation
    const featuredSwiper = new Swiper('.featured-slider', {
        // One slide at a time (landscape cards) - full width
        slidesPerView: 1,
        spaceBetween: 0,
        width: null,
        
        // Loop mode - infinite cycling
        loop: true,
        
        // Navigation arrows - pointing to persistent buttons outside swiper
        navigation: {
            nextEl: '.featured-nav-next',
            prevEl: '.featured-nav-prev',
        },
        
        // Pagination dots
        pagination: {
            el: '.featured-slider .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        // Enable grab cursor for dragging
        grabCursor: true,
        
        // Enhanced dragging/swiping
        touchRatio: 1,
        longSwipes: true,
        followFinger: true,
        
        // Resistance for smoother dragging
        resistanceRatio: 0.85,
        
        // Smooth animations
        speed: 700,
        effect: 'slide',
        
        // Keyboard control
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
    });

    // Initialize All Products Slider - Draggable
    const productsSwiper = new Swiper('.products-slider', {
        // Slides per view
        slidesPerView: 1,
        spaceBetween: 20,
        
        // Responsive breakpoints
        breakpoints: {
            // Mobile (≥450px)
            450: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // Tablet (≥768px)
            768: {
                slidesPerView: 3,
                spaceBetween: 25
            },
            // Desktop (≥1024px)
            1024: {
                slidesPerView: 4,
                spaceBetween: 30
            }
        },
        
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        
        // Pagination dots
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        // Enable grab cursor for dragging indication
        grabCursor: true,
        
        // Enhanced dragging/swiping
        touchRatio: 1,              // 1:1 touch movement ratio
        touchAngle: 45,             // Touch angle tolerance
        longSwipes: true,           // Enable long swipes
        longSwipesRatio: 0.5,       // Ratio to trigger slide change
        longSwipesMs: 300,          // Minimum duration for swipe
        followFinger: true,         // Slide follows finger/cursor
        
        // Resistance for smoother dragging
        resistanceRatio: 0.85,
        
        // Loop mode for infinite scrolling
        loop: true,
        
        // Smooth animations
        speed: 600,
        effect: 'slide',
        
        // Allow mouse wheel scrolling
        mousewheel: {
            forceToAxis: true,
        },
        
        // Keyboard control
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
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
                    timerProgressBar: true,
                    customClass: {
                        popup: 'colored-toast'
                    }
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

    // Add to cart functionality for all products slider
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

<style>
    /* SweetAlert2 custom styling */
    .colored-toast.swal2-icon-success {
        background-color: var(--main-color) !important;
    }
</style>
@endpush
