@extends('layouts.app')

@section('title', 'Shop - U-KAY HUB')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* Shop Section */
    .shop-section {
        padding: 1.2rem 1.4rem 3rem 1.4rem;
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

    /* ===== Sidebar layout + minimal product tiles ===== */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .8rem;
        text-decoration: none;
        color: #111827;
        font-size: 1.45rem;
        font-weight: 600;
        margin: .4rem 0 1.6rem 0;
        padding: .8rem 1.1rem;
        border-radius: 1rem;
        background: rgba(255,255,255,.9);
        border: 1px solid rgba(255,255,255,.35);
        box-shadow: 0 8px 20px rgba(0,0,0,.06);
    }

    .back-link:hover {
        color: var(--main-color);
    }

    .shop-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 2.2rem;
        align-items: start;
    }

    @media (max-width: 992px) {
        .shop-layout {
            grid-template-columns: 1fr;
        }
    }

    .shop-sidebar {
        display: grid;
        gap: 1.4rem;
    }

    /* Sidebar should be squared (no rounded corners) */
    .shop-sidebar .sidebar-card {
        border-radius: 0;
    }

    /* Main content white background */
    .shop-main {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid #ececec;
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        padding: 2rem 2rem 2.4rem;
    }

    @media (max-width: 576px) {
        .shop-main { padding: 1.4rem; }
    }

    .sidebar-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 1.2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 1.5rem 1.35rem;
    }

    @media (min-width: 993px) {
        .shop-sidebar .sidebar-card {
            position: sticky;
            top: 92px;
        }
    }

    .sidebar-title {
        font-size: 2rem;
        font-weight: 700;
        color: #111;
        margin: 0 0 1.1rem 0;
    }

    .category-list {
        display: grid;
        gap: .2rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.2rem;
        padding: .9rem .4rem;
        text-decoration: none;
        color: #374151;
        border-radius: .8rem;
        transition: background .15s ease, color .15s ease;
        font-size: 1.45rem;
        font-weight: 500;
    }

    .category-link:hover {
        background: transparent;
        color: #111;
    }

    .category-link.active {
        color: var(--main-color);
        background: transparent;
        font-weight: 600;
    }

    .category-count {
        color: #6b7280;
        font-weight: 600;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .price-filter {
        display: grid;
        gap: 1.4rem;
    }

    .price-filter label {
        display: block;
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: .45rem;
        font-weight: 600;
    }

    .range-wrap {
        padding: .6rem 0 .2rem;
    }

    .range-slider {
        position: relative;
        height: 26px;
    }

    .range-track {
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        background: #d1d5db;
        border-radius: 999px;
    }

    .range-fill {
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        background: #a3a3a3;
        border-radius: 999px;
    }

    .range-slider input[type="range"] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 26px;
        margin: 0;
        background: transparent;
        -webkit-appearance: none;
        appearance: none;
    }

    .range-slider input[type="range"]::-webkit-slider-runnable-track {
        height: 4px;
        background: transparent;
    }

    .range-slider input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 14px;
        height: 14px;
        border-radius: 999px;
        background: #111;
        border: none;
        margin-top: -5px;
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .range-slider input[type="range"]::-moz-range-track {
        height: 4px;
        background: transparent;
    }

    .range-slider input[type="range"]::-moz-range-thumb {
        width: 14px;
        height: 14px;
        border-radius: 999px;
        background: #111;
        border: none;
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .price-values {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.2rem;
        margin-top: 1rem;
        font-size: 1.35rem;
        font-weight: 700;
        color: #111;
    }

    .price-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.2rem;
        margin-top: .6rem;
    }

    .price-filter button {
        width: 100%;
        padding: 1rem 1.1rem;
        border-radius: 0;
        border: 1px solid #111;
        background: #fff;
        font-size: 1.4rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s ease, color .15s ease, border-color .15s ease;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .price-filter button:hover {
        background: #111;
        color: #fff;
        border-color: #111;
    }

    .shop-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.6rem;
        margin-bottom: 2rem;
        padding: 1.2rem 0;
    }

    @media (max-width: 576px) {
        .shop-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .results-text {
        font-size: 1.6rem;
        color: #111;
        font-weight: 600;
    }

    .sort-form {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .sort-form label {
        font-size: 1.4rem;
        color: #6b7280;
        font-weight: 600;
        margin: 0;
    }

    .sort-form select {
        padding: .9rem 1.2rem;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        font-size: 1.5rem;
        background: #fff;
        min-width: 220px;
    }

    /* Minimal tiles */
    .products-grid {
        margin-top: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 235px));
        justify-content: start;
        gap: 2.2rem;
    }

    .shop-main .product-card {
        background: transparent;
        border: none;
        box-shadow: none;
        padding: 0;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: .9rem;
        transition: none !important;
    }

    /* Hard override any legacy hover "lift" */
    .shop-main .product-card:hover {
        transform: none !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }

    .shop-main .product-card .product-image {
        width: 100%;
        height: 28rem;
        object-fit: cover;
        object-position: center;
        padding: 0;
        margin: 0;
        display: block;
        border-radius: .4rem;
        background: #fff;
        border: 1px solid #ececec;
        box-shadow: none;
        transition: none !important;
    }

    .shop-main .product-card:hover .product-image { transform: none !important; }

    .product-info { padding-top: .2rem; }

    .shop-main .product-card .product-name {
        font-size: 1.5rem;
        font-weight: 500;
        color: #111827;
        margin: 0 0 .25rem 0;
        min-height: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .shop-main .product-card .product-price {
        font-size: 1.4rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: inline-flex;
        align-items: baseline;
        gap: .6rem;
    }

    .shop-main .product-card .product-price .price-new {
        font-weight: 600;
        color: #111827;
    }

    .shop-main .product-card .product-price .price-old {
        font-weight: 500;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .shop-main .product-card .product-pieces {
        font-size: 1.2rem;
        font-weight: 500;
        color: #6b7280;
        margin-top: -.4rem;
    }

    .shop-main .product-card .sale-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        background: #1f1b2e;
        color: #fff;
        font-size: 1.15rem;
        font-weight: 600;
        padding: .55rem .9rem;
        border-radius: 1rem;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.14);
        line-height: 1;
    }

    /* Hide extra product info (image + name + price only) */
    .thumb-strip,
    .product-seller,
    .product-rating,
    .product-details,
    .product-meta,
    .out-of-stock-badge,
    .low-stock-warning {
        display: none !important;
    }

    /* Hover add-to-cart icon (uses existing form/button) */
    .product-card form {
        position: absolute;
        top: 14px;
        right: 14px;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity .18s ease, transform .18s ease;
        margin: 0;
        background: transparent !important;
    }

    .product-card:hover form,
    .product-card:focus-within form {
        opacity: 1;
        transform: translateY(0);
    }

    .product-card .quick-add-btn {
        border: none;
        background: transparent !important;
        padding: 0;
        margin: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform .15s ease;
        position: relative;
    }

    .product-card .quick-add-btn:hover {
        background: transparent !important;
        transform: translateY(-1px);
    }

    .product-card .btn-add-cart__label {
        position: absolute;
        right: calc(100% + 12px);
        top: 50%;
        transform: translateY(-50%);
        background: #1f1b2e;
        color: #fff;
        padding: .75rem 1.2rem;
        border-radius: 1rem;
        font-size: 1.25rem;
        font-weight: 500;
        letter-spacing: .01em;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.18);
        line-height: 1;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity .12s ease, transform .12s ease;
    }

    .product-card .btn-add-cart__label::after {
        content: '';
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-left: 10px solid #1f1b2e;
    }

    .product-card .btn-add-cart__icon {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: #fff !important;
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.14);
        color: #1f1b2e;
    }

    .product-card .btn-add-cart__icon i {
        font-size: 1.8rem;
        color: currentColor;
    }

    /* Show label only when hovering the add-to-cart icon/button */
    .product-card .quick-add-btn:hover .btn-add-cart__label,
    .product-card .quick-add-btn:focus-visible .btn-add-cart__label {
        opacity: 1;
        transform: translateY(-50%) translateX(-2px);
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
    <a href="{{ route('home') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <div class="shop-layout">
        {{-- Sidebar --}}
        <aside class="shop-sidebar">
            <div class="sidebar-card">
                <h2 class="sidebar-title">Categories</h2>
                @php $allCount = $categories->sum('products_count'); @endphp
                <ul class="category-list">
                    <li>
                        <a
                            class="category-link {{ !$category ? 'active' : '' }}"
                            href="{{ route('shop', array_filter(['sort' => $sort], fn($v) => $v !== null && $v !== '')) }}"
                        >
                            <span>All</span>
                            <span class="category-count">({{ $allCount }})</span>
                        </a>
                    </li>
                    @foreach($categories->filter(fn($c) => (int) ($c->products_count ?? 0) > 0) as $cat)
                        <li>
                            <a
                                class="category-link {{ $category === $cat->slug ? 'active' : '' }}"
                                href="{{ route('shop', array_filter(['category' => $cat->slug, 'sort' => $sort], fn($v) => $v !== null && $v !== '')) }}"
                            >
                                <span>{{ $cat->name }}</span>
                                <span class="category-count">({{ $cat->products_count }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-card">
                <h2 class="sidebar-title">Filter by price</h2>
                <form method="GET" action="{{ route('shop') }}" class="price-filter">
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    @if($search)
                        <input type="hidden" name="q" value="{{ $search }}">
                    @endif
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="price_min" id="price_min" value="{{ $priceMin ?? '' }}">
                    <input type="hidden" name="price_max" id="price_max" value="{{ $priceMax ?? '' }}">

                    @php
                        $bMin = (float) ($priceBoundsMin ?? 0);
                        $bMax = (float) ($priceBoundsMax ?? 0);
                        $uiMin = ($priceMin !== null ? (float) $priceMin : $bMin);
                        $uiMax = ($priceMax !== null ? (float) $priceMax : $bMax);
                    @endphp

                    <div class="range-wrap">
                        <div class="range-slider" data-min="{{ $bMin }}" data-max="{{ $bMax }}">
                            <div class="range-track"></div>
                            <div class="range-fill" id="priceRangeFill"></div>
                            <input
                                type="range"
                                id="priceRangeMin"
                                min="{{ $bMin }}"
                                max="{{ $bMax }}"
                                value="{{ $uiMin }}"
                                step="1"
                                aria-label="Minimum price"
                            >
                            <input
                                type="range"
                                id="priceRangeMax"
                                min="{{ $bMin }}"
                                max="{{ $bMax }}"
                                value="{{ $uiMax }}"
                                step="1"
                                aria-label="Maximum price"
                            >
                        </div>

                        <div class="price-values">
                            <span id="priceMinLabel">₱{{ number_format($uiMin, 0) }}</span>
                            <span id="priceMaxLabel">₱{{ number_format($uiMax, 0) }}</span>
                        </div>
                    </div>

                    <div class="price-actions">
                        <button type="button" id="priceResetBtn">Reset</button>
                        <button type="submit">Apply</button>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="shop-main">
            <div class="shop-toolbar">
                <div class="results-text">Showing all {{ $products->total() }} results</div>

                <form method="GET" action="{{ route('shop') }}" class="sort-form">
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    @if($search)
                        <input type="hidden" name="q" value="{{ $search }}">
                    @endif
                    @if($priceMin !== null && $priceMin !== '')
                        <input type="hidden" name="price_min" value="{{ $priceMin }}">
                    @endif
                    @if($priceMax !== null && $priceMax !== '')
                        <input type="hidden" name="price_max" value="{{ $priceMax }}">
                    @endif

                    <label for="sort">Default sorting</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="newest" @selected($sort === 'newest')>Newest</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
                        <option value="rating" @selected($sort === 'rating')>Top Rated</option>
                    </select>
                </form>
            </div>

    @if($products->count() > 0)
        {{-- Products Grid --}}
        <div class="products-grid">
            @foreach($products as $product)
                @php
                    $averageRating = $product->reviews()->avg('rating') ?? 0;
                    $totalReviews = $product->reviews()->count();
                    $salePrice = $product->sale_price ?? null;
                    $isSale = $salePrice !== null && (float) $salePrice > 0 && (float) $salePrice < (float) $product->price;
                    $pieces = (int) ($product->pieces ?? 1);
                @endphp
                <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->id) }}'">
                    {{-- Product Image --}}
                    @if($isSale)
                        <span class="sale-badge">Sale!</span>
                    @endif
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
                    @if($pieces > 1)
                        <div class="product-pieces">{{ $pieces }} pcs bundle</div>
                    @endif

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
                    @if($isSale)
                        <div class="product-price">
                            <span class="price-old">₱{{ number_format((float) $product->price, 2) }}</span>
                            <span class="price-new">₱{{ number_format((float) $salePrice, 2) }}</span>
                        </div>
                    @else
                        <div class="product-price">₱{{ number_format((float) $product->price, 2) }}</div>
                    @endif

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
                            <input type="hidden" name="price" value="{{ $isSale ? $salePrice : $product->price }}">
                            <input type="hidden" name="image" value="{{ $product->image_01 }}">
                            <button type="submit" class="quick-add-btn" aria-label="Add to cart" title="Add to cart">
                                <span class="btn-add-cart__label">Add to cart</span>
                                <span class="btn-add-cart__icon"><i class="fas fa-shopping-bag"></i></span>
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

        </main>
    </div>
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
        if (!shopHeader) return;
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
        const icon = button.querySelector('.btn-add-cart__icon i') || button.querySelector('i');
        if (icon && !button.dataset.originalIconClass) button.dataset.originalIconClass = icon.className;

        // Disable button to prevent double clicks
        button.disabled = true;
        if (icon) icon.className = 'fas fa-spinner fa-spin';

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
                if (window.refreshCartDrawer) window.refreshCartDrawer();

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
            if (icon) icon.className = button.dataset.originalIconClass || 'fas fa-shopping-bag';
        });
    }

    // Update cart count in header
    function updateCartCount(count) {
        const existingBadge = document.querySelector('.cart-badge');
        if (existingBadge) {
            existingBadge.textContent = count;
            existingBadge.style.display = count > 0 ? 'inline-flex' : 'none';
            return;
        }

        const cartLink = document.querySelector('.nav-right a[href*="cart"]');
        if (cartLink && count > 0) {
            const badge = document.createElement('span');
            badge.className = 'cart-badge';
            badge.textContent = count;
            cartLink.appendChild(badge);
        }
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

    // Price range slider (sidebar)
    (function initPriceRange() {
        const minSlider = document.getElementById('priceRangeMin');
        const maxSlider = document.getElementById('priceRangeMax');
        const minHidden = document.getElementById('price_min');
        const maxHidden = document.getElementById('price_max');
        const fill = document.getElementById('priceRangeFill');
        const minLabel = document.getElementById('priceMinLabel');
        const maxLabel = document.getElementById('priceMaxLabel');
        const resetBtn = document.getElementById('priceResetBtn');

        if (!minSlider || !maxSlider || !minHidden || !maxHidden || !fill || !minLabel || !maxLabel) return;

        const minBound = parseFloat(minSlider.min || '0');
        const maxBound = parseFloat(minSlider.max || '0');

        function clampValues(changed) {
            let minV = parseFloat(minSlider.value);
            let maxV = parseFloat(maxSlider.value);

            if (minV > maxV) {
                if (changed === 'min') {
                    minV = maxV;
                    minSlider.value = String(minV);
                } else {
                    maxV = minV;
                    maxSlider.value = String(maxV);
                }
            }

            const denom = (maxBound - minBound) || 1;
            const left = ((minV - minBound) / denom) * 100;
            const right = 100 - (((maxV - minBound) / denom) * 100);
            fill.style.left = `${left}%`;
            fill.style.right = `${right}%`;

            minLabel.textContent = `₱${Math.round(minV)}`;
            maxLabel.textContent = `₱${Math.round(maxV)}`;

            minHidden.value = String(Math.round(minV));
            maxHidden.value = String(Math.round(maxV));
        }

        minSlider.addEventListener('input', () => clampValues('min'));
        maxSlider.addEventListener('input', () => clampValues('max'));
        clampValues('min');

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                minSlider.value = String(minBound);
                maxSlider.value = String(maxBound);
                clampValues('min');
                minHidden.value = '';
                maxHidden.value = '';
                resetBtn.closest('form')?.submit();
            });
        }
    })();
</script>
@endpush
