@extends('layouts.app')

@section('title', 'About Us - U-KAY HUB')

@section('content')

<section class="about">
    <div class="about-container">
        {{-- Brand Identity --}}
        <div class="about-header">
            <img src="{{ asset('images/logo.png') }}" alt="4inTech Logo" class="about-logo">
            <h1 class="about-title">Reviving Style, One Thrift at a Time</h1>
            <div class="title-divider"></div>
        </div>

        {{-- Our Mission --}}
        <div class="about-section">
            <div class="section-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <h2>Our Mission</h2>
            <p>
                At <strong>U-KAY HUB</strong>, we believe that everyone deserves access to quality fashion without breaking the bank. 
                Our mission is to provide high-quality, pre-loved clothing at affordable prices, making style accessible to everyone—especially students. 
                We carefully curate each piece to ensure you get the best value for your money while looking your absolute best.
            </p>
        </div>

        {{-- Sustainability --}}
        <div class="about-section">
            <div class="section-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <h2>Sustainability Matters</h2>
            <p>
                Fashion shouldn't cost the earth—literally. By choosing Ukay-Ukay, you're making an eco-conscious decision 
                that helps reduce textile waste and minimizes environmental impact. Every pre-loved item you purchase is one 
                less piece in a landfill and one step toward a more sustainable future. Join us in making fashion circular 
                and planet-friendly!
            </p>
        </div>

        {{-- Our Process --}}
        <div class="about-section">
            <div class="section-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <h2>Our Process</h2>
            <p>
                Quality is our top priority. Every single item that arrives at U-KAY HUB goes through a rigorous selection 
                process. We hand-pick only the finest pieces, ensuring they meet our high standards. Each item is then 
                thoroughly washed, sanitized, and inspected for quality before it reaches our shop floor. When you shop 
                with us, you can be confident that you're getting clean, safe, and stylish clothing.
            </p>
        </div>

        {{-- Team/Location --}}
        <div class="about-section">
            <div class="section-icon">
                <i class="fas fa-users"></i>
            </div>
            <h2>Our Story</h2>
            <p>
                We're a proud local business based in <strong>Buenavista/Butuan</strong>, run by passionate IT students from 
                <strong>4inTech</strong>. What started as a small project has grown into a mission to transform how our 
                community shops for fashion. We combine our tech skills with a love for sustainable fashion to bring you 
                the best thrifting experience—both online and in-store. Supporting us means supporting local students and 
                entrepreneurs making a difference!
            </p>
        </div>

        {{-- Call to Action --}}
        <div class="about-cta">
            <h3>Ready to Discover Your Next Favorite Piece?</h3>
            <a href="{{ route('shop') }}" class="cta-button">
                <i class="fas fa-shopping-bag"></i> Start Thrifting
            </a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .about {
        padding: 3rem 2rem;
        min-height: calc(100vh - 200px);
    }

    .about-container {
        max-width: 900px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 4rem 3rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    /* Brand Identity Section */
    .about-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .about-logo {
        width: 120px;
        height: auto;
        margin-bottom: 1.5rem;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    }

    .about-title {
        font-family: 'Nunito', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .title-divider {
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--main-color), #27ae60);
        margin: 0 auto;
        border-radius: 2px;
    }

    /* Content Sections */
    .about-section {
        margin-bottom: 3rem;
        padding: 2rem;
        border-left: 4px solid var(--main-color);
        background: rgba(39, 174, 96, 0.05);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .about-section:hover {
        background: rgba(39, 174, 96, 0.1);
        transform: translateX(5px);
    }

    .section-icon {
        display: inline-block;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .section-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .about-section h2 {
        font-family: 'Nunito', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .about-section p {
        font-family: 'Lato', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: justify;
    }

    .about-section strong {
        color: var(--main-color);
        font-weight: 600;
    }

    /* Call to Action Section */
    .about-cta {
        text-align: center;
        margin-top: 4rem;
        padding: 3rem 2rem;
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(46, 204, 113, 0.1));
        border-radius: 15px;
        border: 2px dashed var(--main-color);
    }

    .about-cta h3 {
        font-family: 'Nunito', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .cta-button {
        display: inline-block;
        padding: 1rem 3rem;
        font-family: 'Nunito', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(39, 174, 96, 0.3);
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(39, 174, 96, 0.4);
    }

    .cta-button i {
        margin-right: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .about {
            padding: 2rem 1rem;
        }

        .about-container {
            padding: 2rem 1.5rem;
        }

        .about-title {
            font-size: 1.8rem;
        }

        .about-section {
            padding: 1.5rem;
        }

        .about-section h2 {
            font-size: 1.5rem;
        }

        .about-section p {
            font-size: 1rem;
        }

        .about-cta h3 {
            font-size: 1.5rem;
        }

        .cta-button {
            padding: 0.8rem 2rem;
            font-size: 1rem;
        }
    }
</style>
@endpush
