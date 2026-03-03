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

        .notif-wrap {
            position: relative;
            display: inline-flex;
        }

        .notif-popover {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: min(92vw, 360px);
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.16);
            display: none;
            z-index: 1200;
            overflow: hidden;
        }

        .notif-popover.open {
            display: block;
        }

        .notif-popover-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid #eef2f7;
            font-size: 1.35rem;
            font-weight: 700;
            color: #111827;
        }

        .notif-seeall {
            font-size: 1.2rem;
            font-weight: 600;
            color: #166534;
            text-decoration: none;
        }

        .notif-seeall:hover {
            text-decoration: underline;
        }

        .notif-popover-list {
            max-height: 320px;
            overflow-y: auto;
        }

        .notif-item {
            display: block;
            width: 100%;
            border: 0;
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
            padding: .9rem 1.2rem;
            text-align: left;
            cursor: pointer;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: .2rem;
        }

        .notif-item-msg {
            font-size: 1.2rem;
            color: #475569;
            line-height: 1.35;
        }

        .notif-item-time {
            margin-top: .35rem;
            font-size: 1.1rem;
            color: #94a3b8;
        }

        .notif-empty {
            padding: 1.2rem;
            font-size: 1.25rem;
            color: #6b7280;
            text-align: center;
        }

        .notif-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1600;
            padding: 1.2rem;
        }

        .notif-modal-overlay.open {
            display: flex;
        }

        .notif-modal {
            width: min(94vw, 520px);
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
            overflow: hidden;
        }

        .notif-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .95rem 1.1rem;
            border-bottom: 1px solid #eef2f7;
            font-size: 1.4rem;
            font-weight: 700;
            color: #111827;
        }

        .notif-modal-close {
            width: 32px;
            height: 32px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fff;
            color: #111827;
            cursor: pointer;
            font-size: 1.6rem;
            line-height: 1;
        }

        .notif-modal-body {
            padding: 1rem 1.1rem 1.2rem;
        }

        .notif-modal-msg {
            font-size: 1.4rem;
            color: #334155;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .notif-modal-time {
            margin-top: .8rem;
            font-size: 1.2rem;
            color: #94a3b8;
        }

        #menu-btn {
            display: none;
            font-size: 2rem;
            cursor: pointer;
            color: #111;
        }

        /* Mobile nav drawer */
        @media (max-width: 768px) {
            .header .flex {
                grid-template-columns: auto 1fr auto;
                padding: 0 1.4rem;
            }

            #menu-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 3.6rem;
                height: 3.6rem;
                border: 1px solid #e5e7eb;
                border-radius: .8rem;
                background: #fff;
                font-size: 1.9rem;
            }

            .logo-center {
                justify-content: center;
            }

            .logo-center .text {
                font-size: 1.9rem;
            }

            .logo-center img {
                width: 30px;
            }

            .nav-left {
                display: none;
            }

            .nav-left.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                border-top: 1px solid #e8e8e8;
                border-bottom: 1px solid #e8e8e8;
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
                z-index: 1000;
            }

            .nav-left .navbar,
            .nav-left .cat-dropdown {
                display: none;
            }

            .nav-left.active .navbar.active {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                gap: 0;
                order: 1;
                width: 100%;
            }

            .nav-left.active .navbar a {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                width: 100%;
                padding: 1.3rem 2.4rem;
                border-bottom: 1px solid #edf0f3;
                font-size: 1.7rem;
                line-height: 1.2;
                text-align: left !important;
            }

            .header .flex .nav-left.active .navbar.active a,
            .header .flex .nav-left.active .navbar.active a.active {
                justify-content: flex-start !important;
                text-align: left !important;
                width: 100% !important;
                margin: 0 !important;
                padding-left: 2.4rem !important;
            }

            .nav-left.active .cat-dropdown {
                display: block;
                width: 100%;
                order: 2;
                position: static;
                background: #fff;
                border-top: 1px solid #edf0f3;
                z-index: 1;
                padding: 0 !important;
            }

            .nav-left.active .cat-btn {
                display: flex;
                align-items: center;
                width: 100%;
                justify-content: space-between;
                padding: 1.3rem 2.4rem;
                font-size: 1.7rem;
                text-align: left !important;
                background: #fff;
                position: relative;
                z-index: 2;
            }

            .nav-left.active .cat-menu {
                position: static;
                min-width: 0;
                width: 100%;
                border: 0;
                border-top: 1px solid #edf0f3;
                box-shadow: none;
                padding: 0;
                background: #f8fafc;
                animation: none;
            }

            .nav-left.active .cat-menu a {
                padding: 1rem 1.9rem;
                border-bottom: 1px solid #edf0f3;
                font-size: 1.45rem;
            }

            .nav-right {
                gap: 1.4rem;
            }
        }

        /* ===== SALE SHOWCASE ===== */
        .sale-showcase-shell {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .sale-showcase {
            position: relative;
            margin: 0;
            max-width: none;
            padding: 0;
            height: auto;
            width: 100%;
            background: #df2a2a;
            color: #fff;
            overflow: hidden;
        }

        .sale-showcase-wrap {
            width: 100%;
            margin: 0;
            padding: 5.2rem 4rem;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 3.2rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .sale-showcase-label {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            background: #f8fafc;
            color: #df2a2a;
            padding: .7rem 1.3rem;
            font-size: 1.2rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .sale-showcase-title {
            margin: 0;
            font-size: clamp(4rem, 8vw, 9rem);
            line-height: .92;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .sale-showcase-copy {
            margin-top: 2.6rem;
            max-width: 34rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 2rem;
            line-height: 1.45;
        }

        .sale-grid {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.6rem;
            z-index: 2;
        }

        .sale-grid::before {
            content: "SALE";
            position: absolute;
            right: -.8rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: clamp(8rem, 18vw, 20rem);
            font-weight: 800;
            letter-spacing: .04em;
            color: rgba(255, 255, 255, 0.2);
            pointer-events: none;
            z-index: 1;
            line-height: .8;
        }

        .sale-card {
            position: relative;
            z-index: 2;
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 2rem 1.8rem;
            min-height: 20rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: transform .2s ease, background .2s ease, border-color .2s ease;
        }

        .sale-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .sale-card img {
            width: 8.8rem;
            height: 7.4rem;
            object-fit: contain;
            margin-bottom: 1.1rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .sale-card-name {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: .65rem;
            text-transform: capitalize;
        }

        .sale-card-prices {
            display: inline-flex;
            align-items: baseline;
            gap: .8rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .sale-price {
            font-size: 2.05rem;
            color: #ffd42a;
            font-weight: 800;
            line-height: 1;
        }

        .sale-original-price {
            font-size: 1.35rem;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: line-through;
        }

        @media (max-width: 1024px) {
            .sale-showcase-wrap {
                grid-template-columns: 1fr;
                gap: 2.4rem;
                padding: 1.8rem 2.4rem;
            }

            .sale-showcase-copy {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .sale-grid {
                grid-template-columns: 1fr;
            }

            .sale-grid::before {
                right: 0;
                top: 0;
                transform: none;
                font-size: clamp(6rem, 26vw, 10rem);
            }

            .sale-card {
                min-height: 16rem;
            }
        }

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
        <div id="menu-btn" class="fas fa-bars"></div>

        {{-- LEFT: hamburger (mobile) + category dropdown + nav links --}}
        <div class="nav-left">
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
            <div class="notif-wrap">
                <a href="#" id="notif-bell" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span id="notif-count" style="display:none;position:absolute;top:-6px;right:-8px;background:#dc2626;color:#fff;font-size:.65rem;font-weight:700;min-width:16px;height:16px;border-radius:999px;text-align:center;line-height:16px;padding:0 3px">0</span>
                </a>
                <div class="notif-popover" id="notif-popover">
                    <div class="notif-popover-head">
                        <span>Notifications</span>
                        <a href="{{ route('notifications.index') }}" class="notif-seeall">See all notifications</a>
                    </div>
                    <div class="notif-popover-list" id="notif-list">
                        <div class="notif-empty">No new notifications.</div>
                    </div>
                </div>
            </div>
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

@if(isset($saleItems) && $saleItems->isNotEmpty())
<div class="sale-showcase-shell">
    <section class="sale-showcase" aria-label="Sale items">
        <div class="sale-showcase-wrap">
            <div>
                <span class="sale-showcase-label"><i class="fas fa-bolt"></i> Flash Sale</span>
                <h2 class="sale-showcase-title">Up To<br>60% Off</h2>
                <p class="sale-showcase-copy">
                    Don't miss out on discounted picks. Grab these sale items before they're gone.
                </p>
            </div>
            <div class="sale-grid">
                @foreach($saleItems as $saleProduct)
                    <a href="{{ route('product.detail', $saleProduct->id) }}" class="sale-card">
                        <div>
                            <img src="{{ asset('uploaded_img/' . $saleProduct->image_01) }}" alt="{{ $saleProduct->name }}">
                            <div class="sale-card-name">{{ \Illuminate\Support\Str::limit($saleProduct->name, 26) }}</div>
                            <div class="sale-card-prices">
                                <span class="sale-price">&#8369;{{ number_format((float) $saleProduct->sale_price, 2) }}</span>
                                <span class="sale-original-price">&#8369;{{ number_format((float) $saleProduct->price, 2) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endif

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
(function initNotifications() {
    const bell = document.getElementById('notif-bell');
    const countEl = document.getElementById('notif-count');
    const popover = document.getElementById('notif-popover');
    const listEl = document.getElementById('notif-list');
    if (!bell || !countEl || !popover || !listEl) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const markReadBase = '{{ url("/notifications") }}';

    function escapeHtml(text) {
        const map = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
        return String(text || '').replace(/[&<>"']/g, (m) => map[m]);
    }

    function renderList(items) {
        if (!items || !items.length) {
            listEl.innerHTML = '<div class="notif-empty">No new notifications.</div>';
            return;
        }
        listEl.innerHTML = items.map((item) => `
            <button type="button" class="notif-item" data-id="${item.id}" data-title="${escapeHtml(item.title)}" data-message="${escapeHtml(item.message)}" data-time="${escapeHtml(item.created_at)}">
                <div class="notif-item-title">${escapeHtml(item.title)}</div>
                <div class="notif-item-msg">${escapeHtml(item.message)}</div>
                <div class="notif-item-time">${escapeHtml(item.created_at)}</div>
            </button>
        `).join('');
    }

    function updateCount(count) {
        const cnt = Number(count || 0);
        if (cnt > 0) {
            countEl.textContent = cnt > 99 ? '99+' : String(cnt);
            countEl.style.display = 'inline-block';
        } else {
            countEl.style.display = 'none';
        }
    }

    function fetchUnread() {
        return fetch('{{ route("notifications.unread") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then((data) => {
            updateCount(data.count || 0);
            renderList(data.items || []);
            return data;
        })
        .catch(() => ({ count: 0, items: [] }));
    }

    function openPopover() {
        popover.classList.add('open');
        fetchUnread();
    }

    function closePopover() {
        popover.classList.remove('open');
    }

    function ensureModal() {
        let overlay = document.getElementById('notif-modal-overlay');
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.id = 'notif-modal-overlay';
        overlay.className = 'notif-modal-overlay';
        overlay.innerHTML = `
            <div class="notif-modal" role="dialog" aria-modal="true" aria-labelledby="notif-modal-title">
                <div class="notif-modal-head">
                    <span id="notif-modal-title">Notification</span>
                    <button type="button" class="notif-modal-close" id="notif-modal-close" aria-label="Close">×</button>
                </div>
                <div class="notif-modal-body">
                    <div class="notif-modal-msg" id="notif-modal-message"></div>
                    <div class="notif-modal-time" id="notif-modal-time"></div>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        const closeBtn = overlay.querySelector('#notif-modal-close');
        closeBtn?.addEventListener('click', () => overlay.classList.remove('open'));
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) overlay.classList.remove('open');
        });
        return overlay;
    }

    function openModal(title, message, time) {
        const overlay = ensureModal();
        const titleEl = overlay.querySelector('#notif-modal-title');
        const msgEl = overlay.querySelector('#notif-modal-message');
        const timeEl = overlay.querySelector('#notif-modal-time');
        if (titleEl) titleEl.textContent = title || 'Notification';
        if (msgEl) msgEl.textContent = message || '';
        if (timeEl) timeEl.textContent = time || '';
        overlay.classList.add('open');
    }

    bell.addEventListener('click', (e) => {
        e.preventDefault();
        if (popover.classList.contains('open')) closePopover();
        else openPopover();
    });

    listEl.addEventListener('click', (e) => {
        const btn = e.target.closest('.notif-item');
        if (!btn) return;

        const id = btn.getAttribute('data-id');
        const title = btn.getAttribute('data-title') || 'Notification';
        const message = btn.getAttribute('data-message') || '';
        const time = btn.getAttribute('data-time') || '';
        openModal(title, message, time);

        if (id) {
            fetch(`${markReadBase}/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).finally(() => fetchUnread());
        }
    });

    document.addEventListener('click', (e) => {
        if (!popover.contains(e.target) && !bell.contains(e.target)) {
            closePopover();
        }
    });

    fetchUnread();
})();
</script>
@endauth

</body>
</html>
