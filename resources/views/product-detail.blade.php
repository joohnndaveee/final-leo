@extends('layouts.app')

@section('title', $product->name . ' - U-KAY HUB')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
/* ─── Base ──────────────────────────────────────────── */
.pd-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.6rem 2rem 4rem;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
    color: #333;
}

/* ─── Breadcrumb ────────────────────────────────────── */
.pd-breadcrumb {
    font-size: 1.25rem;
    color: #757575;
    margin-bottom: 1.4rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: .3rem;
}
.pd-breadcrumb a {
    color: #2563eb;
    text-decoration: none;
    transition: color .15s;
}
.pd-breadcrumb a:hover { color: #ee4d2d; }
.pd-breadcrumb span { color: #bbb; margin: 0 .1rem; }

/* ─── Main Product Card ─────────────────────────────── */
.pd-main {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 2.4rem;
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 4px;
    padding: 2.4rem;
    margin-bottom: 1.2rem;
}

/* ─── Image Gallery ─────────────────────────────────── */
.pd-gallery {
    display: flex;
    flex-direction: column;
    gap: .9rem;
}
.pd-gallery-main {
    width: 100%;
    aspect-ratio: 1/1;
    background: #f5f5f5;
    border: 1px solid #e8e8e8;
    border-radius: 4px;
    overflow: hidden;
    cursor: zoom-in;
}
.pd-gallery-main img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform .3s ease;
}
.pd-gallery-main:hover img { transform: scale(1.06); }
.pd-thumbs {
    display: flex;
    gap: .6rem;
}
.pd-thumb {
    width: 64px;
    height: 64px;
    border: 2px solid transparent;
    border-radius: 3px;
    overflow: hidden;
    cursor: pointer;
    background: #f5f5f5;
    transition: border-color .18s;
    flex-shrink: 0;
}
.pd-thumb.active,
.pd-thumb:hover { border-color: #ee4d2d; }
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* ─── Product Info ──────────────────────────────────── */
.pd-info { display: flex; flex-direction: column; }

.pd-name {
    font-size: 2rem;
    font-weight: 400;
    color: #333;
    line-height: 1.35;
    margin: 0 0 1rem;
}

/* Rating row */
.pd-meta-row {
    display: flex;
    align-items: center;
    gap: 1.4rem;
    font-size: 1.3rem;
    padding-bottom: 1.2rem;
    border-bottom: 1px solid #f0f0f0;
    flex-wrap: wrap;
}
.pd-rating-val {
    color: #16a34a;
    font-weight: 600;
    border-bottom: 1px solid #16a34a;
}
.pd-stars { color: #f59e0b; letter-spacing: .05em; font-size: 1.35rem; }
.pd-sep { color: #e0e0e0; }
.pd-reviews-count { color: #757575; }
.pd-sold { color: #757575; }

/* Price strip */
.pd-price-strip {
    background: #f0fdf4;
    padding: 1.4rem 1.6rem;
    margin: 1.2rem 0;
    border-radius: 2px;
}
.pd-price-main {
    font-size: 3rem;
    color: #16a34a;
    font-weight: 500;
    display: flex;
    align-items: baseline;
    gap: 1.2rem;
    flex-wrap: wrap;
}
.pd-price-old {
    font-size: 1.7rem;
    color: #aaa;
    text-decoration: line-through;
    font-weight: 400;
}
.pd-sale-badge {
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
    background: #16a34a;
    padding: .3rem .7rem;
    border-radius: 2px;
    line-height: 1;
}

/* Info rows (Shipping, Guarantee, etc.) */
.pd-row {
    display: grid;
    grid-template-columns: 130px 1fr;
    align-items: start;
    gap: .8rem;
    padding: .9rem 0;
    border-bottom: 1px solid #f5f5f5;
    font-size: 1.3rem;
}
.pd-row-label { color: #757575; padding-top: .1rem; }
.pd-row-val { color: #333; display: flex; align-items: flex-start; gap: .5rem; flex-wrap: wrap; }
.pd-row-val i { color: #16a34a; margin-top: .15rem; flex-shrink: 0; }
.pd-guarantee-icon { color: #16a34a; }

/* Variant chips */
.pd-chips {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
}
.pd-chip {
    border: 1px solid #e0e0e0;
    border-radius: 2px;
    padding: .45rem .9rem;
    font-size: 1.25rem;
    color: #333;
    cursor: pointer;
    background: #fff;
    transition: border-color .15s, color .15s;
    white-space: nowrap;
}
.pd-chip:hover,
.pd-chip.selected {
    border-color: #16a34a;
    color: #16a34a;
}

/* Quantity stepper */
.pd-qty {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.pd-qty-ctrl {
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    border-radius: 2px;
    overflow: hidden;
}
.pd-qty-btn {
    width: 36px;
    height: 36px;
    background: #fff;
    border: none;
    cursor: pointer;
    font-size: 1.6rem;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .15s;
    line-height: 1;
    flex-shrink: 0;
}
.pd-qty-btn:hover:not(:disabled) { background: #f5f5f5; }
.pd-qty-btn:disabled { color: #ccc; cursor: not-allowed; }
.pd-qty-input {
    width: 50px;
    height: 36px;
    border: none;
    border-left: 1px solid #e0e0e0;
    border-right: 1px solid #e0e0e0;
    text-align: center;
    font-size: 1.4rem;
    color: #333;
    outline: none;
    background: #fff;
}
/* hide number input arrows */
.pd-qty-input::-webkit-inner-spin-button,
.pd-qty-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
.pd-qty-input[type=number] { -moz-appearance: textfield; }
.pd-stock-info { font-size: 1.25rem; color: #757575; }
.pd-stock-low  { color: #ee4d2d; }

/* Action buttons */
.pd-actions {
    display: flex;
    gap: 1.2rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}
.pd-btn-cart {
    flex: 1;
    min-width: 180px;
    padding: 1.1rem 1.6rem;
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #16a34a;
    border-radius: 2px;
    font-size: 1.5rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .7rem;
    transition: background .18s;
    font-family: inherit;
}
.pd-btn-cart:hover:not(:disabled) { background: #dcfce7; }
.pd-btn-buy {
    flex: 1;
    min-width: 180px;
    padding: 1.1rem 1.6rem;
    background: #16a34a;
    color: #fff;
    border: 1px solid #16a34a;
    border-radius: 2px;
    font-size: 1.5rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .7rem;
    transition: background .18s;
    font-family: inherit;
}
.pd-btn-buy:hover:not(:disabled) { background: #15803d; }
.pd-btn-cart:disabled,
.pd-btn-buy:disabled {
    background: #e0e0e0;
    border-color: #e0e0e0;
    color: #aaa;
    cursor: not-allowed;
}

/* ─── Seller Card ────────────────────────────────────── */
.pd-seller-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 4px;
    padding: 2rem 2.4rem;
    margin-bottom: 1.2rem;
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 2rem;
    align-items: center;
}
.pd-seller-left {
    display: flex;
    align-items: center;
    gap: 1.4rem;
    padding-right: 2rem;
    border-right: 1px solid #f0f0f0;
}
.pd-seller-logo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e8e8e8;
    background: #f5f5f5;
}
.pd-seller-name {
    font-size: 1.55rem;
    font-weight: 600;
    color: #333;
    margin: 0 0 .2rem;
}
.pd-seller-active {
    font-size: 1.2rem;
    color: #757575;
}
.pd-seller-btns {
    display: flex;
    gap: .7rem;
    margin-top: .6rem;
    flex-wrap: wrap;
}
.pd-seller-btn {
    padding: .5rem 1.1rem;
    font-size: 1.25rem;
    border-radius: 2px;
    cursor: pointer;
    font-family: inherit;
    transition: all .18s;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.pd-seller-btn-chat {
    background: #16a34a;
    color: #fff;
    border: 1px solid #16a34a;
}
.pd-seller-btn-chat:hover { background: #15803d; }
.pd-seller-btn-shop {
    background: #fff;
    color: #555;
    border: 1px solid #ccc;
}
.pd-seller-btn-shop:hover { border-color: #999; }
.pd-seller-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.2rem 2rem;
    font-size: 1.28rem;
}
.pd-seller-stat-label { color: #757575; }
.pd-seller-stat-val { color: #16a34a; font-weight: 500; }

/* ─── Content Sections ───────────────────────────────── */
.pd-section {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 4px;
    margin-bottom: 1.2rem;
    overflow: hidden;
}
.pd-section-head {
    background: #fafafa;
    border-bottom: 1px solid #f0f0f0;
    padding: 1.4rem 2.4rem;
    font-size: 1.55rem;
    font-weight: 500;
    color: #333;
    letter-spacing: .01em;
}
.pd-section-body {
    padding: 2rem 2.4rem;
}

/* Specs table */
.pd-specs {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .1rem;
}
.pd-spec-row {
    display: grid;
    grid-template-columns: 160px 1fr;
    font-size: 1.35rem;
    padding: .85rem .6rem;
    border-bottom: 1px solid #f5f5f5;
}
.pd-spec-key { color: #757575; }
.pd-spec-val { color: #333; }

/* Description */
.pd-desc {
    font-size: 1.38rem;
    color: #444;
    line-height: 1.8;
    white-space: pre-line;
}

/* ─── Ratings Section ────────────────────────────────── */
.pd-ratings-top {
    display: flex;
    align-items: center;
    gap: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 1.8rem;
    flex-wrap: wrap;
}
.pd-rating-big-num {
    font-size: 4.5rem;
    font-weight: 500;
    color: #16a34a;
    line-height: 1;
}
.pd-rating-big-stars { color: #f59e0b; font-size: 2rem; margin: .4rem 0; }
.pd-rating-big-total { font-size: 1.3rem; color: #757575; }
.pd-rating-bars {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
    gap: .65rem;
}
.pd-bar-row {
    display: flex;
    align-items: center;
    gap: .9rem;
    font-size: 1.25rem;
}
.pd-bar-lbl { color: #757575; min-width: 50px; white-space: nowrap; }
.pd-bar-track {
    flex: 1;
    height: 8px;
    background: #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}
.pd-bar-fill { height: 100%; background: #22c55e; border-radius: 4px; transition: width .4s; }
.pd-bar-cnt { color: #757575; min-width: 28px; text-align: right; }

/* Filter tabs */
.pd-filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-bottom: 2rem;
}
.pd-filter-tab {
    border: 1px solid #e0e0e0;
    border-radius: 2px;
    padding: .5rem 1.1rem;
    font-size: 1.28rem;
    color: #555;
    cursor: pointer;
    background: #fff;
    transition: all .18s;
    font-family: inherit;
}
.pd-filter-tab:hover,
.pd-filter-tab.active {
    border-color: #16a34a;
    color: #16a34a;
}

/* Review items */
.pd-review-list { display: flex; flex-direction: column; gap: 0; }
.pd-review-item {
    padding: 2rem 0;
    border-bottom: 1px solid #f5f5f5;
}
.pd-review-item:last-child { border-bottom: 0; }
.pd-review-top {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: .7rem;
}
.pd-reviewer-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #16a34a;
    color: #fff;
    font-size: 1.55rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.pd-reviewer-name { font-size: 1.35rem; font-weight: 500; color: #333; }
.pd-review-stars { color: #f59e0b; font-size: 1.4rem; }
.pd-review-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: .2rem;
    flex-wrap: wrap;
}
.pd-review-date { font-size: 1.2rem; color: #bbb; }
.pd-review-variation { font-size: 1.2rem; color: #999; }
.pd-review-comment { font-size: 1.38rem; color: #444; line-height: 1.65; margin-left: 3rem; }
.pd-no-reviews {
    text-align: center;
    padding: 4rem 1rem;
    color: #999;
    font-size: 1.45rem;
}
.pd-no-reviews i { font-size: 3.5rem; color: #e0e0e0; display: block; margin-bottom: .9rem; }

/* ─── Responsive ─────────────────────────────────────── */
@media (max-width: 900px) {
    .pd-main { grid-template-columns: 1fr; padding: 1.6rem; }
    .pd-gallery-main { aspect-ratio: 4/3; }
    .pd-seller-card { grid-template-columns: 1fr; }
    .pd-seller-left { border-right: none; border-bottom: 1px solid #f0f0f0; padding-right: 0; padding-bottom: 1.2rem; }
    .pd-specs { grid-template-columns: 1fr; }
    .pd-seller-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .pd-wrap { padding: 1rem 1rem 3rem; }
    .pd-name { font-size: 1.7rem; }
    .pd-price-main { font-size: 2.4rem; }
    .pd-actions { flex-direction: column; }
    .pd-seller-stats { grid-template-columns: 1fr 1fr; }
    .pd-ratings-top { gap: 1.5rem; }
    .pd-section-head, .pd-section-body { padding-left: 1.4rem; padding-right: 1.4rem; }
}
</style>
@endpush

@section('content')
@php
    $salePrice    = $product->sale_price ?? null;
    $isSale       = $salePrice !== null && (float)$salePrice > 0 && (float)$salePrice < (float)$product->price;
    $displayPrice = $isSale ? (float)$salePrice : (float)$product->price;
    $stock        = (int)($product->stock ?? 0);
    $pieces       = (int)($product->pieces ?? 1);

    $colors = $product->color ? array_filter(array_map('trim', explode(',', $product->color))) : [];
    $sizes  = $product->size  ? array_filter(array_map('trim', explode(',', $product->size)))  : [];

    $thumbs = array_filter([
        $product->image_01 ?? null,
        $product->image_02 ?? null,
        $product->image_03 ?? null,
    ]);

    $sellerLogo = ($product->seller && !empty($product->seller->shop_logo))
        ? asset('uploaded_img/' . $product->seller->shop_logo)
        : ($siteLogoUrl ?? asset('images/logo.png'));

    $discountPct = 0;
    if ($isSale && (float)$product->price > 0) {
        $discountPct = round((1 - (float)$salePrice / (float)$product->price) * 100);
    }
@endphp

<div class="pd-wrap">

    {{-- Breadcrumb --}}
    <nav class="pd-breadcrumb">
        <a href="{{ route('shop') }}">Shop</a>
        @if($product->category)
            <span>›</span>
            <a href="{{ route('shop') }}?category={{ $product->category->id }}">{{ $product->category->name }}</a>
        @endif
        <span>›</span>
        <span style="color:#333">{{ Str::limit($product->name, 60) }}</span>
    </nav>

    {{-- ── Main product panel ─────────────────────────── --}}
    <div class="pd-main">

        {{-- Gallery --}}
        <div class="pd-gallery">
            <div class="pd-gallery-main">
                <img id="pdMainImg"
                     src="{{ asset('uploaded_img/' . ($thumbs[0] ?? 'placeholder.png')) }}"
                     alt="{{ $product->name }}">
            </div>
            @if(count($thumbs) > 1)
            <div class="pd-thumbs">
                @foreach($thumbs as $i => $img)
                <div class="pd-thumb {{ $i === 0 ? 'active' : '' }}"
                     onclick="pdSwitchThumb(this, '{{ asset('uploaded_img/' . $img) }}')">
                    <img src="{{ asset('uploaded_img/' . $img) }}" alt="">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="pd-info">
            <h1 class="pd-name">{{ $product->name }}</h1>

            {{-- Rating row --}}
            <div class="pd-meta-row">
                <span class="pd-rating-val">{{ number_format($averageRating, 1) }}</span>
                <span class="pd-stars">
                    @for($i = 1; $i <= 5; $i++)
                        {!! $i <= round($averageRating) ? '★' : '☆' !!}
                    @endfor
                </span>
                <span class="pd-sep">|</span>
                <span class="pd-reviews-count">{{ number_format($totalReviews) }} Ratings</span>
                @if($stock > 0)
                    <span class="pd-sep">|</span>
                    <span class="pd-sold">In Stock</span>
                @endif
            </div>

            {{-- Price strip --}}
            <div class="pd-price-strip">
                <div class="pd-price-main">
                    <span>₱{{ number_format($displayPrice, 2) }}</span>
                    @if($isSale)
                        <span class="pd-price-old">₱{{ number_format((float)$product->price, 2) }}</span>
                        <span class="pd-sale-badge">-{{ $discountPct }}%</span>
                    @endif
                </div>
            </div>

            {{-- Shipping row --}}
            <div class="pd-row">
                <span class="pd-row-label">Shipping</span>
                <span class="pd-row-val">
                    <i class="fas fa-truck"></i>
                    <span>Standard Shipping · Cash on Delivery available</span>
                </span>
            </div>

            {{-- Shopping Guarantee --}}
            <div class="pd-row">
                <span class="pd-row-label">Shopping Guarantee</span>
                <span class="pd-row-val">
                    <i class="fas fa-shield-alt pd-guarantee-icon"></i>
                    <span>Lowest Price Guaranteed &nbsp;·&nbsp; Free &amp; Easy Returns &nbsp;·&nbsp; Merchandise Protection</span>
                </span>
            </div>

            {{-- Colors --}}
            @if(count($colors) > 0)
            <div class="pd-row">
                <span class="pd-row-label">Color</span>
                <span class="pd-row-val">
                    <div class="pd-chips">
                        @foreach($colors as $color)
                            <button type="button" class="pd-chip" onclick="pdSelectChip(this)">{{ $color }}</button>
                        @endforeach
                    </div>
                </span>
            </div>
            @endif

            {{-- Sizes --}}
            @if(count($sizes) > 0)
            <div class="pd-row">
                <span class="pd-row-label">Size</span>
                <span class="pd-row-val">
                    <div class="pd-chips">
                        @foreach($sizes as $size)
                            <button type="button" class="pd-chip" onclick="pdSelectChip(this)">{{ $size }}</button>
                        @endforeach
                    </div>
                </span>
            </div>
            @endif

            {{-- Type --}}
            @if($product->type)
            <div class="pd-row">
                <span class="pd-row-label">Type</span>
                <span class="pd-row-val">{{ $product->type }}</span>
            </div>
            @endif

            {{-- Bundle --}}
            @if($pieces > 1)
            <div class="pd-row">
                <span class="pd-row-label">Bundle</span>
                <span class="pd-row-val">{{ $pieces }} pcs per set</span>
            </div>
            @endif

            {{-- Quantity --}}
            <div class="pd-row">
                <span class="pd-row-label">Quantity</span>
                <span class="pd-row-val" style="align-items:center;gap:.9rem;">
                    <div class="pd-qty-ctrl">
                        <button type="button" class="pd-qty-btn" id="pdQtyMinus" onclick="pdChangeQty(-1)" {{ $stock <= 0 ? 'disabled' : '' }}>−</button>
                        <input type="number" class="pd-qty-input" id="pdQtyInput" value="1" min="1" max="{{ $stock }}" readonly>
                        <button type="button" class="pd-qty-btn" id="pdQtyPlus" onclick="pdChangeQty(1)" {{ $stock <= 0 ? 'disabled' : '' }}>+</button>
                    </div>
                    @if($stock > 0)
                        @if($stock <= 10)
                            <span class="pd-stock-info pd-stock-low">Only {{ $stock }} left!</span>
                        @else
                            <span class="pd-stock-info">{{ number_format($stock) }} pieces available</span>
                        @endif
                    @else
                        <span class="pd-stock-info pd-stock-low">Out of Stock</span>
                    @endif
                </span>
            </div>

            {{-- Action buttons --}}
            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id"    value="{{ $product->id }}">
                <input type="hidden" name="product_name"  value="{{ $product->name }}">
                <input type="hidden" name="product_price" value="{{ $displayPrice }}">
                <input type="hidden" name="product_image" value="{{ $product->image_01 }}">
                <input type="hidden" name="quantity"      id="pdQtyHidden" value="1">

                <div class="pd-actions">
                    <button type="submit" class="pd-btn-cart" id="pdAddCartBtn" {{ $stock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i>
                        {{ $stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                    <button type="button" class="pd-btn-buy" id="pdBuyNowBtn" {{ $stock <= 0 ? 'disabled' : '' }}
                            onclick="pdBuyNow()">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Seller Card ──────────────────────────────────── --}}
    @if($product->seller)
    <div class="pd-seller-card">
        <div class="pd-seller-left">
            <img src="{{ $sellerLogo }}" alt="{{ $product->seller->shop_name }} logo"
                 class="pd-seller-logo"
                 onerror="this.src='{{ $siteLogoUrl ?? asset('images/logo.png') }}'">
            <div>
                <p class="pd-seller-name">{{ $product->seller->shop_name ?? 'Shop' }}</p>
                <p class="pd-seller-active">Active Recently</p>
                <div class="pd-seller-btns">
                    @auth
                        <a href="{{ route('user.seller.chat.show', $product->seller->id) }}"
                           class="pd-seller-btn pd-seller-btn-chat" style="text-decoration:none;">
                            <i class="fas fa-comment-dots"></i> Chat Now
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="pd-seller-btn pd-seller-btn-chat" style="text-decoration:none;">
                            <i class="fas fa-comment-dots"></i> Chat Now
                        </a>
                    @endauth
                    <a href="{{ route('seller.shop', $product->seller->id) }}"
                       class="pd-seller-btn pd-seller-btn-shop" style="text-decoration:none;">
                        <i class="fas fa-store"></i> View Shop
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-seller-stats">
            <div>
                <div class="pd-seller-stat-label">Ratings</div>
                <div class="pd-seller-stat-val">{{ number_format($product->seller->products()->withCount('reviews')->get()->sum('reviews_count')) }}</div>
            </div>
            <div>
                <div class="pd-seller-stat-label">Products</div>
                <div class="pd-seller-stat-val">{{ number_format($product->seller->products()->count()) }}</div>
            </div>
            <div>
                <div class="pd-seller-stat-label">Response Rate</div>
                <div class="pd-seller-stat-val" style="color:#16a34a;">High</div>
            </div>
            <div>
                <div class="pd-seller-stat-label">Response Time</div>
                <div class="pd-seller-stat-val" style="color:#16a34a;">Within hours</div>
            </div>
            <div>
                <div class="pd-seller-stat-label">Joined</div>
                <div class="pd-seller-stat-val" style="color:#333;">{{ $product->seller->created_at ? $product->seller->created_at->diffForHumans() : '—' }}</div>
            </div>
            <div>
                <div class="pd-seller-stat-label">Followers</div>
                <div class="pd-seller-stat-val" style="color:#333;">—</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Product Specifications ────────────────────────── --}}
    <div class="pd-section">
        <div class="pd-section-head">Product Specifications</div>
        <div class="pd-section-body">
            <div class="pd-specs">
                @if($product->category)
                <div class="pd-spec-row" style="grid-column:1/-1">
                    <span class="pd-spec-key">Category</span>
                    <span class="pd-spec-val" style="color:#2563eb;">
                        U-KAY HUB
                        @if($product->category)
                            › <a href="{{ route('shop') }}?category={{ $product->category->id }}"
                                 style="color:#2563eb;text-decoration:none;">{{ $product->category->name }}</a>
                        @endif
                    </span>
                </div>
                @endif
                <div class="pd-spec-row">
                    <span class="pd-spec-key">Stock</span>
                    <span class="pd-spec-val">{{ $stock > 0 ? 'IN STOCK' : 'OUT OF STOCK' }}</span>
                </div>
                @if($product->type)
                <div class="pd-spec-row">
                    <span class="pd-spec-key">Type</span>
                    <span class="pd-spec-val">{{ $product->type }}</span>
                </div>
                @endif
                @if($product->color)
                <div class="pd-spec-row">
                    <span class="pd-spec-key">Color</span>
                    <span class="pd-spec-val">{{ $product->color }}</span>
                </div>
                @endif
                @if($product->size)
                <div class="pd-spec-row">
                    <span class="pd-spec-key">Size</span>
                    <span class="pd-spec-val">{{ $product->size }}</span>
                </div>
                @endif
                @if($pieces > 1)
                <div class="pd-spec-row">
                    <span class="pd-spec-key">Bundle</span>
                    <span class="pd-spec-val">{{ $pieces }} pcs</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Product Description ──────────────────────────── --}}
    <div class="pd-section">
        <div class="pd-section-head">Product Description</div>
        <div class="pd-section-body">
            <div class="pd-desc">{{ $product->details }}</div>
        </div>
    </div>

    {{-- ── Product Ratings ──────────────────────────────── --}}
    <div class="pd-section">
        <div class="pd-section-head">Product Ratings</div>
        <div class="pd-section-body">
            @if($totalReviews > 0)
            <div class="pd-ratings-top">
                <div style="text-align:center;">
                    <div class="pd-rating-big-num">{{ number_format($averageRating, 1) }}</div>
                    <div class="pd-rating-big-stars">
                        @for($i = 1; $i <= 5; $i++)
                            {!! $i <= round($averageRating) ? '★' : '☆' !!}
                        @endfor
                    </div>
                    <div class="pd-rating-big-total">out of 5</div>
                </div>
                <div class="pd-rating-bars">
                    @foreach($starDistribution as $star => $data)
                    <div class="pd-bar-row">
                        <span class="pd-bar-lbl">{{ $star }} Star</span>
                        <div class="pd-bar-track">
                            <div class="pd-bar-fill" style="width:{{ $data['percentage'] }}%"></div>
                        </div>
                        <span class="pd-bar-cnt">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Filter tabs --}}
            <div class="pd-filter-tabs">
                <button type="button" class="pd-filter-tab active" onclick="pdFilterReviews('all', this)">
                    All ({{ $totalReviews }})
                </button>
                @foreach([5,4,3,2,1] as $s)
                    @if(($starDistribution[$s]['count'] ?? 0) > 0)
                    <button type="button" class="pd-filter-tab" onclick="pdFilterReviews({{ $s }}, this)">
                        {{ $s }} Star ({{ $starDistribution[$s]['count'] }})
                    </button>
                    @endif
                @endforeach
            </div>

            {{-- Review list --}}
            <div class="pd-review-list" id="pdReviewList">
                @foreach($reviews as $review)
                <div class="pd-review-item" data-rating="{{ $review->rating }}">
                    <div class="pd-review-top">
                        <div class="pd-reviewer-avatar">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <div class="pd-reviewer-name">{{ $review->user->name ?? 'Anonymous' }}</div>
                            <div class="pd-review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    {!! $i <= $review->rating ? '★' : '☆' !!}
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="pd-review-meta" style="margin-left:3rem;">
                        <span class="pd-review-date">{{ $review->created_at->format('Y-m-d H:i') }}</span>
                        @if($review->orderItem)
                            <span class="pd-review-variation">Qty: {{ $review->orderItem->quantity }}</span>
                        @endif
                    </div>
                    <div class="pd-review-comment">
                        {{ $review->comment ?? 'No written review.' }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="pd-no-reviews">
                <i class="fas fa-star"></i>
                <p>No reviews yet. Be the first to review this product!</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {

    /* ── Thumbnail switcher ── */
    window.pdSwitchThumb = function (el, src) {
        document.getElementById('pdMainImg').src = src;
        document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    };

    /* ── Chip selector ── */
    window.pdSelectChip = function (el) {
        const group = el.closest('.pd-chips');
        group.querySelectorAll('.pd-chip').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
    };

    /* ── Quantity stepper ── */
    const maxStock = {{ $stock }};
    window.pdChangeQty = function (delta) {
        const input  = document.getElementById('pdQtyInput');
        const hidden = document.getElementById('pdQtyHidden');
        let val = parseInt(input.value, 10) + delta;
        val = Math.max(1, Math.min(maxStock, val));
        input.value  = val;
        hidden.value = val;
        document.getElementById('pdQtyMinus').disabled = val <= 1;
        document.getElementById('pdQtyPlus').disabled  = val >= maxStock;
    };

    /* ── Review filter tabs ── */
    window.pdFilterReviews = function (rating, btn) {
        document.querySelectorAll('.pd-filter-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.pd-review-item').forEach(item => {
            item.style.display = (rating === 'all' || parseInt(item.dataset.rating) === rating) ? '' : 'none';
        });
    };

    /* ── Add to Cart (AJAX) ── */
    const form = document.getElementById('add-to-cart-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn  = document.getElementById('pdAddCartBtn');
            const data = new FormData(form);
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding…';

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: data
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    if (typeof updateCartCount === 'function') updateCartCount(res.cart_count);
                    if (window.refreshCartDrawer) window.refreshCartDrawer();
                    Swal.fire({ icon:'success', title:'Added to Cart!', text: res.message,
                        toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true });
                } else if (res.redirect) {
                    Swal.fire({ icon:'warning', title:'Login Required', text: res.message,
                        showCancelButton:true, confirmButtonText:'Login', cancelButtonText:'Cancel' })
                    .then(r => { if (r.isConfirmed) window.location.href = res.redirect; });
                } else {
                    Swal.fire({ icon:'error', title:'Failed', text: res.message || 'Could not add item.',
                        toast:true, position:'top-end', showConfirmButton:false, timer:3000 });
                }
            })
            .catch(() => Swal.fire({ icon:'error', title:'Oops…', text:'Something went wrong.',
                toast:true, position:'top-end', showConfirmButton:false, timer:3000 }))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
            });
        });
    }

    /* ── Buy Now ── */
    window.pdBuyNow = function () {
        const hidden = document.getElementById('pdQtyHidden');
        const qty = hidden ? hidden.value : 1;
        document.getElementById('add-to-cart-form').querySelector('[name="quantity"]').value = qty;
        document.getElementById('add-to-cart-form').dispatchEvent(new Event('submit'));
    };

})();
</script>
@endpush
