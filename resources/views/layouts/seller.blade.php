<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Center - U-KAY HUB')</title>

    <link rel="icon" type="image/png" href="{{ $siteLogoUrl ?? asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(6, 95, 70, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(16, 185, 129, 0.1);
            box-shadow: 4px 0 24px rgba(16, 185, 129, 0.08);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .shop-logo-container {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            padding: 4px;
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.2);
            transition: transform 0.3s ease;
            border: 3px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .shop-logo-container:hover {
            transform: scale(1.05);
        }

        .shop-logo {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background: #f3f4f6;
        }

        .shop-info {
            text-align: center;
            color: white;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .shop-info {
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        .shop-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            word-wrap: break-word;
        }

        .shop-status {
            font-size: 0.85rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.25);
            display: inline-block;
            text-transform: capitalize;
            backdrop-filter: blur(10px);
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .nav-item {
            margin: 0.5rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            color: #4b5563;
            text-decoration: none;
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: #10b981;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(16, 185, 129, 0.08);
            color: #059669;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
        }

        .nav-link.active {
            background: rgba(16, 185, 129, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #059669;
            font-weight: 600;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.15);
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-icon {
            font-size: 1.3rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-text {
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 1rem;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(16, 185, 129, 0.1);
            background: rgba(249, 250, 251, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .footer-actions {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .sidebar.collapsed .footer-actions {
            align-items: center;
        }

        .footer-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #4b5563;
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.05);
        }

        .footer-btn:hover {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-color: rgba(16, 185, 129, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .sidebar.collapsed .footer-btn {
            padding: 0.8rem;
            justify-content: center;
        }

        .sidebar.collapsed .footer-btn span {
            display: none;
        }

        /* Top Bar */
        .topbar {
            position: fixed;
            left: 280px;
            right: 0;
            top: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 24px rgba(16, 185, 129, 0.08);
            border-bottom: 1px solid rgba(16, 185, 129, 0.1);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            gap: 2rem;
            z-index: 999;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed ~ .topbar {
            left: 80px;
        }

        .sidebar-toggle {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .sidebar-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: rgba(16, 185, 129, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(16, 185, 129, 0.15);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #059669;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
        }

        .notification-btn:hover {
            background: rgba(16, 185, 129, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 999px;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 55px;
            width: 380px;
            max-width: 90vw;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            border: 1px solid rgba(16, 185, 129, 0.15);
            box-shadow: 0 12px 48px rgba(16, 185, 129, 0.15);
            overflow: hidden;
            z-index: 9999;
        }

        .notification-dropdown.open {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
        }

        .notification-link {
            font-size: 0.9rem;
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            display: block;
            padding: 1.2rem 1.5rem;
            text-decoration: none;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s ease;
        }

        .notification-item:hover {
            background: #f9fafb;
        }

        .notification-text {
            font-size: 0.95rem;
            color: #374151;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .notification-empty {
            padding: 2rem 1.5rem;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 70px;
            padding: 2rem;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - 70px);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        .seller-messages {
            margin-bottom: 2rem;
        }

        .message {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(16, 185, 129, 0.1);
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message i {
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.2s ease;
        }

        .message i:hover {
            color: #374151;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .notification-dropdown {
                width: 320px;
            }
        }

        /* Seller UI Refresh Override */
        :root {
            --seller-accent: #059669;
            --seller-accent-2: #10b981;
            --seller-bg: #eef6f2;
            --seller-panel: rgba(255, 255, 255, 0.9);
            --seller-border: rgba(5, 150, 105, 0.16);
            --seller-text: #0f172a;
            --seller-muted: #64748b;
        }

        body {
            background: linear-gradient(155deg, #eef6f2 0%, #eaf4ff 100%);
        }

        body::before {
            background:
                radial-gradient(circle at 10% 10%, rgba(16, 185, 129, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 15%, rgba(14, 165, 233, 0.08) 0%, transparent 45%);
        }

        .sidebar {
            width: 272px;
            background: var(--seller-panel);
            border-right: 1px solid var(--seller-border);
            box-shadow: 10px 0 30px rgba(15, 23, 42, 0.06);
        }

        .sidebar-header {
            background: linear-gradient(160deg, #065f46 0%, #047857 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.14);
            padding: 1.6rem 1.3rem;
        }

        .shop-logo-container {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 24px rgba(4, 120, 87, 0.24);
        }

        .shop-logo {
            border-radius: 14px;
        }

        .shop-name {
            font-size: 1.18rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .shop-status {
            font-size: 0.74rem;
            padding: 0.3rem 0.65rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .sidebar-nav {
            padding: 1.1rem 0;
        }

        .nav-item {
            margin: 0.35rem 0.75rem;
            opacity: 0;
            transform: translateX(-8px);
            animation: sellerNavIn 0.45s ease forwards;
        }

        .nav-item:nth-child(1) { animation-delay: 0.04s; }
        .nav-item:nth-child(2) { animation-delay: 0.08s; }
        .nav-item:nth-child(3) { animation-delay: 0.12s; }
        .nav-item:nth-child(4) { animation-delay: 0.16s; }
        .nav-item:nth-child(5) { animation-delay: 0.2s; }
        .nav-item:nth-child(6) { animation-delay: 0.24s; }
        .nav-item:nth-child(7) { animation-delay: 0.28s; }
        .nav-item:nth-child(8) { animation-delay: 0.32s; }

        @keyframes sellerNavIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .nav-link {
            color: #334155;
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            font-weight: 600;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .nav-link::before {
            width: 5px;
            border-radius: 999px;
            left: -1px;
            background: linear-gradient(180deg, #10b981, #059669);
        }

        .nav-link:hover {
            transform: translateX(3px);
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.18);
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
            color: #065f46;
        }

        .nav-link.active {
            color: #065f46;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.16), rgba(6, 182, 212, 0.1));
            border-color: rgba(16, 185, 129, 0.26);
            box-shadow: 0 8px 18px rgba(5, 150, 105, 0.12);
        }

        .nav-icon {
            font-size: 1.1rem;
            color: #64748b;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .nav-link:hover .nav-icon,
        .nav-link.active .nav-icon {
            color: #047857;
            transform: scale(1.05);
        }

        .sidebar-footer {
            border-top: 1px solid var(--seller-border);
            background: rgba(248, 250, 252, 0.8);
            padding: 1rem;
        }

        .footer-btn {
            background: #fff;
            border-color: rgba(148, 163, 184, 0.22);
            border-radius: 10px;
            color: #334155;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }

        .footer-btn:hover {
            color: #065f46;
            border-color: rgba(16, 185, 129, 0.25);
            background: rgba(16, 185, 129, 0.08);
            transform: translateY(-1px);
        }

        .topbar {
            left: 272px;
            height: 68px;
            background: rgba(255, 255, 255, 0.86);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .sidebar.collapsed ~ .topbar {
            left: 80px;
        }

        .sidebar-toggle {
            background: linear-gradient(135deg, #059669, #0ea5e9);
            border-radius: 11px;
            box-shadow: 0 8px 16px rgba(14, 165, 233, 0.25);
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
        }

        .main-content {
            margin-left: 272px;
            margin-top: 68px;
            padding: 1.75rem;
            overflow-x: hidden;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        .notification-btn {
            background: rgba(255, 255, 255, 0.94);
            border-color: rgba(148, 163, 184, 0.3);
            color: #047857;
        }

        .notification-btn:hover {
            background: rgba(16, 185, 129, 0.12);
            transform: translateY(-1px);
        }

        @media (prefers-reduced-motion: reduce) {
            .nav-item,
            .nav-link,
            .footer-btn,
            .sidebar-toggle,
            .notification-btn {
                animation: none !important;
                transition: none !important;
            }
        }

        /* Seller Performance Mode */
        body::before {
            display: none;
        }

        .sidebar,
        .sidebar-header,
        .footer-btn,
        .topbar,
        .notification-btn,
        .notification-dropdown,
        .message {
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        .sidebar,
        .topbar,
        .notification-dropdown {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06) !important;
        }

        .nav-item {
            animation: none !important;
            opacity: 1;
            transform: none;
        }

        .nav-link,
        .footer-btn,
        .notification-btn,
        .sidebar-toggle {
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.15s ease !important;
        }
    </style>

    @stack('styles')
</head>
<body>
@php
    $seller = Auth::guard('seller')->user();
    $shopName = $seller->shop_name ?? $seller->name ?? 'My Shop';
    $shopLogo = $seller->shop_logo ? asset('storage/' . $seller->shop_logo) : asset('images/logo.png');
    $sellerStatus = $seller->status ?? 'pending';
    $unreadAdminCount = \App\Models\SellerChat::where('seller_id', $seller->id)
        ->where('sender_type', 'admin')
        ->where('is_read', false)
        ->count();
    $latestUnread = \App\Models\SellerChat::where('seller_id', $seller->id)
        ->where('sender_type', 'admin')
        ->where('is_read', false)
        ->latest('id')
        ->take(5)
        ->get();
@endphp

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="shop-logo-container">
            <img src="{{ $shopLogo }}" alt="{{ $shopName }}" class="shop-logo">
        </div>
        <div class="shop-info">
            <div class="shop-name">{{ $shopName }}</div>
            <div class="shop-status">{{ ucfirst($sellerStatus) }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('seller.dashboard') }}" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.products.index') }}" class="nav-link {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <i class="fas fa-box-open nav-icon"></i>
                <span class="nav-text">Products</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.orders.index') }}" class="nav-link {{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag nav-icon"></i>
                <span class="nav-text">Orders</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.analytics.index') }}" class="nav-link {{ request()->routeIs('seller.analytics*') ? 'active' : '' }}">
                <i class="fas fa-chart-line nav-icon"></i>
                <span class="nav-text">Analytics</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.discounts.index') }}" class="nav-link {{ request()->routeIs('seller.discounts*') ? 'active' : '' }}">
                <i class="fas fa-percent nav-icon"></i>
                <span class="nav-text">Discounts</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.vouchers.index') }}" class="nav-link {{ request()->routeIs('seller.vouchers*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt nav-icon"></i>
                <span class="nav-text">Vouchers</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.wallet.index') }}" class="nav-link {{ request()->routeIs('seller.wallet.*') ? 'active' : '' }}">
                <i class="fas fa-wallet nav-icon"></i>
                <span class="nav-text">Wallet</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('seller.chat') }}" class="nav-link {{ request()->routeIs('seller.chat*') ? 'active' : '' }}">
                <i class="fas fa-comments nav-icon"></i>
                <span class="nav-text">Chat with Admin</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="footer-actions">
            <a href="{{ route('seller.settings') }}" class="footer-btn">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="footer-btn" style="width: 100%;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Top Bar -->
<div class="topbar">
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="topbar-right">
        <div style="position: relative;">
            <button type="button" class="notification-btn" id="notificationBtn">
                <i class="fas fa-bell"></i>
                @if($unreadAdminCount > 0)
                    <span class="notification-badge">
                        {{ $unreadAdminCount > 99 ? '99+' : $unreadAdminCount }}
                    </span>
                @endif
            </button>
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <div class="notification-title">Notifications</div>
                    <a href="{{ route('seller.chat') }}" class="notification-link">Open chat</a>
                </div>
                <div class="notification-list">
                    @if($unreadAdminCount > 0)
                        @foreach($latestUnread as $msg)
                            <a href="{{ route('seller.chat') }}" class="notification-item">
                                <div class="notification-text">
                                    {{ \Illuminate\Support\Str::limit((string) $msg->message, 90) }}
                                </div>
                                <div class="notification-time">
                                    {{ $msg->created_at?->format('M d, h:i A') ?? '' }}
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="notification-empty">
                            No new notifications.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<main class="main-content">
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

    @yield('content')
</main>


@stack('scripts')

<script>
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });

    // Restore sidebar state from localStorage
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    // Mobile sidebar toggle
    if (window.innerWidth <= 768) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
        });
    }

    // Notification Dropdown
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('open');
    });

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('open');
        }
    });

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });
</script>
</body>
</html>
