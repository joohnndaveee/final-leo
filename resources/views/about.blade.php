@extends('layouts.app')

@section('title', "About Us \u2014 U-KAY HUB")

@push('styles')
<style>
.about-page {
    background: #f4f4f0;
    padding: 0;
    max-width: 100% !important;
    margin: 0 !important;
}
.about-hero {
    background: #1a1a1a;
    padding: 9rem 4rem 8rem;
}
.about-hero-inner { max-width: 1100px; margin: 0 auto; }
.about-eyebrow {
    font-size: 1.25rem;
    font-weight: 600;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--main-color);
    margin-bottom: 2rem;
}
.about-wordmark {
    font-size: clamp(5rem, 9vw, 9.6rem);
    font-weight: 900;
    letter-spacing: -.03em;
    line-height: 1;
    color: #fff;
    margin-bottom: 2.8rem;
}
.about-wordmark span { color: var(--main-color); }
.about-hero-tagline {
    font-size: 2rem;
    font-weight: 300;
    color: rgba(255,255,255,.65);
    max-width: 56rem;
    line-height: 1.6;
}
.about-strip {
    background: var(--main-color);
    padding: 1.8rem 4rem;
}
.about-strip-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    gap: 5rem;
    align-items: center;
}
.about-stat { display: flex; flex-direction: column; }
.about-stat-num { font-size: 2.8rem; font-weight: 800; color: #fff; line-height: 1; }
.about-stat-label { font-size: 1.25rem; color: rgba(255,255,255,.8); letter-spacing: .04em; margin-top: .3rem; }
.strip-sep { width: 1px; height: 4rem; background: rgba(255,255,255,.35); flex-shrink: 0; }
.about-body { max-width: 1100px; margin: 0 auto; padding: 7rem 4rem 8rem; }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3.2rem; margin-bottom: 3.2rem; }
.about-card {
    background: #fff;
    border-radius: 16px;
    padding: 3.6rem 3.2rem;
    border: 1px solid #e8e8e0;
    transition: box-shadow .25s ease, transform .25s ease;
}
.about-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,.08); transform: translateY(-3px); }
.card-icon {
    width: 52px; height: 52px;
    background: rgba(58,199,45,.1);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 2rem;
}
.card-icon i { font-size: 2.2rem; color: var(--main-color); }
.about-card h2 { font-size: 2.2rem; font-weight: 700; color: #1a1a1a; margin-bottom: 1.4rem; letter-spacing: -.02em; }
.about-card p { font-size: 1.55rem; line-height: 1.8; color: #5a5a5a; }
.about-card p strong { color: var(--main-color); font-weight: 600; }
.about-cta-block {
    background: #1a1a1a;
    border-radius: 20px;
    padding: 5.6rem 4rem;
    text-align: center;
    margin-top: 5rem;
}
.about-cta-block h3 { font-size: 3.2rem; font-weight: 800; color: #fff; letter-spacing: -.02em; margin-bottom: 1.4rem; }
.about-cta-block p { font-size: 1.6rem; color: rgba(255,255,255,.6); margin-bottom: 3.2rem; }
.cta-button {
    display: inline-flex; align-items: center; gap: 1rem;
    padding: 1.5rem 3.8rem;
    font-size: 1.6rem; font-weight: 700; color: #fff;
    background: var(--main-color);
    border-radius: 50px; text-decoration: none;
    transition: all .25s ease;
    box-shadow: 0 4px 24px rgba(58,199,45,.35);
}
.cta-button:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(58,199,45,.45); color: #fff; }
@media (max-width: 768px) {
    .about-hero { padding: 6rem 2.4rem 5.6rem; }
    .about-strip-inner { gap: 3rem; flex-wrap: wrap; }
    .about-body { padding: 5rem 2.4rem 6rem; }
    .about-grid { grid-template-columns: 1fr; gap: 2rem; }
    .about-cta-block { padding: 4rem 2.4rem; }
    .about-cta-block h3 { font-size: 2.6rem; }
}
@media (max-width: 480px) {
    .about-hero { padding: 5rem 2rem 4.4rem; }
    .about-wordmark { font-size: 5.6rem; }
    .about-body { padding: 4rem 2rem 5rem; }
}
</style>
@endpush

@section('content')
<div class="about-page">

    <div class="about-hero">
        <div class="about-hero-inner">
            <p class="about-eyebrow">Who We Are</p>
            <h1 class="about-wordmark">U-KAY<span>HUB</span></h1>
            <p class="about-hero-tagline">
                Reviving style, one thrift at a time. Your trusted destination for quality
                pre-loved clothing &mdash; sustainable, affordable, and always stylish.
            </p>
        </div>
    </div>

    <div class="about-strip">
        <div class="about-strip-inner">
            <div class="about-stat">
                <span class="about-stat-num">100+</span>
                <span class="about-stat-label">Products Listed</span>
            </div>
            <div class="strip-sep"></div>
            <div class="about-stat">
                <span class="about-stat-num">50+</span>
                <span class="about-stat-label">Happy Customers</span>
            </div>
            <div class="strip-sep"></div>
            <div class="about-stat">
                <span class="about-stat-num">10+</span>
                <span class="about-stat-label">Active Sellers</span>
            </div>
            <div class="strip-sep"></div>
            <div class="about-stat">
                <span class="about-stat-num">0%</span>
                <span class="about-stat-label">Fast Fashion Waste</span>
            </div>
        </div>
    </div>

    <div class="about-body">
        <div class="about-grid">

            <div class="about-card">
                <div class="card-icon"><i class="fas fa-bullseye"></i></div>
                <h2>Our Mission</h2>
                <p>At <strong>U-KAY HUB</strong>, we believe everyone deserves quality fashion without breaking
                the bank. We carefully curate pre-loved pieces so you get the best value while looking
                your absolute best &mdash; especially for students on a budget.</p>
            </div>

            <div class="about-card">
                <div class="card-icon"><i class="fas fa-leaf"></i></div>
                <h2>Sustainability Matters</h2>
                <p>Fashion should not cost the earth &mdash; literally. Every pre-loved item you purchase is one
                less piece in a landfill and one step toward a more sustainable future. Join us in making
                fashion circular and <strong>planet-friendly</strong>.</p>
            </div>

            <div class="about-card">
                <div class="card-icon"><i class="fas fa-check-circle"></i></div>
                <h2>Our Process</h2>
                <p>Quality is our top priority. Every item is hand-picked, washed, sanitized, and inspected
                before it reaches your cart. When you shop with us, you get
                <strong>clean, safe, and stylish</strong> clothing guaranteed.</p>
            </div>

            <div class="about-card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h2>Our Story</h2>
                <p>We are a proud local business from <strong>Buenavista / Butuan</strong>, run by passionate
                IT students from <strong>4inTech</strong>. What started as a small project has grown into
                a mission to transform how our community shops for fashion.</p>
            </div>

        </div>

        <div class="about-cta-block">
            <h3>Ready to Find Your Next Favorite Piece?</h3>
            <p>Browse hundreds of curated pre-loved items &mdash; new stock added every week.</p>
            <a href="{{ route('shop') }}" class="cta-button">
                <i class="fas fa-shopping-bag"></i> Start Thrifting
            </a>
        </div>
    </div>

</div>
@endsection