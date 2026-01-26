@extends('layouts.app')

@section('title', 'Home - U-KAY HUB')

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<style>
    /* Products Section */
    .products-section {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 20rem);
        position: relative;
    }

    /* Swiper Container */
    .products-slider {
        margin-top: 3rem;
        padding: 2rem 0;
    }

    .swiper-slide {
        height: auto;
        display: flex;
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
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.25);
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

    .product-card .product-image {
        width: 100%;
        height: 25rem;
        object-fit: contain;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        background: rgba(245, 245, 245, 0.5);
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

    /* Empty State */
    .empty-products {
        text-align: center;
        padding: 8rem 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: var(--border);
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
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
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
        .products-grid {
            grid-template-columns: 1fr;
        }

        .product-card .product-image {
            height: 20rem;
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

{{-- Products Section --}}
<section class="products-section">
    <h1 class="heading">Featured Products</h1>

    @if($products->count() > 0)
        {{-- Swiper Slider --}}
        <div class="swiper products-slider">
            <div class="swiper-wrapper">
                @foreach($products as $product)
                    <div class="swiper-slide">
                        <div class="product-card">
                            {{-- Product Image --}}
                            <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image"
                                 onerror="this.src='{{ asset('images/logo.png') }}'">

                            {{-- Product Name --}}
                            <span class="product-name">{{ $product->name }}</span>

                            {{-- Product Price --}}
                            <div class="product-price">₱{{ number_format($product->price, 2) }}</div>

                            {{-- Add to Cart Button --}}
                            <form action="#" method="POST">
                                @csrf
                                <input type="hidden" name="pid" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                <input type="hidden" name="image" value="{{ $product->image_01 }}">
                                <button type="submit" class="btn-add-cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
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

@endsection

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    // Initialize Swiper
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
        
        // Enable grab cursor
        grabCursor: true,
        
        // Enable touch/swipe
        touchRatio: 1,
        touchAngle: 45,
        
        // Loop mode (optional)
        loop: true,
        
        // Autoplay (optional)
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        
        // Smooth animations
        speed: 600,
        effect: 'slide',
    });

    // Add to cart functionality (placeholder for now)
    document.querySelectorAll('.product-card form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // For now, show a temporary message
            const productName = this.querySelector('[name="name"]').value;
            alert(`"${productName}" will be added to cart (functionality coming soon!)`);
            
            // Later this will be converted to AJAX to add items to cart
            console.log('Add to cart:', {
                pid: this.querySelector('[name="pid"]').value,
                name: productName,
                price: this.querySelector('[name="price"]').value,
                image: this.querySelector('[name="image"]').value
            });
        });
    });
</script>
@endpush
