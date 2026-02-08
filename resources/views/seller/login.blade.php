<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login - U-KAY HUB</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: url('{{ asset('images/bg.png') }}') center center fixed;
            background-size: cover;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .seller-login-header {
            background: rgba(26, 48, 9, 0.96);
            color: #fff;
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .seller-login-header .brand {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .seller-login-header img {
            width: 34px;
            height: 34px;
        }

        .seller-login-header .brand-text {
            display: flex;
            flex-direction: column;
        }

        .seller-login-header .brand-text span:first-child {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .seller-login-header .brand-text span:last-child {
            font-size: 1.2rem;
            opacity: 0.85;
        }

        .seller-login-header a {
            color: #fff;
            text-decoration: none;
            font-size: 1.3rem;
        }

        .seller-login-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .seller-alert-wrapper,
        .seller-login-card {
            width: 100%;
            max-width: 420px;
        }

        .seller-login-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 1.2rem;
            padding: 2.5rem 2.5rem 2.8rem;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }

        .seller-login-card h1 {
            font-size: 2.1rem;
            margin-bottom: 0.3rem;
            color: #111827;
        }

        .seller-login-card p {
            font-size: 1.3rem;
            color: #6b7280;
            margin-bottom: 1.8rem;
        }

        .seller-login-card label {
            display: block;
            font-size: 1.3rem;
            color: #374151;
            margin-bottom: 0.4rem;
            font-weight: 500;
        }

        .seller-login-card input {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: 0.7rem;
            border: 1px solid #d1d5db;
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
        }

        .seller-login-card button {
            width: 100%;
            padding: 1rem;
            border-radius: 0.8rem;
            border: none;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .seller-login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 1.2rem;
            color: #6b7280;
        }

        .seller-login-footer a {
            color: #16a34a;
            text-decoration: none;
            font-weight: 600;
        }

        .seller-alert-wrapper {
            margin-bottom: 1.2rem;
        }

        .seller-alert {
            padding: 0.9rem 1.1rem;
            border-radius: 0.8rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            border-width: 1px;
            border-style: solid;
        }

        .seller-alert i {
            font-size: 1.4rem;
        }

        .seller-alert.pending {
            background: #ecfdf3;
            color: #166534;
            border-color: #bbf7d0;
        }

        .seller-alert.rejected {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .seller-alert.info {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .seller-alert.error {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }
    </style>
</head>
<body>
<header class="seller-login-header">
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="U-KAY HUB Logo">
        <div class="brand-text">
            <span>SELLER CENTER</span>
            <span>U-KAY HUB</span>
        </div>
    </div>
    <a href="{{ route('home') }}">← Back to shop</a>
</header>

<main class="seller-login-container">
    @if ($errors->has('login') || $errors->has('email') || $errors->has('pass') || session('success'))
        <div class="seller-alert-wrapper">
            @if ($errors->has('login') || $errors->has('email') || $errors->has('pass'))
                <div class="seller-alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first('login') ?? $errors->first('email') ?? $errors->first('pass') }}</span>
                </div>
            @endif

            @if (session('success'))
                @php $status = session('seller_status'); @endphp
                <div class="seller-alert {{ $status ?? 'info' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
        </div>
    @endif

    <div class="seller-login-card">
        <h1>Seller Login</h1>
        <p>Log in to manage your products, orders, and shop performance.</p>

        <form action="{{ route('seller.login.post') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                maxlength="50"
                oninput="this.value = this.value.replace(/\s/g, '')"
            >

            <label for="pass">Password</label>
            <input
                type="password"
                id="pass"
                name="pass"
                required
                maxlength="20"
                oninput="this.value = this.value.replace(/\s/g, '')"
            >

            <button type="submit">Login as Seller</button>
        </form>

        <div class="seller-login-footer">
            Need a buyer account instead?
            <a href="{{ route('login') }}">Go to user login</a>
        </div>
    </div>
</main>

</body>
</html>

