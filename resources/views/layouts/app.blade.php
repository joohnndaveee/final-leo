<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'U-KAY HUB - Online Shop')</title>

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

        /* Enhanced header styling */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
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

        /* Footer styling */
        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin-top: 5rem;
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
            <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'active' : '' }}">Orders</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        </nav>

        {{-- Icons --}}
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="{{ route('search') }}"><i class="fas fa-search"></i></a>
            <a href="{{ route('wishlist') }}">
                <i class="fas fa-heart"></i>
                <span>({{ $wishlistCount }})</span>
            </a>
            <a href="{{ route('cart') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>({{ $cartCount }})</span>
            </a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        {{-- User Profile Dropdown --}}
        <div class="profile">
            @auth
                <p>{{ Auth::user()->name }}</p>
                <a href="{{ route('profile.update') }}" class="btn">Update Profile</a>
                <div class="flex-btn">
                    {{-- Additional profile actions can go here --}}
                </div>
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
            @endauth
        </div>
    </section>
</header>

{{-- Main Content --}}
@yield('content')

{{-- Footer --}}
<footer class="footer">
    <section class="grid">
        {{-- Contact Information --}}
        <div class="box">
            <h3>Contact Us</h3>
            <a><i class="fas fa-phone"></i> +63936149724</a>
            <a href="https://maps.app.goo.gl/m2pYdGEKLH9EEqUd8" target="_blank">
                <i class="fas fa-map-marker-alt"></i> MMACI Campus, Butuan City 8600, Philippines
            </a>
        </div>

        {{-- Social Media Links --}}
        <div class="box">
            <h3>Follow Us</h3>
            <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
            <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
        </div>
    </section>

    <div class="credit">
        &copy; copyright @ {{ date('Y') }} by <span>4in1 Tech</span> | all rights reserved!
    </div>
</footer>

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
