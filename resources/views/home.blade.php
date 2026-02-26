@extends('layouts.app')

@section('title', 'Home - U-KAY HUB')

@push('styles')
<style>
/* ============================================================
   GLOBAL TOKENS
   ============================================================ */
:root {
    --brand:   #1d6f42;
    --brand-light: #e8f5e9;
    --ink:     #1d1d1f;
    --ink2:    #424245;
    --ink3:    #6e6e73;
    --divider: #d2d2d7;
    --surface: #f5f5f7;
    --white:   #ffffff;
    --radius-card: 18px;
    --radius-btn:  980px;
    --transition: 0.36s cubic-bezier(0.4,0,0.2,1);
}

/* ============================================================
   1. HERO — FULL-WIDTH IMAGE WITH TEXT OVERLAY
   ============================================================ */
.hero-section {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    height: 100vh;
    min-height: 60rem;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: #111;
}

.hero-bg-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    z-index: 0;
}

.hero-bg-fallback {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d1a0a 0%, #1a3a12 100%);
    z-index: 0;
}

/* Left gradient for text + bottom-right vignette for right tag */
.hero-bg-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(to right,
            rgba(0,0,0,.48) 0%,
            rgba(0,0,0,.28) 38%,
            rgba(0,0,0,.03) 65%,
            rgba(0,0,0,0)   100%),
        radial-gradient(ellipse at 100% 100%,
            rgba(0,0,0,.26) 0%,
            rgba(0,0,0,0)   55%);
    z-index: 1;
}

/* ── text panel (overlaid, left-aligned) ── */
.hero-text-col {
    position: relative;
    z-index: 2;
    padding: 0 5rem 0 8rem;
    max-width: 68rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.hero-img-col { display: none; }

.hero-eyebrow {
    font-size: 1.35rem;
    font-weight: 700;
    color: #6ee89a;
    letter-spacing: .15em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1.8rem;
}

.hero-title {
    font-size: clamp(4.2rem, 6.8vw, 7.4rem);
    font-weight: 850;
    color: #ffffff;
    line-height: 1.06;
    letter-spacing: -.03em;
    margin-bottom: 2rem;
}

.hero-title span { color: var(--brand); }

.hero-desc {
    font-size: 1.75rem;
    color: rgba(255,255,255,.95);
    line-height: 1.65;
    max-width: 48rem;
    font-weight: 400;
    margin-bottom: 3rem;
}

.hero-actions {
    display: flex;
    align-items: center;
    gap: 1.4rem;
    flex-wrap: wrap;
    margin-bottom: 3.2rem;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: .8rem;
    padding: 1.5rem 3.4rem;
    background: var(--brand);
    color: #fff;
    border-radius: var(--radius-btn);
    font-size: 1.7rem;
    font-weight: 600;
    text-decoration: none;
    transition: background var(--transition), transform var(--transition);
}

.btn-hero-primary:hover {
    background: #155a35;
    transform: scale(1.03);
    color: #fff;
}

.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    font-size: 1.7rem;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    border: 1.5px solid rgba(255,255,255,.5);
    border-radius: var(--radius-btn);
    padding: 1.4rem 3rem;
    transition: all var(--transition);
    background: none;
}

.btn-hero-secondary:hover {
    border-color: #fff;
    background: rgba(255,255,255,.12);
    color: #fff;
}

/* ── stat badges ── */
.hero-stats {
    display: flex;
    gap: 1.2rem;
    flex-wrap: wrap;
}

.hero-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 1.2rem;
    padding: 1.1rem 1.8rem;
    background: rgba(0,0,0,.28);
    backdrop-filter: blur(6px);
    min-width: 8.5rem;
    text-align: center;
}

.hero-stat-value {
    font-size: 2.1rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
}

.hero-stat-label {
    font-size: 1.05rem;
    font-weight: 600;
    color: rgba(255,255,255,.85);
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-top: .3rem;
}

/* ── right-side floating tag ── */
.hero-right-tag {
    position: absolute;
    right: 5rem;
    bottom: 5rem;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 1rem;
    text-align: right;
}

.hero-right-tag-line {
    font-size: 1.2rem;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: rgba(255,255,255,.55);
}

.hero-right-tag-main {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.25;
    text-align: right;
}

.hero-right-tag-divider {
    width: 4rem;
    height: 2px;
    background: var(--brand);
    margin-left: auto;
    border-radius: 2px;
}

.hero-right-tag-sub {
    font-size: 1.3rem;
    color: rgba(255,255,255,.65);
    font-weight: 500;
    text-align: right;
    line-height: 1.5;
}

/* ── scroll hint ── */
.hero-scroll-hint {
    position: absolute;
    bottom: 3rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .6rem;
    color: rgba(255,255,255,.4);
    font-size: 1.05rem;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.scroll-arrow {
    width: 1.8rem;
    height: 1.8rem;
    border-right: 1.5px solid rgba(255,255,255,.35);
    border-bottom: 1.5px solid rgba(255,255,255,.35);
    transform: rotate(45deg);
    animation: scrollBounce 2s ease-in-out infinite;
}

@keyframes scrollBounce {
    0%, 100% { transform: rotate(45deg) translateY(0); }
    50% { transform: rotate(45deg) translateY(5px); }
}

/* ============================================================
   2. MARQUEE STRIP
   ============================================================ */
.marquee-strip {
    background: var(--brand);
    padding: 1.2rem 0;
    overflow: hidden;
    white-space: nowrap;
    user-select: none;
}

.marquee-inner {
    display: inline-block;
    animation: marquee 28s linear infinite;
}

.marquee-item {
    display: inline-flex;
    align-items: center;
    gap: .8rem;
    font-size: 1.4rem;
    font-weight: 600;
    color: #fff;
    letter-spacing: .05em;
    text-transform: uppercase;
    padding: 0 3.2rem;
}

.marquee-item i { font-size: 1.2rem; opacity: .7; }

@keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ============================================================
   3. CATEGORIES
   ============================================================ */
/* Apple-style category strip */
.categories-section {
    background: var(--white) !important;
    padding: 5.6rem 4rem !important;
    max-width: 100% !important;
    margin: 0 !important;
    border-bottom: 1px solid #e5e5e5;
    width: 100%;
    box-sizing: border-box;
}

.cat-strip-wrap {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 0;
}

.cat-strip-heading {
    flex-shrink: 0;
    font-size: clamp(3.6rem, 4vw, 5.2rem);
    font-weight: 700;
    color: #1d1d1f;
    letter-spacing: -.03em;
    line-height: 1.05;
    padding-right: 4.8rem;
    margin-right: 0;
    border-right: 1.5px solid #1d1d1f;
    align-self: center;
    white-space: nowrap;
}

.cat-strip-scroll {
    display: flex;
    align-items: flex-end;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    flex: 1;
    min-width: 0;
    padding: 0 0 0 4rem;
    gap: 0;
}

.cat-strip-scroll::-webkit-scrollbar { display: none; }

.category-card {
    display: inline-flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-decoration: none !important;
    flex-shrink: 0;
    width: 13rem;
    padding: 0 1.2rem;
    scroll-snap-align: start;
    background: transparent !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    border: none !important;
    transition: opacity .25s ease;
    gap: 1.4rem;
}

.category-card:hover { opacity: .72; }

.category-image-wrapper {
    width: 9rem;
    height: 9rem;
    display: flex !important;
    align-items: flex-end;
    justify-content: center;
    overflow: visible;
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    flex-shrink: 0;
}

.category-image {
    max-width: 9rem;
    max-height: 9rem;
    width: auto;
    height: auto;
    object-fit: contain;
    display: block;
    transition: transform .3s ease;
}

.category-card:hover .category-image {
    transform: scale(1.06);
}

.category-name {
    font-size: 1.35rem;
    font-weight: 400;
    color: #424245;
    text-align: center;
    line-height: 1.4;
    transition: color .2s;
    padding: 0;
    width: 100%;
}

.category-card:hover .category-name {
    color: #1d1d1f;
}

/* ============================================================
   4. RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .hero-text-col { padding: 0 4rem 0 5rem; }
    .hero-title { font-size: 5.6rem; }
    .cat-strip-heading { font-size: 3.8rem; padding-right: 3.6rem; }
    .category-card { width: 11rem; }
    .category-image, .category-image-wrapper { max-width: 7.6rem; max-height: 7.6rem; width: 7.6rem; height: 7.6rem; }
}

@media (max-width: 768px) {
    .hero-text-col { padding: 0 3rem; max-width: 100%; }
    .hero-title { font-size: 4.4rem; }
    .hero-desc { font-size: 1.6rem; }
    .hero-right-tag { display: none; }
    .section-container { padding: 0 2rem; }
    .section-headline { font-size: 3.2rem; margin-bottom: 3.6rem; }
    .categories-section { padding: 3.6rem 2.4rem !important; }
    .cat-strip-wrap { flex-direction: column; align-items: flex-start; gap: 2.8rem; }
    .cat-strip-heading { border-right: none; border-bottom: 1.5px solid #1d1d1f; padding-right: 0; padding-bottom: 1.6rem; font-size: 3.6rem; }
    .cat-strip-scroll { padding: 0; }
    .category-card { width: 10rem; }
}

@media (max-width: 480px) {
    .hero-text-col { padding: 0 2rem; }
    .hero-title { font-size: 3.6rem; }
    .hero-stats { gap: .8rem; }
    .hero-stat { padding: 1rem 1.4rem; min-width: 7rem; }
    .hero-stat-value { font-size: 1.8rem; }
    .btn-hero-primary, .btn-hero-secondary { font-size: 1.5rem; padding: 1.2rem 2.4rem; }
    .cat-strip-heading { font-size: 3rem; }
    .category-card { width: 8.8rem; padding: 0 .8rem; }
    .category-image, .category-image-wrapper { max-width: 6.4rem; max-height: 6.4rem; width: 6.4rem; height: 6.4rem; }
    .category-name { font-size: 1.2rem; }
}
</style>
@endpush
@section('content')

{{-- ============================================================
     1. HERO — FULL-WIDTH IMAGE, TEXT OVERLAY
     ============================================================ --}}
<section class="hero-section">

    {{-- Background image --}}
    @if(!empty($heroBgPath))
        <img src="{{ asset($heroBgPath) }}" class="hero-bg-img" alt="">
    @else
        <div class="hero-bg-fallback"></div>
    @endif

    {{-- Soft left-side overlay --}}
    <div class="hero-bg-overlay"></div>

    {{-- Overlaid text --}}
    <div class="hero-text-col">
        <p class="hero-eyebrow"><i class="fas fa-leaf"></i> Curated, sustainable finds</p>
        <h1 class="hero-title">Find your next<br><span>pre‑loved</span> piece</h1>
        <p class="hero-desc">
            Browse handpicked items from local sellers &mdash; clean listings, fair prices, and a better way to shop.
        </p>
        <div class="hero-actions">
            <a href="{{ route('shop') }}" class="btn-hero-primary">
                <i class="fas fa-shopping-bag"></i> Shop New Arrivals
            </a>
            <a href="{{ route('shop') }}" class="btn-hero-secondary">
                Explore Categories <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-value">1000+</span>
                <span class="hero-stat-label">Items</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">100%</span>
                <span class="hero-stat-label">Pre-loved</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">50+</span>
                <span class="hero-stat-label">Sellers</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">0%</span>
                <span class="hero-stat-label">Fast Fashion</span>
            </div>
        </div>
    </div>

    <div class="hero-scroll-hint">
        <span>Scroll</span>
        <div class="scroll-arrow"></div>
    </div>

    {{-- Right-side floating text --}}
    <div class="hero-right-tag">
        <span class="hero-right-tag-line">&#10022; Thrift &amp; Treasure</span>
        <div class="hero-right-tag-divider"></div>
        <p class="hero-right-tag-main">Find your look.<br>Own your story.</p>
        <p class="hero-right-tag-sub">Pre-loved. Curated. Yours.</p>
    </div>

</section>

<div class="marquee-strip" aria-hidden="true">
    <div class="marquee-inner">
        @foreach(array_fill(0, 2, null) as $_)
        <span class="marquee-item"><i class="fas fa-bolt"></i> Free Shipping on Orders &#8369;999+</span>
        <span class="marquee-item"><i class="fas fa-shield-halved"></i> Secure Checkout</span>
        <span class="marquee-item"><i class="fas fa-rotate-left"></i> Easy Returns</span>
        <span class="marquee-item"><i class="fas fa-headset"></i> 24/7 Support</span>
        <span class="marquee-item"><i class="fas fa-tags"></i> New Arrivals Every Week</span>
        @endforeach
    </div>
</div>

@endsection
