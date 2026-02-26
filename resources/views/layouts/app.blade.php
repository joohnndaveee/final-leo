<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'U-KAY HUB - Online Shop')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $siteLogoUrl ?? asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fahkwang:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- User Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        /* Clean neutral background (no image) + subtle shapes */
        body {
            background: #f2f4f7;
            position: relative;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -1;
        }

        /* soft wash */
        body::before {
            background:
                radial-gradient(1200px 600px at 10% 15%, rgba(58, 199, 45, 0.10), transparent 65%),
                radial-gradient(900px 500px at 92% 18%, rgba(17, 24, 39, 0.06), transparent 60%),
                radial-gradient(1100px 700px at 75% 95%, rgba(58, 199, 45, 0.07), transparent 60%),
                linear-gradient(180deg, rgba(255,255,255,0.40) 0%, rgba(255,255,255,0.00) 55%);
        }

        /* subtle shape accents */
        body::after {
            background:
                radial-gradient(240px 240px at 18% 78%, rgba(17, 24, 39, 0.04), transparent 70%),
                radial-gradient(320px 320px at 88% 70%, rgba(58, 199, 45, 0.08), transparent 72%);
            filter: blur(0.5px);
        }

        /* ── CK-STYLE NAVBAR ── */
        .seasonal-banner {
            background: linear-gradient(90deg, rgba(45, 80, 22, 1) 0%, rgba(26, 48, 9, 1) 100%);
            color: #fff;
            font-size: 1.25rem;
            padding: .75rem 1.5rem;
            border-bottom: 1px solid rgba(255, 215, 0, .25);
        }

        .seasonal-banner .inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .seasonal-banner a {
            color: #ffed4e;
            text-decoration: underline;
            font-weight: 700;
            white-space: nowrap;
        }

        .header {
            background: #ffffff;
            border-bottom: 1px solid #e8e8e8;
            position: sticky;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            box-shadow: none;
        }

        .header::before { display: none; }

        .header .flex {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            padding: 0 3rem;
            height: 66px;
            position: relative;
        }

        /* ── LEFT ── */
        .nav-left {
            display: flex;
            align-items: center;
            gap: 2.4rem;
            align-self: stretch;
            height: 100%;
        }

        /* Category dropdown */
        /* Stretch to full navbar height so the menu anchors to the navbar bottom */
        .cat-dropdown {
            position: relative;
            align-self: stretch;
            display: flex;
            align-items: center;
        }

        .cat-btn {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.4rem;
            font-weight: 600;
            color: #111;
            letter-spacing: .02em;
            padding: 0;
            font-family: inherit;
            white-space: nowrap;
        }

        .cat-btn i {
            font-size: 1rem;
            transition: transform .25s;
        }

        .cat-dropdown.open .cat-btn i { transform: rotate(180deg); }

        .cat-menu {
            display: none;
            position: absolute;
            top: calc(100% + 1px);
            left: 0;
            min-width: 210px;
            background: #fff;
            border: 1px solid #e8e8e8;
            box-shadow: 0 12px 36px rgba(0,0,0,.12);
            padding: .6rem 0;
            z-index: 9999;
            animation: catFadeIn .18s ease;
        }

        @keyframes catFadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .cat-dropdown.open .cat-menu {
            display: block;
        }

        /* Open on hover (desktop) */
        .cat-dropdown:hover .cat-menu {
            display: block;
        }

        .cat-dropdown:hover .cat-btn i {
            transform: rotate(180deg);
        }

        /* Fallback: open on focus (works even if JS fails) */
        .cat-dropdown:focus-within .cat-menu {
            display: block;
        }

        .cat-dropdown:focus-within .cat-btn i {
            transform: rotate(180deg);
        }

        .cat-menu a {
            display: block;
            padding: 1rem 2rem;
            font-size: 1.4rem;
            color: #111;
            text-decoration: none;
            transition: background .15s, color .15s;
            letter-spacing: .01em;
        }

        .cat-menu a:hover { background: #f5f5f5; color: var(--main-color); }

        /* Navbar links */
        .header .navbar {
            display: flex;
            align-items: center;
            gap: 2.2rem;
        }

        .header .navbar a {
            font-size: 1.4rem;
            font-weight: 600;
            color: #111;
            text-decoration: none;
            letter-spacing: .02em;
            transition: color .2s;
            white-space: nowrap;
        }

        .header .navbar a:hover,
        .header .navbar a.active { color: var(--main-color); }

        /* ── CENTER LOGO ── */
        .logo-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-center a {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .logo-center img {
            width: 38px;
            height: auto;
            mix-blend-mode: multiply;
        }

        .logo-center .text {
            font-family: 'Fahkwang', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: #111;
            letter-spacing: -.01em;
        }

        .logo-center .text span { color: var(--main-color); }

        /* ── RIGHT ICONS ── */
        .nav-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 2rem;
        }

        .nav-right > * {
            font-size: 1.9rem;
            color: #111;
            cursor: pointer;
            text-decoration: none;
            position: relative;
            transition: color .2s;
            display: flex;
            align-items: center;
        }

        .nav-right > *:hover { color: var(--main-color); }

        .cart-badge {
            background: var(--main-color);
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            min-width: 17px;
            height: 17px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            position: absolute;
            top: -7px;
            right: -9px;
        }

        #menu-btn { display: none; font-size: 2rem; cursor: pointer; color: #111; }

        /* ===== FOOTER — VERDANT-STYLE ===== */
        .modern-footer {
            background: #f4f4f0;
            color: #1a1a1a;
            margin-top: 6rem;
            border-top: 1px solid #e0e0d8;
        }

        .footer-content {
            padding: 6rem 4rem 4rem;
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 5rem;
        }

        /* Brand wordmark */
        .footer-wordmark {
            font-size: 3.8rem;
            font-weight: 900;
            letter-spacing: -.02em;
            color: #1a1a1a;
            margin-bottom: 1.6rem;
            line-height: 1;
        }

        .footer-wordmark span {
            color: var(--main-color);
        }

        .footer-description {
            font-size: 1.45rem;
            line-height: 1.75;
            color: #5a5a5a;
            margin-bottom: 2.8rem;
            max-width: 28rem;
        }

        /* Social icons — outlined squares like VERDANT */
        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: 7px;
            border: 1.5px solid #c4c4bc;
            color: #2a2a2a;
            font-size: 1.55rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
        }

        .social-icon:hover {
            background: var(--main-color);
            border-color: var(--main-color);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Column headings */
        .footer-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .footer-title::after { display: none; }

        /* Column links */
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 1.3rem;
        }

        .footer-links a {
            color: #4a4a4a;
            font-size: 1.5rem;
            text-decoration: none;
            display: block;
            transition: color .2s ease;
        }

        .footer-links a i { display: none; }

        .footer-links a.accent { color: var(--main-color); }

        .footer-links a:hover {
            color: #1a1a1a;
            transform: none;
        }

        /* Contact list */
        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-contact li {
            display: flex;
            gap: 1.2rem;
            margin-bottom: 1.6rem;
            color: #4a4a4a;
            font-size: 1.45rem;
            line-height: 1.6;
        }

        .footer-contact li i {
            color: var(--main-color);
            font-size: 1.6rem;
            min-width: 18px;
            margin-top: .2rem;
        }

        /* Bottom bar */
        .footer-bottom {
            border-top: 1px solid #deded6;
            padding: 2.6rem 4rem;
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 1.35rem;
            color: #6a6a6a;
        }

        .footer-bottom span {
            color: #1a1a1a;
            font-weight: 600;
        }

        .footer-bottom-links {
            display: flex;
            gap: 2.2rem;
            align-items: center;
        }

        .footer-bottom-links a {
            font-size: 1.35rem;
            color: #6a6a6a;
            text-decoration: none;
            transition: color .2s;
        }

        .footer-bottom-links a:hover { color: #1a1a1a; }

        .footer-tagline {
            font-size: 1.35rem;
            color: #6a6a6a;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .footer-tagline .leaf {
            color: var(--main-color);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .footer-grid { grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 3rem; }
        }

        @media (max-width: 768px) {
            .footer-content { padding: 4.4rem 2.4rem 3rem; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 3.2rem; }
            .footer-bottom { flex-direction: column; align-items: flex-start; padding: 2.4rem; }
            .footer-bottom-links { flex-wrap: wrap; gap: 1.6rem; }
        }

        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
            .footer-content { padding: 3.6rem 2rem 2.4rem; }
            .footer-wordmark { font-size: 3.2rem; }
        }

        /* Message styling */
        .message {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>

    @stack('styles')
</head>
<body>

@if(($seasonalBannerEnabled ?? true) && isset($seasonalDiscount) && $seasonalDiscount)
    @php
        $computedMessage = strtoupper($seasonalDiscount->name) . ' ' . (
            $seasonalDiscount->type === 'percentage'
                ? rtrim(rtrim(number_format((float) $seasonalDiscount->value, 2), '0'), '.') . '%'
                : '₱' . number_format((float) $seasonalDiscount->value, 2)
        );
        $bannerMessage = trim((string) ($seasonalBannerMessage ?? '')) !== '' ? (string) $seasonalBannerMessage : $computedMessage;
    @endphp
    <div class="seasonal-banner" style="background: {{ $seasonalBannerBgColor ?? '#1a3009' }}; color: {{ $seasonalBannerTextColor ?? '#ffffff' }};">
        <div class="inner" style="justify-content:center">
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;justify-content:center;text-align:center;">
                <strong>SALE</strong>
                <span>{{ $bannerMessage }}</span>
                @if($seasonalDiscount->end_date)
                    <span style="opacity:.9"> (ends {{ \Carbon\Carbon::parse($seasonalDiscount->end_date)->format('M d, Y') }})</span>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- SweetAlert2 messages --}}

{{-- Header --}}
<header class="header">
    <section class="flex">

        {{-- LEFT: hamburger (mobile) + category dropdown + nav links --}}
        <div class="nav-left">
            <div id="menu-btn" class="fas fa-bars"></div>

        <div class="cat-dropdown" id="cat-dropdown">
                <button class="cat-btn" type="button">
                    Shop By Category <i class="fas fa-chevron-down"></i>
                </button>
                <div class="cat-menu" id="cat-menu">
                    @forelse($navCategories as $cat)
                        <a href="{{ route('shop') }}?category={{ $cat->slug }}">{{ $cat->name }}</a>
                    @empty
                        <a href="{{ route('shop') }}">All Products</a>
                    @endforelse
                </div>
            </div>

            <nav class="navbar">
                <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">New Arrivals</a>
            </nav>
        </div>

        {{-- CENTER: Logo --}}
        <div class="logo-center">
            <a href="{{ route('home') }}">
                <img src="{{ $siteLogoUrl ?? asset('images/logo.png') }}" alt="U-KAY HUB Logo">
                <span class="text">U-KAY<span>HUB</span></span>
            </a>
        </div>

        {{-- RIGHT: Notifications, Cart, User --}}
        <div class="nav-right">
            @auth
            <a href="{{ route('notifications.index') }}" id="notif-bell" title="Notifications">
                <i class="fas fa-bell"></i>
                <span id="notif-count" style="display:none;position:absolute;top:-6px;right:-8px;background:#dc2626;color:#fff;font-size:.65rem;font-weight:700;min-width:16px;height:16px;border-radius:999px;text-align:center;line-height:16px;padding:0 3px">0</span>
            </a>
            @endauth
            <a href="{{ route('cart') }}" id="cart-link" title="Cart">
                <i class="fas fa-shopping-bag"></i>
                @if($cartCount > 0)
                <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        {{-- User Profile Dropdown --}}
        <div class="profile">
            <div class="profile-card">
                @php
                    $webUser = Auth::guard('web')->user();
                    $sellerUser = Auth::guard('seller')->user();
                    $displayName = $webUser ? $webUser->name : ($sellerUser ? $sellerUser->name : 'User');
                    $initial = strtoupper(substr($displayName, 0, 1));
                @endphp

                @if($webUser)
                    <div class="profile-header">
                        <div class="profile-avatar">{{ $initial }}</div>
                        <div class="profile-meta">
                            <div class="profile-name">{{ $displayName }}</div>
                            <div class="profile-email">{{ $webUser->email }}</div>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="profile-btn primary">
                            <i class="fas fa-user-edit"></i> Update Profile
                        </a>
                        <a href="{{ route('orders') }}" class="profile-btn">
                            <i class="fas fa-receipt"></i> Orders
                        </a>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="profile-btn danger" onclick="return confirm('Logout from the website?');">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @else
                    <div class="profile-guest">
                        <div class="profile-guest-title">Welcome back</div>
                        <div class="profile-guest-text">Sign in to manage your account.</div>
                        <div class="profile-actions">
                            <a href="{{ route('login') }}" class="profile-btn primary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="profile-btn">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </section>
</header>

{{-- Main Content --}}
@yield('content')

{{-- Footer — VERDANT style --}}
<footer class="modern-footer">
    <div class="footer-content">
        <div class="footer-grid">

            {{-- Brand Column --}}
            <div class="footer-section brand-section">
                <div class="footer-wordmark">U-KAY<span>HUB</span></div>
                <p class="footer-description">
                    Modern thrift essentials curated for everyone.<br>Sustainable. Stylish. Built to last.
                </p>
                <div class="social-links">
                    <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon" title="Twitter / X"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" class="social-icon" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            {{-- SHOP Column --}}
            <div class="footer-section">
                <h3 class="footer-title">Shop</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('shop') }}" class="accent">New Arrivals</a></li>
                    <li><a href="{{ route('shop') }}" class="accent">Best Sellers</a></li>
                    <li><a href="{{ route('shop') }}">Sale Items</a></li>
                    <li><a href="{{ route('shop') }}">Clothing</a></li>
                    <li><a href="{{ route('shop') }}">Footwear</a></li>
                    <li><a href="{{ route('shop') }}">Accessories</a></li>
                </ul>
            </div>

            {{-- HELP Column --}}
            <div class="footer-section">
                <h3 class="footer-title">Help</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('orders') }}">Track My Order</a></li>
                    <li><a href="{{ route('contact') }}">Returns &amp; Exchanges</a></li>
                    <li><a href="{{ route('contact') }}">Shipping Info</a></li>
                    <li><a href="{{ route('contact') }}">Size Guide</a></li>
                    <li><a href="{{ route('contact') }}">FAQs</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            {{-- COMPANY Column --}}
            <div class="footer-section">
                <h3 class="footer-title">Company</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('seller.register') }}">Become a Seller</a></li>
                    <li><a href="{{ route('contact') }}">Careers</a></li>
                    <li><a href="{{ route('contact') }}">Press</a></li>
                    <li><a href="{{ route('contact') }}">Affiliates</a></li>
                    <li><a href="{{ route('contact') }}">Gift Cards</a></li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} <span>U-KAY HUB</span>. All rights reserved.</p>
        <div class="footer-bottom-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Cookie Settings</a>
        </div>
        <div class="footer-tagline">
            Made with <span class="leaf"><i class="fas fa-leaf"></i></span> for a better tomorrow
        </div>
    </div>
</footer>

{{-- Floating Chat Widget (Only for logged-in users) --}}
@if(Auth::check())
    @include('components.chat-widget')
@endif

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/script.js') }}"></script>

<script>
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Please check the form',
            html: {!! json_encode('<ul style="text-align:left;margin:0;padding-left:1.2rem;">' . implode('', array_map(fn($e) => '<li>' . e($e) . '</li>', $errors->all())) . '</ul>') !!},
            confirmButtonText: 'OK'
        });
    @endif

    @php
        $successMessage = session()->pull('success');
        $infoMessage = session()->pull('info');
    @endphp

    @if ($successMessage)
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json($successMessage),
            timer: 2200,
            showConfirmButton: false
        });
    @endif

    @if ($infoMessage)
        Swal.fire({
            icon: 'info',
            title: 'Notice',
            text: @json($infoMessage),
            timer: 2200,
            showConfirmButton: false
        });
    @endif
</script>

@include('partials.cart-drawer')

<style>
    :root { --drawer-top: 0px; }

    .cart-drawer-overlay {
        position: fixed;
        left: 0;
        right: 0;
        top: var(--drawer-top);
        bottom: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 998;
    }

    .cart-drawer {
        position: fixed;
        top: var(--drawer-top);
        right: 0;
        height: calc(100% - var(--drawer-top));
        width: min(420px, 92vw);
        background: #fff;
        z-index: 999;
        transform: translateX(100%);
        transition: transform .22s ease;
        box-shadow: -20px 0 60px rgba(0, 0, 0, 0.18);
        display: flex;
        flex-direction: column;
    }

    .cart-drawer.open { transform: translateX(0); }

    .cart-drawer__content {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .cart-drawer__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.6rem 1.6rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .cart-drawer__title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #111827;
    }

    .cart-drawer__close {
        border: 1px solid #111827;
        background: transparent;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .cart-drawer__loading {
        padding: 2rem 1.6rem;
        color: #6b7280;
        font-size: 1.5rem;
    }

    .cart-drawer__items {
        padding: 1.2rem 1.6rem;
        overflow: auto;
        flex: 1;
    }

    .cart-drawer__item {
        display: grid;
        grid-template-columns: 54px 1fr 34px;
        align-items: center;
        gap: 1.2rem;
        padding: 1.2rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .cart-drawer__img {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .cart-drawer__name {
        font-size: 1.5rem;
        color: #111827;
        font-weight: 500;
        line-height: 1.2;
        margin-bottom: .3rem;
    }

    .cart-drawer__line {
        font-size: 1.35rem;
        color: #6b7280;
    }

    .cart-drawer__remove {
        border: none;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        font-size: 1.8rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .cart-drawer__remove:hover { color: #111827; }

    .cart-drawer__footer {
        border-top: 1px solid #e5e7eb;
        padding: 1.4rem 1.6rem 1.6rem;
        background: #fff;
    }

    .cart-drawer__subtotal {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 1.5rem;
        color: #111827;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.2rem;
    }

    .cart-drawer__actions {
        display: grid;
        gap: 1rem;
    }

    .cart-drawer__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem 1.4rem;
        text-decoration: none;
        font-size: 1.4rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .cart-drawer__btn--outline {
        border: 1px solid #111827;
        color: #111827;
        background: #fff;
    }

    .cart-drawer__btn--outline:hover {
        background: #111827;
        color: #fff;
    }

    .cart-drawer__empty {
        padding: 3rem 1.6rem;
        text-align: center;
        color: #111827;
    }

    .cart-drawer__empty-icon {
        font-size: 4rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }

    .cart-drawer__empty-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: .4rem;
    }

    .cart-drawer__empty-text { color: #6b7280; font-size: 1.4rem; }
</style>

<script>
    const profile = document.querySelector('.header .flex .profile');
    const catBtn = document.querySelector('#cat-dropdown .cat-btn');
    if (catBtn) catBtn.addEventListener('click', toggleCatMenu);

    // Category dropdown — global function so onclick attribute works
    function toggleCatMenu(e) {
        var cd = document.getElementById('cat-dropdown');
        if (e) e.stopPropagation();
        if (!cd) return;
        cd.classList.toggle('open');
        if (profile) profile.classList.remove('active');
    }

    // Close dropdown on outside click (but don't instantly close when clicking the button/menu)
    document.addEventListener('click', function(e) {
        var cd = document.getElementById('cat-dropdown');
        if (cd && !cd.contains(e.target)) cd.classList.remove('open');
        if (profile) profile.classList.remove('active');
    });

    // Auto-hide messages after 5 seconds
    document.querySelectorAll('.message').forEach(msg => {
        setTimeout(() => { msg.style.display = 'none'; }, 5000);
    });
</script>

<script>
(function initCartDrawer() {
    const cartLink = document.getElementById('cart-link');
    const drawer = document.getElementById('cartDrawer');
    const overlay = document.getElementById('cartDrawerOverlay');
    const content = document.getElementById('cartDrawerContent');
    if (!cartLink || !drawer || !overlay || !content) return;

    function updateDrawerTop() {
        const header = document.querySelector('header.header');
        const top = header ? Math.max(0, Math.round(header.getBoundingClientRect().bottom)) : 0;
        document.documentElement.style.setProperty('--drawer-top', `${top}px`);
    }

    function isCartPage() {
        return window.location.pathname.replace(/\/+$/, '') === '/cart';
    }

    function setBadge(count) {
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

    function openDrawer() {
        updateDrawerTop();
        overlay.hidden = false;
        drawer.classList.add('open');
        drawer.setAttribute('aria-hidden', 'false');
        document.documentElement.style.overflow = 'hidden';
        loadDrawer();
    }

    function closeDrawer() {
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        overlay.hidden = true;
        document.documentElement.style.overflow = '';
    }

    function bindDrawerEvents() {
        content.querySelectorAll('[data-cart-drawer-close]').forEach(btn => {
            btn.addEventListener('click', closeDrawer);
        });
        content.querySelectorAll('[data-cart-remove]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = btn.getAttribute('data-cart-remove');
                if (!id) return;
                try {
                    const res = await fetch(`/cart/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        setBadge(data.cart_count || 0);
                        loadDrawer();
                    }
                } catch (_) {}
            });
        });
    }

    async function loadDrawer() {
        content.innerHTML = '<div class="cart-drawer__header"><div class="cart-drawer__title">Shopping Cart</div><button type="button" class="cart-drawer__close" data-cart-drawer-close aria-label="Close cart"><i class="fas fa-times"></i></button></div><div class="cart-drawer__loading">Loading…</div>';
        bindDrawerEvents();

        try {
            const res = await fetch('{{ route("cart.drawer") }}', { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!res.ok && data && data.redirect) {
                window.location.href = data.redirect;
                return;
            }
            if (data && data.success) {
                content.innerHTML = data.html || '';
                setBadge(data.cart_count || 0);
                bindDrawerEvents();
            }
        } catch (_) {}
    }

    cartLink.addEventListener('click', function (e) {
        if (isCartPage()) {
            e.preventDefault();
            window.location.reload();
            return;
        }
        e.preventDefault();
        openDrawer();
    });

    updateDrawerTop();
    window.addEventListener('resize', updateDrawerTop);
    window.addEventListener('scroll', updateDrawerTop, { passive: true });

    overlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDrawer(); });

    // Allow other pages to trigger a refresh after AJAX add-to-cart
    window.refreshCartDrawer = function () {
        if (!drawer.classList.contains('open')) return;
        loadDrawer();
    };
})();
</script>

@stack('scripts')

@auth
<script>
(function loadNotifCount() {
    fetch('{{ route("notifications.unread") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        var cnt = data.count || 0;
        var el = document.getElementById('notif-count');
        if (el && cnt > 0) {
            el.textContent = cnt > 99 ? '99+' : cnt;
            el.style.display = 'inline-block';
        }
    })
    .catch(() => {});
})();
</script>
@endauth

</body>
</html>
