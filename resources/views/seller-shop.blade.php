@extends('layouts.app')

@section('title', ($seller->shop_name ?? 'Shop') . ' - U-KAY HUB')

@push('styles')
<style>
/* ─── Variables ─────────────────────────────────── */
:root {
    --green: #16a34a;
    --green-dark: #15803d;
    --green-light: #f0fdf4;
    --star: #f59e0b;
    --border: #e8e8e8;
    --text: #333;
    --muted: #757575;
    --card-radius: 4px;
}

/* ─── Layout ─────────────────────────────────────── */
.ss-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.6rem 4rem;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
}

/* ─── Shop Header ────────────────────────────────── */
.ss-header {
    background: linear-gradient(135deg, #1e3a2f 0%, #14532d 100%);
    margin: 0 -1.6rem 0;
    padding: 2.8rem 3rem;
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 2.4rem;
    align-items: center;
}
.ss-header-left {
    display: flex;
    align-items: center;
    gap: 1.8rem;
}
.ss-shop-logo {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.25);
    background: #fff;
    flex-shrink: 0;
}
.ss-shop-logo-placeholder {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: #16a34a;
    color: #fff;
    font-size: 3rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(255,255,255,.25);
    flex-shrink: 0;
}
.ss-shop-name {
    font-size: 2.2rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 .3rem;
    line-height: 1.2;
}
.ss-shop-active {
    font-size: 1.25rem;
    color: rgba(255,255,255,.65);
    margin-bottom: .8rem;
}
.ss-header-btns {
    display: flex;
    gap: .7rem;
    flex-wrap: wrap;
}
.ss-header-btn {
    padding: .55rem 1.4rem;
    font-size: 1.3rem;
    border-radius: 2px;
    cursor: pointer;
    font-family: inherit;
    transition: all .18s;
    display: flex;
    align-items: center;
    gap: .45rem;
    text-decoration: none;
    font-weight: 500;
}
.ss-btn-follow {
    background: transparent;
    color: #fff;
    border: 1.5px solid rgba(255,255,255,.7);
}
.ss-btn-follow:hover { background: rgba(255,255,255,.1); border-color: #fff; }
.ss-btn-following {
    background: rgba(255,255,255,.15);
    color: #fff;
    border-color: #fff;
}
.ss-btn-follow.loading { opacity: .6; pointer-events: none; }
.ss-btn-chat {
    background: #fff;
    color: var(--green);
    border: 1.5px solid #fff;
}
.ss-btn-chat:hover { background: #f0fdf4; }

.ss-header-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .6rem 2rem;
    font-size: 1.28rem;
    padding-top: 1rem;
}
.ss-stat {
    display: flex;
    align-items: center;
    gap: .6rem;
    color: rgba(255,255,255,.75);
}
.ss-stat i { font-size: 1.3rem; opacity: .7; }
.ss-stat-label { color: rgba(255,255,255,.55); }
.ss-stat-val { color: #fff; font-weight: 500; }

/* ─── Nav Tabs ───────────────────────────────────── */
.ss-nav {
    background: #fff;
    border-bottom: 1px solid var(--border);
    margin: 0 -1.6rem;
    padding: 0 3rem;
    display: flex;
    align-items: center;
    gap: 0;
    overflow-x: auto;
    scrollbar-width: none;
}
.ss-nav::-webkit-scrollbar { display: none; }
.ss-nav-tab {
    padding: 1.2rem 1.6rem;
    font-size: 1.35rem;
    color: var(--muted);
    cursor: pointer;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    font-family: inherit;
    font-weight: 500;
    transition: color .18s, border-color .18s;
    text-decoration: none;
    display: block;
}
.ss-nav-tab:hover { color: var(--green); }
.ss-nav-tab.active {
    color: var(--green);
    border-bottom-color: var(--green);
}

/* ─── Content ────────────────────────────────────── */
.ss-content {
    padding-top: 2rem;
}

/* ─── Section heading ────────────────────────────── */
.ss-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.4rem;
}
.ss-section-title {
    font-size: 1.55rem;
    font-weight: 700;
    color: var(--text);
    letter-spacing: .01em;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.ss-section-title i { color: var(--green); }
.ss-see-all {
    font-size: 1.3rem;
    color: var(--green);
    text-decoration: none;
    font-weight: 500;
    transition: color .15s;
}
.ss-see-all:hover { color: var(--green-dark); }

/* ─── Search bar ─────────────────────────────────── */
.ss-search-form {
    display: flex;
    gap: .7rem;
    margin-bottom: 1.8rem;
    max-width: 460px;
}
.ss-search-input {
    flex: 1;
    border: 1.5px solid var(--border);
    border-radius: 2px;
    padding: .75rem 1.1rem;
    font-size: 1.35rem;
    font-family: inherit;
    outline: none;
    transition: border-color .18s;
    color: var(--text);
    background: #fff;
}
.ss-search-input:focus { border-color: var(--green); }
.ss-search-btn {
    padding: .75rem 1.4rem;
    background: var(--green);
    color: #fff;
    border: none;
    border-radius: 2px;
    font-size: 1.35rem;
    cursor: pointer;
    font-family: inherit;
    transition: background .18s;
}
.ss-search-btn:hover { background: var(--green-dark); }

/* ─── Product Grid ───────────────────────────────── */
.ss-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: .8rem;
}
.ss-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--card-radius);
    overflow: hidden;
    cursor: pointer;
    transition: box-shadow .2s, transform .2s;
    text-decoration: none;
    color: inherit;
    display: block;
}
.ss-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,.1);
    transform: translateY(-2px);
}
.ss-card-img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
    display: block;
    background: #f5f5f5;
}
.ss-card-body {
    padding: .7rem .8rem .9rem;
}
.ss-card-name {
    font-size: 1.3rem;
    color: var(--text);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: .5rem;
    min-height: 3.6rem;
}
.ss-card-price-row {
    display: flex;
    align-items: baseline;
    gap: .5rem;
    flex-wrap: wrap;
    margin-bottom: .4rem;
}
.ss-card-price {
    font-size: 1.7rem;
    color: var(--green);
    font-weight: 600;
}
.ss-card-price-old {
    font-size: 1.2rem;
    color: #bbb;
    text-decoration: line-through;
}
.ss-card-discount {
    font-size: 1.1rem;
    color: #fff;
    background: var(--green);
    padding: .15rem .4rem;
    border-radius: 2px;
    font-weight: 600;
}
.ss-card-meta {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: 1.15rem;
    color: var(--muted);
}
.ss-card-stars { color: var(--star); font-size: 1.2rem; }
.ss-card-rating { color: var(--muted); }

/* ─── About Shop ─────────────────────────────────── */
.ss-about {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--card-radius);
    padding: 2rem 2.4rem;
    margin-top: 2rem;
}
.ss-about-head {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 1.4rem;
    padding-bottom: .8rem;
    border-bottom: 1px solid #f0f0f0;
}
.ss-about-desc {
    font-size: 1.38rem;
    color: #555;
    line-height: 1.75;
}
.ss-about-empty {
    font-size: 1.35rem;
    color: var(--muted);
    font-style: italic;
}

/* ─── Empty state ────────────────────────────────── */
.ss-empty {
    text-align: center;
    padding: 5rem 1rem;
    color: var(--muted);
    font-size: 1.45rem;
    background: #fff;
    border: 1px dashed var(--border);
    border-radius: var(--card-radius);
}
.ss-empty i { font-size: 4rem; color: #d1d5db; display: block; margin-bottom: 1rem; }

/* ─── Pagination ─────────────────────────────────── */
.ss-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: .4rem;
    margin-top: 2.4rem;
    flex-wrap: wrap;
}
.ss-page-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 .6rem;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--text);
    font-size: 1.3rem;
    cursor: pointer;
    border-radius: 2px;
    font-family: inherit;
    transition: all .15s;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}
.ss-page-btn:hover:not(.disabled) { border-color: var(--green); color: var(--green); }
.ss-page-btn.active { background: var(--green); border-color: var(--green); color: #fff; }
.ss-page-btn.disabled { color: #ccc; cursor: default; }

/* ─── Responsive ─────────────────────────────────── */
@media (max-width: 1024px) {
    .ss-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 768px) {
    .ss-header { grid-template-columns: 1fr; padding: 2rem 1.6rem; }
    .ss-header-stats { grid-template-columns: repeat(2, 1fr); }
    .ss-grid { grid-template-columns: repeat(3, 1fr); }
    .ss-nav { padding: 0 1.2rem; }
}
@media (max-width: 520px) {
    .ss-grid { grid-template-columns: repeat(2, 1fr); }
    .ss-header-stats { grid-template-columns: 1fr; gap: .4rem; }
    .ss-header { gap: 1.4rem; }
}
</style>
@endpush

@section('content')
@php
    $shopName   = $seller->shop_name ?? 'Shop';
    $shopLogo   = !empty($seller->shop_logo)
        ? asset('uploaded_img/' . $seller->shop_logo)
        : null;
    $joinedAgo  = $seller->created_at ? $seller->created_at->diffForHumans(null, true) . ' ago' : '—';
    $isHome     = $tab === 'home' && !$category && !$search;
    $isAll      = $tab === 'all' || $search || ($tab === 'home' && ($category || $search));
@endphp

<div class="ss-wrap">

    {{-- ── Shop Header ─────────────────────────────── --}}
    <div class="ss-header">
        <div class="ss-header-left">
            @if($shopLogo)
                <img src="{{ $shopLogo }}"
                     alt="{{ $shopName }}"
                     class="ss-shop-logo"
                     onerror="this.replaceWith(document.querySelector('.ss-shop-logo-placeholder').cloneNode(true))">
            @else
                <div class="ss-shop-logo-placeholder">{{ strtoupper(substr($shopName, 0, 1)) }}</div>
            @endif
            <div>
                <p class="ss-shop-name">{{ $shopName }}</p>
                <p class="ss-shop-active"><i class="fas fa-circle" style="font-size:.7rem;color:#4ade80;"></i> Active recently</p>
                <div class="ss-header-btns">
                    <button type="button"
                            class="ss-header-btn ss-btn-follow {{ $isFollowing ? 'ss-btn-following' : '' }}"
                            id="followBtn"
                            data-seller-id="{{ $seller->id }}"
                            data-following="{{ $isFollowing ? '1' : '0' }}"
                            data-follow-url="{{ route('seller.follow.toggle', $seller->id) }}"
                            data-login-url="{{ route('login') }}">
                        <i class="fas {{ $isFollowing ? 'fa-check' : 'fa-plus' }}" id="followIcon"></i>
                        <span id="followLabel">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                    </button>
                    @auth
                        <a href="{{ route('user.seller.chat.show', $seller->id) }}"
                           class="ss-header-btn ss-btn-chat" style="text-decoration:none;">
                            <i class="fas fa-comment-dots"></i> Chat
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('user.seller.chat.show', $seller->id)) }}"
                           class="ss-header-btn ss-btn-chat" style="text-decoration:none;">
                            <i class="fas fa-comment-dots"></i> Chat
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="ss-header-stats">
            <div class="ss-stat">
                <i class="fas fa-box"></i>
                <span class="ss-stat-label">Products:</span>
                <span class="ss-stat-val">{{ number_format($totalProducts) }}</span>
            </div>
            <div class="ss-stat">
                <i class="fas fa-users"></i>
                <span class="ss-stat-label">Followers:</span>
                <span class="ss-stat-val" id="followerCountDisplay">{{ number_format($followerCount) }}</span>
            </div>
            <div class="ss-stat">
                <i class="fas fa-star"></i>
                <span class="ss-stat-label">Rating:</span>
                <span class="ss-stat-val">{{ $averageRating > 0 ? $averageRating . ' (' . number_format($totalReviews) . ' ratings)' : '—' }}</span>
            </div>
            <div class="ss-stat">
                <i class="fas fa-comment"></i>
                <span class="ss-stat-label">Chat Performance:</span>
                <span class="ss-stat-val">Within hours</span>
            </div>
            <div class="ss-stat">
                <i class="fas fa-calendar-alt"></i>
                <span class="ss-stat-label">Joined:</span>
                <span class="ss-stat-val">{{ $joinedAgo }}</span>
            </div>
        </div>
    </div>

    {{-- ── Nav Tabs ──────────────────────────────────── --}}
    <nav class="ss-nav">
        <a href="{{ route('seller.shop', $seller->id) }}"
           class="ss-nav-tab {{ $isHome ? 'active' : '' }}">Home</a>
        <a href="{{ route('seller.shop', $seller->id) }}?tab=all"
           class="ss-nav-tab {{ $tab === 'all' && !$category ? 'active' : '' }}">All Products</a>
        @foreach($sellerCategories as $cat)
        <a href="{{ route('seller.shop', $seller->id) }}?category={{ $cat->slug ?? $cat->id }}"
           class="ss-nav-tab {{ $category == ($cat->slug ?? $cat->id) ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </nav>

    {{-- ── Main Content ──────────────────────────────── --}}
    <div class="ss-content">

        {{-- Search --}}
        <form method="GET" action="{{ route('seller.shop', $seller->id) }}" class="ss-search-form">
            <input type="hidden" name="tab" value="{{ $tab }}">
            @if($category)<input type="hidden" name="category" value="{{ $category }}">@endif
            <input type="text" name="q" class="ss-search-input"
                   placeholder="Search in {{ $shopName }}…"
                   value="{{ $search ?? '' }}">
            <button type="submit" class="ss-search-btn"><i class="fas fa-search"></i></button>
        </form>

        @if($isHome && !$search)
            {{-- ── HOME TAB ── --}}

            {{-- Recommended for you (featured/latest) --}}
            <div class="ss-section-head">
                <span class="ss-section-title"><i class="fas fa-fire"></i> Recommended for You</span>
                <a href="{{ route('seller.shop', $seller->id) }}?tab=all" class="ss-see-all">See All ›</a>
            </div>

            @if($featuredProducts->count())
            <div class="ss-grid" style="margin-bottom:2.4rem;">
                @foreach($featuredProducts as $product)
                    @include('partials.seller-shop-card', ['product' => $product])
                @endforeach
            </div>
            @else
            <div class="ss-empty" style="margin-bottom:2.4rem;">
                <i class="fas fa-box-open"></i>
                <p>No products yet in this shop.</p>
            </div>
            @endif

            {{-- About Shop --}}
            <div class="ss-about">
                <div class="ss-about-head">ABOUT SHOP</div>
                @if(!empty($seller->shop_description))
                    <div class="ss-about-desc">{{ $seller->shop_description }}</div>
                @else
                    <div class="ss-about-empty">No shop description available.</div>
                @endif
            </div>

        @else
            {{-- ── ALL PRODUCTS / CATEGORY / SEARCH TAB ── --}}

            <div class="ss-section-head">
                <span class="ss-section-title">
                    @if($search)
                        <i class="fas fa-search"></i> Results for "{{ $search }}"
                    @elseif($category)
                        <i class="fas fa-tag"></i> {{ $sellerCategories->firstWhere('slug', $category)?->name ?? $sellerCategories->firstWhere('id', $category)?->name ?? 'Category' }}
                    @else
                        <i class="fas fa-th"></i> All Products
                    @endif
                </span>
                <span style="font-size:1.28rem;color:var(--muted);">{{ $products->total() }} items</span>
            </div>

            @if($products->count())
            <div class="ss-grid">
                @foreach($products as $product)
                    @include('partials.seller-shop-card', ['product' => $product])
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->lastPage() > 1)
            <div class="ss-pagination">
                {{-- Prev --}}
                @if($products->onFirstPage())
                    <span class="ss-page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a class="ss-page-btn" href="{{ $products->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                @for($p = 1; $p <= $products->lastPage(); $p++)
                    @if($p == 1 || $p == $products->lastPage() || abs($p - $products->currentPage()) <= 2)
                        <a class="ss-page-btn {{ $p == $products->currentPage() ? 'active' : '' }}"
                           href="{{ $products->url($p) }}">{{ $p }}</a>
                    @elseif(abs($p - $products->currentPage()) == 3)
                        <span class="ss-page-btn disabled">…</span>
                    @endif
                @endfor

                {{-- Next --}}
                @if($products->hasMorePages())
                    <a class="ss-page-btn" href="{{ $products->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="ss-page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
            @endif

            @else
            <div class="ss-empty">
                <i class="fas fa-box-open"></i>
                <p>No products found{{ $search ? ' for "' . $search . '"' : '' }}.</p>
            </div>
            @endif
        @endif

    </div>{{-- /.ss-content --}}
</div>{{-- /.ss-wrap --}}
@endsection

@push('scripts')
<script>
(function () {
    const btn = document.getElementById('followBtn');
    if (!btn) return;

    btn.addEventListener('click', async function () {
        const loginUrl  = btn.dataset.loginUrl;
        const followUrl = btn.dataset.followUrl;
        const csrf      = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Redirect to login if not authenticated
        if (!{{ Auth::check() ? 'true' : 'false' }}) {
            window.location.href = loginUrl;
            return;
        }

        btn.classList.add('loading');

        try {
            const res  = await fetch(followUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const data = await res.json();

            if (data.success) {
                const icon  = document.getElementById('followIcon');
                const label = document.getElementById('followLabel');
                const count = document.getElementById('followerCountDisplay');

                if (data.following) {
                    btn.classList.add('ss-btn-following');
                    icon.className  = 'fas fa-check';
                    label.textContent = 'Following';
                } else {
                    btn.classList.remove('ss-btn-following');
                    icon.className  = 'fas fa-plus';
                    label.textContent = 'Follow';
                }

                if (count) {
                    count.textContent = new Intl.NumberFormat().format(data.follower_count);
                }
            }
        } catch (e) {
            console.error('Follow error:', e);
        } finally {
            btn.classList.remove('loading');
        }
    });
})();
</script>
@endpush
