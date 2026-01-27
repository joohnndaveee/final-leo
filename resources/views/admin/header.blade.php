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

<header class="header">
    <section class="flex">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            <span class="text">Admin<span>Panel</span></span>
        </a>

        <nav class="navbar">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <a href="{{ route('admin.orders') }}">Orders</a>
            <a href="{{ route('admin.messages') }}">Messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <p>{{ $admin->name ?? 'Admin' }}</p>
            <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Logout from the website?');">
                @csrf
                <button type="submit" class="delete-btn">logout</button>
            </form>
        </div>
    </section>
</header>
