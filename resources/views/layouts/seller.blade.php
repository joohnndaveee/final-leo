<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Center - U-KAY HUB')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.18) 0, transparent 55%),
                radial-gradient(circle at bottom right, rgba(22, 163, 74, 0.22) 0, transparent 60%),
                linear-gradient(135deg, #e3f9e7 0%, #cdefd1 45%, #b4e4b9 75%, #a0dba7 100%);
            background-attachment: fixed;
        }

        .seller-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(26, 48, 9, 0.98);
            color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .seller-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .seller-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .seller-brand img {
            width: 36px;
            height: 36px;
        }

        .seller-brand-text {
            display: flex;
            flex-direction: column;
        }

        .seller-brand-text span:first-child {
            font-weight: 700;
            letter-spacing: 0.05em;
            font-size: 1.5rem;
        }

        .seller-brand-text span:last-child {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .seller-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 1.4rem;
        }

        .seller-nav a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 999px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .seller-nav a:hover,
        .seller-nav a.active {
            background: rgba(255,255,255,0.12);
        }

        .seller-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .seller-shop {
            display: flex;
            flex-direction: column;
            font-size: 1.3rem;
        }

        .seller-shop-name {
            font-weight: 600;
        }

        .seller-status {
            font-size: 1.1rem;
            padding: 0.2rem 0.8rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.1);
        }

        .seller-main {
            max-width: 1200px;
            margin: 2.5rem auto;
            padding: 0 2rem 3rem;
        }

        .seller-logout-btn {
            border: 1px solid rgba(255,255,255,0.4);
            background: transparent;
            color: #fff;
            border-radius: 999px;
            padding: 0.6rem 1.3rem;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .seller-logout-btn:hover {
            background: rgba(255,255,255,0.12);
        }

        .seller-messages {
            max-width: 1200px;
            margin: 1rem auto 0;
            padding: 0 2rem;
        }

        .message {
            background: rgba(255,255,255,0.98);
            border-radius: 0.8rem;
            padding: 0.9rem 1.3rem;
            margin-bottom: 0.6rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.3rem;
        }

        .message i {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .seller-header-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .seller-main {
                padding: 0 1.5rem 3rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
@php
    $seller = Auth::guard('seller')->user();
    $shopName = $seller->shop_name ?? $seller->name ?? 'My Shop';
    $sellerStatus = $seller->status ?? 'pending';
@endphp

<header class="seller-header">
    <div class="seller-header-inner">
        <div class="seller-brand">
            <img src="{{ asset('images/logo.png') }}" alt="U-KAY HUB Logo">
            <div class="seller-brand-text">
                <span>SELLER CENTER</span>
                <span>U-KAY HUB</span>
            </div>
        </div>

        <nav class="seller-nav">
            <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="{{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Products
            </a>
            <a href="{{ route('seller.orders.index') }}" class="{{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
            <a href="{{ route('seller.chat') }}" class="{{ request()->routeIs('seller.chat*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> Chat with Admin
            </a>
        </nav>

        <div class="seller-user">
            <div class="seller-shop">
                <span class="seller-shop-name">{{ $shopName }}</span>
                <span class="seller-status">
                    {{ ucfirst($sellerStatus) }}
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('seller.settings') }}" class="seller-logout-btn" style="background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; margin: 0;">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="seller-logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="seller-messages">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="message">
                <span>{{ $error }}</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
        @endforeach
    @endif

    @if (session('success'))
        <div class="message">
            <span>{{ session('success') }}</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
    @endif

    @if (session('info'))
        <div class="message">
            <span>{{ session('info') }}</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
    @endif
</div>

<!-- Display subscription warning -->
@include('components.subscription-warning')

<main class="seller-main">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>

