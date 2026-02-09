<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - U-KAY HUB</title>

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
        /* Background overlay for register page */
        body {
            background: url('{{ asset('images/bg.png') }}') center center fixed;
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        /* Modern form container */
        .form-container {
            padding: 2rem 0;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container form {
            background: #ffffff;
            border-radius: 1.6rem;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
            padding: 3rem 4rem !important;
            margin: 0 auto !important;
            max-width: 600px !important;
            width: 600px !important;
            border: 1px solid rgba(39, 174, 96, 0.1);
        }

        .form-container form h3 {
            font-size: 2.6rem !important;
            margin-bottom: 0.6rem !important;
            color: #0f172a;
            font-weight: 800;
            text-align: center;
        }

        .form-header-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .input-group i {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--main-color);
            font-size: 1.6rem;
            pointer-events: none;
        }

        .input-group .box {
            width: 100%;
            padding: 1.3rem 1.5rem 1.3rem 4.5rem !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 1rem !important;
            font-size: 1.5rem !important;
            color: #0f172a;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .input-group .box:focus {
            border-color: var(--main-color) !important;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
            outline: none;
        }

        .input-group .box::placeholder {
            color: #94a3b8;
        }

        .form-container form .btn {
            width: 100%;
            padding: 1.4rem !important;
            background: linear-gradient(135deg, var(--main-color) 0%, #1f8f52 100%);
            color: white;
            border: none;
            border-radius: 1rem;
            font-size: 1.6rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-container form .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(39, 174, 96, 0.3);
        }

        .form-divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            color: #94a3b8;
            font-size: 1.3rem;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .form-divider span {
            padding: 0 1rem;
        }

        .form-container form p {
            margin: 0 !important;
            text-align: center;
            color: #64748b;
            font-size: 1.4rem;
        }

        .form-container form .option-btn {
            width: 100%;
            display: block;
            padding: 1.2rem;
            background: transparent;
            color: var(--main-color);
            border: 2px solid var(--main-color);
            border-radius: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .form-container form .option-btn:hover {
            background: var(--main-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.2);
        }

        /* Footer styling */
        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin-top: auto;
        }

        /* Message styling */
        .message {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Compact Modern Footer */
        .modern-footer {
            background: linear-gradient(135deg, #1a3009 0%, #2d5016 100%);
            color: white;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .modern-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--main-color) 0%, #ffd700 50%, var(--main-color) 100%);
        }

        .footer-content {
            padding: 2rem 2rem 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr;
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .brand-section .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
        }

        .brand-section .footer-logo img {
            width: 35px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .brand-section .footer-logo h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin: 0;
        }

        .brand-section .footer-logo h3 span {
            color: #ffd700;
        }

        .footer-description {
            font-size: 1.2rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
        }

        .social-links {
            display: flex;
            gap: 0.8rem;
        }

        .social-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.3rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .social-icon:hover {
            background: var(--main-color);
            border-color: #ffd700;
            transform: translateY(-5px) rotate(360deg);
        }

        .footer-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffd700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 0.5rem;
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

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.6rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.3rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .footer-links a i {
            color: var(--main-color);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-links a:hover i {
            color: #ffd700;
        }

        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-contact li {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            line-height: 1.5;
        }

        .footer-contact li i {
            color: var(--main-color);
            font-size: 1.3rem;
            min-width: 16px;
            margin-top: 0.2rem;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom p {
            margin: 0;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-bottom span {
            color: #ffd700;
            font-weight: 600;
        }

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
    </style>
</head>
<body>

{{-- SweetAlert2 messages --}}

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
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('orders') }}">Orders</a>
            <a href="{{ route('contact') }}">Contact</a>
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
            <div class="profile-card">
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
            </div>
        </div>
    </section>
</header>

{{-- Registration Form --}}
<section class="form-container">
    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <h3>Create Account</h3>
        <p class="form-header-subtitle">Join U-KAY HUB and start shopping</p>
        
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input 
                type="text" 
                name="name" 
                required 
                placeholder="Full name" 
                maxlength="20" 
                class="box"
                value="{{ old('name') }}"
            >
        </div>
        
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input 
                type="email" 
                name="email" 
                required 
                placeholder="Email address" 
                maxlength="50" 
                class="box" 
                oninput="this.value = this.value.replace(/\s/g, '')"
                value="{{ old('email') }}"
            >
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input 
                type="password" 
                name="pass" 
                required 
                placeholder="Password" 
                maxlength="20" 
                class="box" 
                id="register-password"
                oninput="this.value = this.value.replace(/\s/g, '')"
            >
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input 
                type="password" 
                name="cpass" 
                required 
                placeholder="Confirm password" 
                maxlength="20" 
                class="box" 
                id="register-cpassword"
                oninput="this.value = this.value.replace(/\s/g, '')"
            >
        </div>
        
        <input type="submit" value="Create Account" class="btn" name="submit">
        
        <div class="form-divider">
            <span>OR</span>
        </div>
        
        <p>Already have an account?</p>
        <a href="{{ route('login') }}" class="option-btn">Sign In</a>
    </form>
</section>

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

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            timer: 2200,
            showConfirmButton: false
        });
    @endif
</script>

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

</body>
</html>
