@extends('layouts.admin')

@section('title', 'Branding Settings - Admin Panel')

@push('styles')
<style>
    .settings-wrap {
        background: var(--admin-panel-bg, #fff);
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: 10px;
        box-shadow: var(--admin-shadow, 0 1px 2px rgba(17, 24, 39, 0.06));
        padding: 1.6rem;
        max-width: 900px;
    }

    .settings-title {
        margin: 0 0 0.4rem 0;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--admin-text, #111827);
    }

    .settings-subtitle {
        margin: 0 0 1.6rem 0;
        font-size: 1.3rem;
        color: #6b7280;
    }

    .logo-card {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 1.6rem;
        align-items: start;
        background: #fff;
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: 10px;
        padding: 1.4rem;
    }

    .logo-preview {
        border: 1px dashed var(--admin-border, #e5e7eb);
        border-radius: 10px;
        background: #f9fafb;
        padding: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 160px;
    }

    .logo-preview img {
        max-width: 100%;
        max-height: 120px;
        object-fit: contain;
    }

    .form-row label {
        display: block;
        font-size: 1.2rem;
        color: #374151;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .form-row input[type="file"] {
        display: block;
        width: 100%;
        font-size: 1.3rem;
        background: #fff;
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: 8px;
        padding: 0.8rem;
    }

    .actions {
        margin-top: 1.2rem;
        display: flex;
        gap: 0.8rem;
        align-items: center;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.9rem 1.2rem;
        border-radius: 8px;
        border: 1px solid #111827;
        background: #111827;
        color: #fff;
        cursor: pointer;
        font-size: 1.3rem;
        font-weight: 600;
        text-decoration: none;
    }

    .help {
        font-size: 1.2rem;
        color: #6b7280;
        margin-top: 0.8rem;
        line-height: 1.4;
    }

    @media (max-width: 768px) {
        .logo-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Settings</h1>

<div class="settings-wrap">
    <h2 class="settings-title">Branding</h2>
    <p class="settings-subtitle">Update the logo used across the website (favicon + headers).</p>

    <div class="logo-card">
        <div class="logo-preview">
            <img src="{{ $siteLogoUrl ?? asset('images/logo.png') }}" alt="Current site logo">
        </div>

        <div>
            <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <label for="site_logo">Upload new logo</label>
                    <input id="site_logo" type="file" name="site_logo" accept="image/*" required>
                    <div class="help">Recommended: square image, PNG/WebP, max 2MB.</div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-upload"></i>
                        Save Logo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

