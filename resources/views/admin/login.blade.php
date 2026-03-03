<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $siteLogoUrl ?? asset('images/logo.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #eceff1;
            --card: #f3f4f6;
            --ink: #172036;
            --muted: #73849d;
            --brand: #2fbf34;
            --brand-dark: #26a031;
            --input-bg: #cdd6e2;
            --input-border: #c5cfdb;
            --ring: rgba(47, 191, 52, 0.24);
            --danger-bg: #fee2e2;
            --danger-text: #b91c1c;
            --success-bg: #dcfce7;
            --success-text: #166534;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 18% 28%, rgba(157, 194, 169, 0.38), transparent 42%),
                radial-gradient(circle at 86% 38%, rgba(181, 194, 214, 0.34), transparent 48%),
                linear-gradient(180deg, #f0f2f4 0%, var(--bg-main) 100%);
            display: grid;
            place-items: center;
            padding: 24px;
            overflow-x: hidden;
            position: relative;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 999px;
            filter: blur(6px);
            z-index: 0;
            animation: float 8s ease-in-out infinite;
        }

        body::before {
            width: 380px;
            height: 380px;
            top: -140px;
            left: -120px;
            background: rgba(168, 201, 177, 0.28);
        }

        body::after {
            width: 340px;
            height: 340px;
            bottom: -100px;
            right: -100px;
            background: rgba(177, 206, 190, 0.26);
            animation-delay: 1.8s;
        }

        .auth-wrap {
            width: min(430px, 100%);
            border-radius: 20px;
            background: var(--card);
            border: 1px solid #e3e7ec;
            box-shadow: 0 24px 45px rgba(16, 24, 40, 0.12);
            position: relative;
            z-index: 1;
            padding: 40px 34px 38px;
        }

        .form-shell {
            width: 100%;
        }

        .title {
            font-size: clamp(2rem, 3vw, 2.35rem);
            font-weight: 800;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
            text-transform: uppercase;
            text-align: center;
        }

        .subtitle {
            color: var(--muted);
            margin-bottom: 20px;
            font-size: 0.93rem;
            text-align: center;
        }

        .message {
            margin-bottom: 12px;
            border-radius: 12px;
            padding: 11px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 0.93rem;
            line-height: 1.35;
            animation: appear 0.25s ease-out;
        }

        .message.error {
            background: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid #fecaca;
        }

        .message.success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #bbf7d0;
        }

        .message i {
            cursor: pointer;
            opacity: 0.75;
        }

        .field {
            margin-bottom: 16px;
            position: relative;
        }

        .box {
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--input-border);
            background: var(--input-bg);
            padding: 14px 14px 14px 46px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
        }

        .box:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px var(--ring);
            transform: translateY(-1px);
        }

        .field-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--brand);
            font-size: 1.08rem;
            pointer-events: none;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--brand), #289b48);
            color: #fff;
            font-size: 2.1rem;
            font-weight: 700;
            letter-spacing: 0.25px;
            padding: 14px;
            line-height: 1;
            cursor: pointer;
            margin-top: 6px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            text-transform: uppercase;
        }

        .btn:hover {
            transform: translateY(-1px);
            background: linear-gradient(90deg, var(--brand-dark), var(--brand));
            box-shadow: 0 10px 24px rgba(47, 191, 52, 0.24);
        }

        .btn:active {
            transform: translateY(0);
        }

        @keyframes appear {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @media (max-width: 560px) {
            .auth-wrap {
                padding: 28px 20px 26px;
                border-radius: 18px;
            }

            .title {
                font-size: 1.8rem;
            }

            .btn {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

<section class="auth-wrap">
    <div class="form-shell">
        <h2 class="title">Welcome Back</h2>
        <p class="subtitle">Sign in to continue to U-KAY HUB</p>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="message error">
                    <span>{{ $error }}</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
            @endforeach
        @endif

        @if (session('success'))
            <div class="message success">
                <span>{{ session('success') }}</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="field">
                <i class="fa-solid fa-envelope field-icon"></i>
                <input id="name"
                       type="text"
                       name="name"
                       required
                       placeholder="Enter your username"
                       maxlength="20"
                       class="box"
                       oninput="this.value = this.value.replace(/\s/g, '')"
                       value="{{ old('name') }}">
            </div>

            <div class="field">
                <i class="fa-solid fa-lock field-icon"></i>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       placeholder="Enter your password"
                       maxlength="20"
                       class="box"
                       oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <input type="submit" value="Sign In" class="btn" name="submit">
        </form>
    </div>
</section>
   
</body>
</html>
