<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'U-KAY HUB - Online Shop')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;500;600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- User Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        /* Background overlay for all pages */
        body {
            background: url('{{ asset('images/bg.png') }}') center center fixed;
            background-size: cover;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }

        /* Enhanced header styling with background image */
        .header {
            background: url('{{ asset('images/bg.png') }}') center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            z-index: 0;
        }

        .header .flex {
            position: relative;
            z-index: 1;
        }

        /* Make text more readable with text shadow */
        .header .navbar a,
        .header .flex .logo .text {
            text-shadow: 0 2px 4px rgba(255, 255, 255, 0.8);
        }

        /* Logo styling */
        .header .flex .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .header .flex .logo img {
            width: 40px;
            height: auto;
        }

        .header .flex .logo .text {
            font-family: 'Nunito', sans-serif;
            font-size: 25px;
            font-weight: 800;
            color: #000;
        }

        .header .flex .logo .text span {
            color: var(--main-color);
        }

        /* Modern Footer Styling */
        .modern-footer {
            background: linear-gradient(135deg, #1a3009 0%, #2d5016 100%);
            color: white;
            margin-top: 5rem;
            position: relative;
            overflow: hidden;
        }

        .modern-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--main-color) 0%, #ffd700 50%, var(--main-color) 100%);
        }

        .footer-content {
            padding: 5rem 2rem 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr;
            gap: 4rem;
            margin-bottom: 3rem;
        }

        /* Brand Section */
        .brand-section .footer-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .brand-section .footer-logo img {
            width: 50px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .brand-section .footer-logo h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin: 0;
        }

        .brand-section .footer-logo h3 span {
            color: #ffd700;
        }

        .footer-description {
            font-size: 1.5rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }

        /* Social Links */
        .social-links {
            display: flex;
            gap: 1.2rem;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.8rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .social-icon:hover {
            background: var(--main-color);
            border-color: #ffd700;
            transform: translateY(-5px) rotate(360deg);
        }

        /* Footer Sections */
        .footer-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffd700;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 1rem;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--main-color);
        }

        /* Footer Links */
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 1.2rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.6rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .footer-links a i {
            color: var(--main-color);
            font-size: 1.4rem;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-links a:hover i {
            color: #ffd700;
        }

        /* Footer Contact */
        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-contact li {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.8rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.5rem;
            line-height: 1.6;
        }

        .footer-contact li i {
            color: var(--main-color);
            font-size: 1.8rem;
            min-width: 20px;
            margin-top: 0.3rem;
        }

        /* Footer Bottom */
        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 2rem;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom p {
            margin: 0;
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-bottom span {
            color: #ffd700;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .footer-content {
                padding: 4rem 2rem 2rem;
            }
        }

        @media (max-width: 450px) {
            .footer-content {
                padding: 3rem 1.5rem 1.5rem;
            }

            .brand-section .footer-logo h3 {
                font-size: 2rem;
            }

            .social-links {
                flex-wrap: wrap;
            }
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

{{-- Messages --}}
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

{{-- Header --}}
<header class="header">
    <section class="flex">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="U-KAY HUB Logo">
            <span class="text">U-KAY<span>HUB</span></span>
        </a>

        {{-- Navigation --}}
        <nav class="navbar">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        </nav>

        {{-- Icons --}}
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="{{ route('cart') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>({{ $cartCount }})</span>
            </a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        {{-- User Profile Dropdown --}}
        <div class="profile">
            @php
                $webUser = Auth::user();
                $isSeller = $webUser && (($webUser->role ?? 'buyer') === 'seller');
            @endphp

            @if($webUser && !$isSeller)
                <p>{{ $webUser->name }}</p>
                <a href="{{ route('profile.edit') }}" class="btn">Update Profile</a>
                <a href="{{ route('orders') }}" class="btn">Orders</a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="delete-btn" onclick="return confirm('Logout from the website?');" style="width: 100%; border: none; cursor: pointer;">
                        Logout
                    </button>
                </form>
            @else
                <p>Please login or register first!</p>
                <div class="flex-btn">
                    <a href="{{ route('register') }}" class="option-btn">Register</a>
                    <a href="{{ route('login') }}" class="option-btn">Login</a>
                </div>
            @endif
        </div>
    </section>
</header>

{{-- Main Content --}}
@yield('content')

{{-- Modern Footer --}}
<footer class="modern-footer">
    <div class="footer-content">
        <div class="footer-grid">
            {{-- Brand Section --}}
            <div class="footer-section brand-section">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="U-KAY HUB Logo">
                    <h3>U-KAY<span>HUB</span></h3>
                </div>
                <p class="footer-description">
                    Your trusted destination for quality pre-loved clothing. Reviving style, one thrift at a time.
                </p>
                <div class="social-links">
                    <a href="#" class="social-icon facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon twitter" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon instagram" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon linkedin" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="footer-section">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="fas fa-angle-right"></i> Home</a></li>
                    <li><a href="{{ route('about') }}"><i class="fas fa-angle-right"></i> About Us</a></li>
                    <li><a href="{{ route('shop') }}"><i class="fas fa-angle-right"></i> Shop</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fas fa-angle-right"></i> Contact</a></li>
                    @if(request()->routeIs('home'))
                        <li>
                            <a href="{{ route('seller.register') }}">
                                <i class="fas fa-angle-right"></i> Become a Seller
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Contact Information --}}
            <div class="footer-section">
                <h3 class="footer-title">Contact Us</h3>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>P-6 Abilan, Buenavista<br>Agusan del Norte, Philippines</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>0930 447 5164</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>info@ukayhub.com</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Footer Bottom --}}
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} <span>U-KAY HUB</span> by 4in1 Tech. All Rights Reserved.</p>
    </div>
</footer>

{{-- Floating Chat Widget (Only for logged-in users) --}}
@if(Auth::check())
    @include('components.chat-widget')
@endif

{{-- Scripts --}}
<script src="{{ asset('js/script.js') }}"></script>

<script>
    // Mobile menu toggle
    let navbar = document.querySelector('.header .flex .navbar');
    let profile = document.querySelector('.header .flex .profile');

    document.querySelector('#menu-btn').onclick = () => {
        navbar.classList.toggle('active');
        profile.classList.remove('active');
    }

    document.querySelector('#user-btn').onclick = () => {
        profile.classList.toggle('active');
        navbar.classList.remove('active');
    }

    window.onscroll = () => {
        navbar.classList.remove('active');
        profile.classList.remove('active');
    }

    // Auto-hide messages after 5 seconds
    document.querySelectorAll('.message').forEach(message => {
        setTimeout(() => {
            message.style.display = 'none';
        }, 5000);
    });
</script>

@stack('scripts')

</body>
</html>
