<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    
    <style>
        /* Modern Dashboard Layout */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
            padding-top: 0;
        }

        /* Sidebar Styles with Green/Gold Theme */
        .dashboard-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #2d5016 0%, #1a3009 100%);
            padding: 2rem;
            color: white;
            z-index: 999;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-sidebar .admin-profile {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
            padding-top: 2rem;
        }

        .dashboard-sidebar .admin-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .dashboard-sidebar .admin-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .dashboard-sidebar .admin-name {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .dashboard-sidebar .admin-role {
            font-size: 1.4rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .dashboard-sidebar .update-btn {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #1a3009;
            border: 2px solid rgba(255, 215, 0, 0.5);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-radius: 1rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 1.4rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .dashboard-sidebar .update-btn:hover {
            background: linear-gradient(135deg, #ffed4e 0%, #ffd700 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        .dashboard-sidebar .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dashboard-sidebar .nav-menu li {
            margin-bottom: 0.5rem;
        }

        .dashboard-sidebar .nav-menu a {
            display: flex;
            align-items: center;
            padding: 1.5rem 2rem;
            color: white;
            text-decoration: none;
            font-size: 1.6rem;
            font-weight: 500;
            border-radius: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0);
        }

        .dashboard-sidebar .nav-menu a:hover {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.2) 0%, transparent 100%);
            border-left: 4px solid #ffd700;
            padding-left: calc(2rem - 4px);
            transform: translateX(5px);
        }

        .dashboard-sidebar .nav-menu a.active {
            background: linear-gradient(90deg, rgba(76, 175, 80, 0.3) 0%, transparent 100%);
            border-left: 4px solid #4caf50;
            padding-left: calc(2rem - 4px);
        }

        .dashboard-sidebar .nav-menu a i {
            margin-right: 1.5rem;
            font-size: 1.8rem;
            width: 25px;
            text-align: center;
        }

        .dashboard-sidebar .nav-menu button.logout-link {
            background: none;
            border: none;
            color: white;
            width: 100%;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 1.5rem 2rem;
            font-size: 1.6rem;
            font-weight: 500;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .dashboard-sidebar .nav-menu button.logout-link:hover {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.2) 0%, transparent 100%);
            border-left: 4px solid #ffd700;
            padding-left: calc(2rem - 4px);
            transform: translateX(5px);
        }

        .dashboard-sidebar .nav-menu button.logout-link i {
            margin-right: 1.5rem;
            font-size: 1.8rem;
            width: 25px;
            text-align: center;
        }

        /* Main Content Area with Background Image */
        .dashboard-content {
            margin-left: 280px;
            flex: 1;
            padding: 4rem;
            background: url('{{ asset('images/bg.png') }}') center center;
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            position: relative;
        }

        .dashboard-content::before {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 280px;
            background: rgba(255, 255, 255, 0.85);
            z-index: 0;
            pointer-events: none;
        }

        .dashboard-content .heading {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 3rem;
            color: #1a3009;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(255, 255, 255, 0.5);
        }

        /* Glassmorphism Effect for Content */
        .dashboard-content > * {
            position: relative;
            z-index: 1;
        }

        /* Override card styles for glassmorphism */
        .modern-card,
        .stat-card {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15) !important;
        }

        .product-card,
        .empty-state {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15) !important;
        }

        .product-card:hover,
        .modern-card:hover {
            background: rgba(255, 255, 255, 0.9) !important;
        }

        /* Update gradient buttons to match green/gold theme */
        .gradient-btn,
        .btn-update {
            background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%) !important;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3) !important;
        }

        .gradient-btn:hover,
        .btn-update:hover {
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4) !important;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.85) !important;
        }

        .stat-card.pending {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.9) 0%, rgba(255, 193, 7, 0.9) 100%) !important;
        }

        .stat-card.completed {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.9) 0%, rgba(129, 199, 132, 0.9) 100%) !important;
        }

        .stat-card.orders {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.9) 0%, rgba(100, 181, 246, 0.9) 100%) !important;
        }

        .stat-card.products {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.9) 0%, rgba(255, 241, 118, 0.9) 100%) !important;
        }

        .stat-card.products h3,
        .stat-card.products p {
            color: #1a3009 !important;
        }

        .stat-card.users {
            background: linear-gradient(135deg, rgba(103, 58, 183, 0.9) 0%, rgba(149, 117, 205, 0.9) 100%) !important;
        }

        .stat-card.admins {
            background: linear-gradient(135deg, rgba(233, 30, 99, 0.9) 0%, rgba(244, 143, 177, 0.9) 100%) !important;
        }

        .stat-card.messages {
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.9) 0%, rgba(255, 138, 101, 0.9) 100%) !important;
        }

        .stat-card.chats {
            background: linear-gradient(135deg, rgba(52, 211, 153, 0.9) 0%, rgba(16, 185, 129, 0.9) 100%) !important;
        }

        .price-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%) !important;
            color: #1a3009 !important;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4) !important;
        }

        /* Message Styles with Glassmorphism */
        .message {
            position: sticky;
            top: 0;
            max-width: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            z-index: 1100;
            margin-bottom: 2rem;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .message span {
            font-size: 2rem;
            color: var(--black);
        }

        .message i {
            cursor: pointer;
            color: var(--red);
            font-size: 2.5rem;
        }

        .message i:hover {
            color: var(--black);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .dashboard-sidebar {
                width: 240px;
            }

            .dashboard-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .dashboard-content {
                margin-left: 0;
                padding: 2rem;
            }
        }

        /* Hide the default header on dashboard pages */
        .header {
            display: none;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="dashboard-layout">
    
    {{-- Sidebar --}}
    <aside class="dashboard-sidebar">
        {{-- Admin Profile Section --}}
        <div class="admin-profile">
            <div class="admin-avatar">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div class="admin-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</div>
            <div class="admin-role">Administrator</div>
        </div>

        {{-- Navigation Menu --}}
        <nav>
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.chats.index') }}" class="{{ request()->routeIs('admin.chats*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span>Live Chats</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Inquiries</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    {{-- Main Content Area --}}
    <main class="dashboard-content">
        
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

        {{-- Page Content --}}
        @yield('content')

    </main>

</div>

<script src="{{ asset('js/admin_script.js') }}"></script>
@stack('scripts')

</body>
</html>
