@extends('layouts.app')

@section('title', 'My Profile - U-KAY HUB')

@push('styles')
<style>
    .profile-section {
        padding: 3rem 2rem;
        max-width: 800px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .profile-section .heading {
        font-size: 3rem;
        color: #27ae60;
        text-align: center;
        margin-bottom: 3rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 2px solid #27ae60;
    }

    .profile-card h2 {
        font-size: 2rem;
        color: #27ae60;
        margin-bottom: 2rem;
        border-bottom: 2px solid #27ae60;
        padding-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .form-group label span {
        color: #e74c3c;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 1.2rem;
        font-size: 1.4rem;
        border: 2px solid #ddd;
        border-radius: 8px;
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
        min-height: 100px;
    }

    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid #eee;
    }

    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .update-btn {
        width: 100%;
        padding: 1.5rem;
        font-size: 1.6rem;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
    }

    .update-btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        transform: translateY(-2px);
    }

    .info-text {
        font-size: 1.3rem;
        color: #666;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .password-hint {
        font-size: 1.2rem;
        color: #999;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .profile-section {
            padding: 2rem 1rem;
        }

        .profile-card {
            padding: 2rem;
        }

        .profile-section .heading {
            font-size: 2.5rem;
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
                <h2>Basic Information</h2>

                <div class="form-group">
                    <label for="name">Full Name <span>*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span>*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Contact & Address Information -->
            <div class="form-section">
                <h2>Contact & Address</h2>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g., 09123456789">
                    <p class="info-text">Your phone number will be used for order confirmations and delivery updates.</p>
                    @error('phone')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Complete Address</label>
                    <textarea id="address" name="address" placeholder="House/Unit No., Street, Barangay, City, Province">{{ old('address', $user->address) }}</textarea>
                    <p class="info-text">Providing your address helps speed up the checkout process.</p>
                    @error('address')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Change Password (Optional) -->
            <div class="form-section">
                <h2>Change Password (Optional)</h2>
                <p class="info-text">Leave blank if you don't want to change your password.</p>

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter your current password">
                    @error('current_password')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password (min. 6 characters)">
                    <p class="password-hint">Must be at least 6 characters long</p>
                    @error('new_password')
                        <span class="info-text" style="color: #e74c3c;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm your new password">
                </div>
            </div>
            <button type="submit" class="update-btn">Update Profile</button>
        </form>
    </div>

    {{-- Seller section removed: sellers now have a dedicated seller/login page --}}
</section>
@endsection
