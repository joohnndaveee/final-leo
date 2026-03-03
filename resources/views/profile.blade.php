@extends('layouts.app')

@section('title', 'My Profile - U-KAY HUB')

@push('styles')
<style>
    .profile-section {
        padding: 2rem 1.2rem;
        max-width: 740px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .profile-section .heading {
        font-size: 2.4rem;
        color: #27ae60;
        text-align: center;
        margin-bottom: 1.8rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid #b7e8ca;
        transition: transform .35s ease, box-shadow .35s ease;
    }

    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.12);
    }

    .profile-card h2 {
        font-size: 1.65rem;
        color: #27ae60;
        margin-bottom: 1.2rem;
        border-bottom: 1px solid #d1fae5;
        padding-bottom: .7rem;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: .55rem;
        font-size: 1.25rem;
        color: #333;
        margin-bottom: 0.55rem;
        font-weight: 600;
    }

    .form-group label i {
        color: #27ae60;
        font-size: 1.1rem;
    }

    .form-group label span {
        color: #e74c3c;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: .95rem 1rem;
        font-size: 1.3rem;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #27ae60;
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 88px;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }

    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .update-btn {
        width: 100%;
        padding: 1.15rem;
        font-size: 1.35rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-top: 1.2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
    }

    .update-btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        transform: translateY(-2px);
    }

    .info-text {
        font-size: 1.15rem;
        color: #666;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .password-hint {
        font-size: 1.1rem;
        color: #999;
        margin-top: 0.5rem;
    }

    /* Scroll reveal animations */
    .profile-reveal {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity .45s cubic-bezier(.2,.65,.2,1), transform .45s cubic-bezier(.2,.65,.2,1);
        will-change: opacity, transform;
    }

    .profile-reveal.in-view {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .profile-reveal {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }

    @media (max-width: 768px) {
        .profile-section {
            padding: 1.2rem .8rem;
        }

        .profile-card {
            padding: 1.4rem;
        }

        .profile-section .heading {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<section class="profile-section">
    <h1 class="heading">My Profile</h1>

    <div class="profile-card">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <h2><i class="fas fa-id-card"></i> Basic Information</h2>

                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name <span>*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address <span>*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Contact & Address Information -->
            <div class="form-section">
                <h2><i class="fas fa-location-dot"></i> Contact & Address</h2>

                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g., 09123456789">
                    <p class="info-text">Your phone number will be used for order confirmations and delivery updates.</p>
                    @error('phone')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Complete Address</label>
                    <textarea id="address" name="address" placeholder="House/Unit No., Street, Barangay, City, Province">{{ old('address', $user->address) }}</textarea>
                    <p class="info-text">Providing your address helps speed up the checkout process.</p>
                    @error('address')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Change Password (Optional) -->
            <div class="form-section">
                <h2><i class="fas fa-lock"></i> Change Password (Optional)</h2>
                <p class="info-text">Leave blank if you don't want to change your password.</p>

                <div class="form-group">
                    <label for="current_password"><i class="fas fa-key"></i> Current Password</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter your current password">
                    @error('current_password')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password"><i class="fas fa-lock-open"></i> New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password (min. 6 characters)">
                    <p class="password-hint">Must be at least 6 characters long</p>
                    @error('new_password')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation"><i class="fas fa-shield-halved"></i> Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm your new password">
                </div>
            </div>
            <button type="submit" class="update-btn"><i class="fas fa-floppy-disk"></i> Update Profile</button>
        </form>
    </div>

    {{-- Seller section removed: sellers now have a dedicated seller/login page --}}
</section>
@endsection

@push('scripts')
<script>
(function () {
    const targets = [
        document.querySelector('.profile-section .heading'),
        document.querySelector('.profile-card'),
        ...document.querySelectorAll('.profile-card .form-section'),
        document.querySelector('.update-btn')
    ].filter(Boolean);

    targets.forEach((el, index) => {
        el.classList.add('profile-reveal');
        el.style.transitionDelay = `${Math.min(index * 70, 320)}ms`;
    });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('in-view');
            obs.unobserve(entry.target);
        });
    }, {
        threshold: 0.16,
        rootMargin: '0px 0px -8% 0px'
    });

    targets.forEach((el) => observer.observe(el));
})();
</script>
@endpush
