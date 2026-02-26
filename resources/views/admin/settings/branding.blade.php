@extends('layouts.admin')

@section('title', 'Branding Settings - Admin Panel')

@push('styles')
<style>
    .dashboard-content {
        padding: 2rem 2.2rem;
    }

    .heading {
        margin-bottom: 1.2rem;
        font-size: 2.4rem !important;
    }

    .branding-shell {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto 0 0;
    }

    .branding-head {
        margin: 0 0 1.2rem;
        padding-bottom: 0.9rem;
        border-bottom: 1px solid var(--admin-border, #e5e7eb);
    }

    .branding-title {
        margin: 0;
        font-size: 2.2rem;
        line-height: 1.2;
        font-weight: 700;
        color: var(--admin-text, #111827);
        letter-spacing: -0.01em;
    }

    .branding-lead {
        margin: 0.45rem 0 0;
        font-size: 1.18rem;
        color: var(--admin-muted, #6b7280);
    }

    .branding-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .branding-card {
        background: var(--admin-panel-bg, #fff);
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: var(--admin-radius, 10px);
        box-shadow: var(--admin-shadow, 0 1px 2px rgba(17, 24, 39, 0.06));
        padding: 1.3rem;
    }

    .branding-card-head {
        margin-bottom: 1rem;
    }

    .branding-card-title {
        margin: 0;
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--admin-text, #111827);
    }

    .branding-card-subtitle {
        margin: 0.35rem 0 0;
        color: var(--admin-muted, #6b7280);
        font-size: 1.12rem;
    }

    .branding-body {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 1rem;
        align-items: center;
    }

    .media-preview {
        border: 1px dashed var(--admin-border, #e5e7eb);
        border-radius: var(--admin-radius-sm, 8px);
        background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        min-height: 128px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        overflow: hidden;
    }

    .media-preview.logo img {
        width: 100%;
        max-width: 110px;
        max-height: 110px;
        object-fit: contain;
    }

    .media-preview.hero {
        padding: 0;
        min-height: 105px;
        aspect-ratio: 16 / 9;
        background: #0f172a;
    }

    .media-preview.hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-placeholder {
        color: #94a3b8;
        font-size: 2.6rem;
    }

    .form-stack {
        display: grid;
        gap: 0.7rem;
    }

    .file-label {
        display: inline-block;
        margin: 0;
        font-size: 1.12rem;
        font-weight: 600;
        color: #334155;
    }

    .file-input {
        width: 100%;
        padding: 0.65rem;
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: var(--admin-radius-sm, 8px);
        font-size: 1.12rem;
        background: #fff;
        color: var(--admin-text, #111827);
    }

    .file-input:focus {
        outline: none;
        border-color: rgba(22, 101, 52, 0.45);
        box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.12);
    }

    .help {
        margin: 0;
        font-size: 1.04rem;
        line-height: 1.45;
        color: var(--admin-muted, #6b7280);
    }

    .actions {
        margin-top: 0.25rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        border: 1px solid #166534;
        border-radius: 8px;
        background: linear-gradient(135deg, #166534, #15803d);
        color: #fff;
        padding: 0.72rem 1rem;
        font-size: 1.08rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(22, 101, 52, 0.2);
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(22, 101, 52, 0.28);
    }

    .live-preview {
        margin-top: 0.5rem;
        display: none;
        width: min(220px, 100%);
        border: 1px solid var(--admin-border, #e5e7eb);
        border-radius: 8px;
        overflow: hidden;
        background: #0f172a;
    }

    .live-preview img {
        width: 100%;
        height: auto;
        max-height: 120px;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 900px) {
        .dashboard-content {
            padding: 1.6rem;
        }

        .branding-body {
            grid-template-columns: 1fr;
            align-items: start;
        }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Settings</h1>
<section class="branding-shell">
    <header class="branding-head">
        <h2 class="branding-title">Branding Settings</h2>
        <p class="branding-lead">Update your storefront logo and homepage hero media while keeping your current admin theme.</p>
    </header>

    <div class="branding-grid">
        <article class="branding-card">
            <div class="branding-card-head">
                <h3 class="branding-card-title">Site Logo</h3>
                <p class="branding-card-subtitle">Used in the favicon and header across the site.</p>
            </div>

            <div class="branding-body">
                <div class="media-preview logo">
                    <img src="{{ $siteLogoUrl ?? asset('images/logo.png') }}" alt="Current site logo">
                </div>

                <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data" class="form-stack">
                    @csrf
                    <label for="site_logo" class="file-label">Upload new logo</label>
                    <input id="site_logo" type="file" name="site_logo" class="file-input" accept="image/*" required>
                    <p class="help">Recommended: square PNG/WebP, up to 2MB.</p>

                    <div class="actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-upload"></i>
                            Save Logo
                        </button>
                    </div>
                </form>
            </div>
        </article>

        <article class="branding-card">
            <div class="branding-card-head">
                <h3 class="branding-card-title">Home Page Hero Background</h3>
                <p class="branding-card-subtitle">Displayed behind the homepage hero section. Use a wide image for best quality.</p>
            </div>

            <div class="branding-body">
                <div class="media-preview hero">
                    @if(!empty($heroBgUrl))
                        <img src="{{ $heroBgUrl }}" alt="Current hero background">
                    @else
                        <div class="media-placeholder" aria-hidden="true"><i class="fas fa-image"></i></div>
                    @endif
                </div>

                <form action="{{ route('admin.settings.hero_bg.update') }}" method="POST" enctype="multipart/form-data" class="form-stack">
                    @csrf
                    <label for="hero_bg" class="file-label">Upload hero background image</label>
                    <input id="hero_bg" type="file" name="hero_bg" class="file-input" accept="image/*" required>
                    <p class="help">JPG, PNG or WebP, up to 5MB. Ideal size: 1920x1080 or wider.</p>

                    <div id="hero-live-preview" class="live-preview">
                        <img id="hero-preview-image" src="" alt="New hero image preview">
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-upload"></i>
                            Save Hero Background
                        </button>
                    </div>
                </form>
            </div>
        </article>
    </div>
</section>

@endsection

@push('scripts')
<script>
    const heroInput = document.getElementById('hero_bg');
    const heroPreviewWrap = document.getElementById('hero-live-preview');
    const heroPreviewImage = document.getElementById('hero-preview-image');

    if (heroInput && heroPreviewWrap && heroPreviewImage) {
        heroInput.addEventListener('change', function () {
            const [file] = this.files || [];
            if (!file) {
                heroPreviewWrap.style.display = 'none';
                heroPreviewImage.removeAttribute('src');
                return;
            }

            heroPreviewImage.src = URL.createObjectURL(file);
            heroPreviewWrap.style.display = 'block';
        });
    }
</script>
@endpush
