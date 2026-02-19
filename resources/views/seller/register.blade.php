<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - U-KAY HUB</title>

    <link rel="icon" type="image/png" href="{{ $siteLogoUrl ?? asset('images/logo.png') }}">
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
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .seller-login-card {
            width: 100%;
            max-width: 520px;
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

        .seller-login-card input,
        .seller-login-card textarea {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: 0.7rem;
            border: 1px solid #d1d5db;
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
        }

        .seller-login-card textarea {
            min-height: 80px;
            resize: vertical;
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

        .message {
            margin: 1rem auto 0;
            max-width: 520px;
            background: #fef3c7;
            color: #92400e;
            padding: 0.8rem 1rem;
            border-radius: 0.8rem;
            font-size: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message i {
            cursor: pointer;
        }
    </style>
</head>
<body>

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

<header class="seller-login-header">
    <div class="brand">
        <img src="{{ $siteLogoUrl ?? asset('images/logo.png') }}" alt="U-KAY HUB Logo">
        <div class="brand-text">
            <span>SELLER CENTER</span>
            <span>U-KAY HUB</span>
        </div>
    </div>
    <a href="{{ route('home') }}">← Back to shop</a>
</header>

<main class="seller-login-container">
    <div class="seller-login-card">
        <h1>Become a Seller</h1>
        <p>Create your seller account, then submit your GCash payment proof for admin verification.</p>

        <form action="{{ route('seller.register.post') }}" method="POST" enctype="multipart/form-data" id="seller-register-form">
            @csrf

            <div id="step-1">
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

            <label for="shop_name">Shop Name</label>
            <input
                type="text"
                id="shop_name"
                name="shop_name"
                value="{{ old('shop_name') }}"
                required
                maxlength="255"
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

            <label for="cpass">Confirm Password</label>
            <input
                type="password"
                id="cpass"
                name="cpass"
                required
                maxlength="20"
                oninput="this.value = this.value.replace(/\s/g, '')"
            >

            <label for="gcash_number_used">GCash Number You Used</label>
            <input
                type="text"
                id="gcash_number_used"
                name="gcash_number_used"
                value="{{ old('gcash_number_used') }}"
                required
                maxlength="30"
                placeholder="09XXXXXXXXX"
            >

            <button type="button" id="proceed-to-payment">Proceed to Payment</button>
            </div>

            <div id="step-2" style="display:none; margin-top: 1.5rem;">
                <div style="padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.8rem; background: #f9fafb; margin-bottom: 1.2rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <div style="font-size: 1.4rem; font-weight: 700; color: #111827;">Registration Fee</div>
                            <div style="font-size: 1.3rem; color: #374151;">Amount: <strong>₱{{ number_format($registrationFee ?? 200, 2) }}</strong></div>
                            <div style="font-size: 1.2rem; color: #6b7280; margin-top: 0.3rem;">
                                Pay to: <strong>{{ $adminGcashName ?? 'Admin' }}</strong> ({{ $adminGcashNumber ?? '09xxxxxxxxx' }})
                            </div>
                            <div style="font-size: 1.2rem; color: #6b7280; margin-top: 0.3rem;">
                                Localhost testing: you may upload any image as proof.
                            </div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size: 1.2rem; color: #6b7280; margin-bottom: 0.6rem;">Scan this QR (optional)</div>
                            <img
                                src="{{ $adminGcashQrUrl ?? asset('images/gcash_qr.svg') }}"
                                alt="Admin GCash QR"
                                style="width: 180px; height: 180px; object-fit: contain; background: #fff; border-radius: 0.8rem; border: 1px solid #e5e7eb; padding: 0.6rem;"
                            >
                        </div>
                    </div>
                </div>

                <label for="reference_number">GCash Reference Number</label>
                <input
                    type="text"
                    id="reference_number"
                    name="reference_number"
                    value="{{ old('reference_number') }}"
                    required
                    maxlength="100"
                    placeholder="Enter the reference number from GCash"
                >

                <label for="payment_proof">Upload Proof of Payment (Screenshot/Image)</label>
                <input
                    type="file"
                    id="payment_proof"
                    name="payment_proof"
                    accept="image/*"
                    required
                >

                <button type="submit" style="margin-top: 1rem;">Submit Registration</button>

                <div class="seller-login-footer" style="margin-top: 1rem;">
                    <a href="#" id="back-to-step-1" style="font-size:1.2rem;">← Back</a>
                </div>
            </div>
        </form>

        <div class="seller-login-footer">
            Already have a seller account?
            <a href="{{ route('seller.login') }}">Login here</a>
        </div>
    </div>
</main>

<script>
    (function () {
        const proceedBtn = document.getElementById('proceed-to-payment');
        const backBtn = document.getElementById('back-to-step-1');
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');

        function isFilled(id) {
            const el = document.getElementById(id);
            return el && String(el.value || '').trim().length > 0;
        }

        if (proceedBtn) {
            proceedBtn.addEventListener('click', function () {
                const required = ['email', 'shop_name', 'pass', 'cpass', 'gcash_number_used'];
                const missing = required.filter((id) => !isFilled(id));
                if (missing.length > 0) {
                    alert('Please complete all required fields before proceeding to payment.');
                    return;
                }
                step1.style.display = 'none';
                step2.style.display = 'block';
            });
        }

        if (backBtn) {
            backBtn.addEventListener('click', function (e) {
                e.preventDefault();
                step2.style.display = 'none';
                step1.style.display = 'block';
            });
        }
    })();
</script>

</body>
</html>
