@extends('layouts.seller')

@section('title', 'Seller Products')

@push('styles')
<style>
    .seller-products-page {
        max-width: 1100px;
        margin: 2rem auto 3rem;
    }

    .seller-products-title {
        font-size: 2.4rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #064e3b;
    }

    .seller-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        padding: 1.6rem 2rem;
        margin-bottom: 1.6rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .seller-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.8rem;
    }

    .seller-card-header h3 {
        font-size: 1.9rem;
        font-weight: 700;
        margin: 0;
        color: #064e3b;
    }

    .seller-card-subtitle {
        font-size: 1.3rem;
        color: #6b7280;
        margin-top: 0.3rem;
    }

    .seller-toggle-btn {
        padding: 0.5rem 0.9rem;
        border-radius: 12px;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        font-size: 1.25rem;
        color: #374151;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
    }

    .seller-toggle-btn:hover {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
        color: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .seller-toggle-btn i {
        font-size: 1.3rem;
        transition: transform 0.2s ease;
    }

    .collapsible-body {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.25s ease, opacity 0.25s ease;
    }

    .collapsible-body.expanded {
        max-height: 1000px;
        opacity: 1;
    }

    .seller-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.2rem 1.6rem;
    }

    .seller-form-group label {
        display: block;
        font-size: 1.3rem;
        color: #374151;
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .seller-form-group input[type="text"],
    .seller-form-group input[type="number"],
    .seller-form-group textarea,
    .seller-form-group input[type="file"] {
        width: 100%;
        padding: 0.85rem 0.9rem;
        border-radius: 0.6rem;
        border: 1px solid #d1d5db;
        font-size: 1.35rem;
        transition: all 0.2s ease;
    }

    .seller-form-group select {
        width: 100%;
        padding: 0.85rem 0.9rem;
        border-radius: 0.6rem;
        border: 1px solid #d1d5db;
        font-size: 1.35rem;
        transition: all 0.2s ease;
        background: #fff;
    }

    .seller-form-group input:focus,
    .seller-form-group textarea:focus {
        outline: none;
        border-color: rgba(16, 185, 129, 0.5);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background: rgba(255, 255, 255, 0.95);
    }

    .seller-form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .seller-primary-btn {
        padding: 0.9rem 1.8rem;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(5, 150, 105, 0.95));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #fff;
        font-size: 1.45rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    .seller-primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    }

    .seller-success-alert {
        background: rgba(236, 253, 245, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #065f46;
        padding: 0.85rem 1.1rem;
        border-radius: 12px;
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.1);
    }

    .seller-error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 0.85rem 1.1rem;
        border-radius: 0.7rem;
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
    }

    .seller-products-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1.35rem;
    }

    .seller-products-table thead {
        background: rgba(240, 253, 244, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #065f46;
    }

    .seller-products-table th,
    .seller-products-table td {
        padding: 0.9rem 0.8rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .seller-products-table tbody tr:hover {
        background: rgba(240, 253, 244, 0.4);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .seller-thumb-strip {
        display: flex;
        gap: 0.4rem;
        align-items: center;
    }

    .seller-thumb-strip img {
        width: 42px;
        height: 42px;
        border-radius: 0.4rem;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .seller-table-actions a {
        font-size: 1.3rem;
        text-decoration: none;
        margin-right: 0.6rem;
    }

    .seller-table-actions a:first-child {
        color: #10b981;
        font-weight: 600;
    }

    .seller-table-actions a:first-child:hover {
        color: #059669;
    }

    .seller-table-actions button {
        font-size: 1.3rem;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .seller-card {
            padding: 1.6rem 1.6rem 1.8rem;
        }
    }
</style>
@endpush

@section('content')
<section class="seller-products-page">
    <h1 class="seller-products-title">Your Products</h1>

    <div class="seller-card">
        <div class="seller-card-header">
            <div>
                <h3>Add Product</h3>
                <p class="seller-card-subtitle">Upload your product details and images. The main image will be shown first to buyers.</p>
            </div>
            <button type="button" class="seller-toggle-btn" id="toggleAddProduct" title="Show form">
                <i id="toggleAddProductIcon" class="fas fa-chevron-down"></i>
            </button>
        </div>

        @if($errors->any())
            @php
                $missing = [];
                if($errors->has('name')) $missing[] = 'Name';
                if($errors->has('category_id')) $missing[] = 'Category';
                if($errors->has('price')) $missing[] = 'Price';
                if($errors->has('stock')) $missing[] = 'Stock';
                if($errors->has('pieces')) $missing[] = 'Pieces';
                if($errors->has('details')) $missing[] = 'Details';
                if($errors->has('image_01')) $missing[] = 'Main Image';
                if(empty($missing)) {
                    $message = $errors->first();
                } else {
                    $message = implode(', ', $missing) . ' are required.';
                }
            @endphp
            <div class="seller-error-alert">
                <strong>Cannot add product.</strong>
                <span style="margin-left:0.5rem;">{{ $message }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="seller-success-alert">
                {{ session('success') }}
            </div>
        @endif

        <div id="addProductBody" class="collapsible-body">
            <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="seller-form-grid">
                    <div class="seller-form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="seller-form-group">
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="">Select category…</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="seller-form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
                    </div>
                    <div class="seller-form-group">
                        <label>Sale Price (optional)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="Leave blank for none">
                    </div>
                    <div class="seller-form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" value="{{ old('stock') }}" required>
                    </div>
                    <div class="seller-form-group">
                        <label>Pieces (bundle count)</label>
                        <input type="number" name="pieces" min="1" value="{{ old('pieces', 1) }}" required>
                    </div>
                </div>

                <div class="seller-form-grid" style="margin-top:1.2rem;">
                    <div class="seller-form-group">
                        <label>Main Image <span style="color:#dc2626;">(required)</span></label>
                        <input type="file" name="image_01" accept="image/*" required>
                    </div>
                </div>

                <div class="seller-form-group" style="margin-top:1.2rem;">
                    <label>Details</label>
                    <textarea name="details" rows="2" placeholder="Short description that buyers will see." required>{{ old('details') }}</textarea>
                </div>

                <div style="margin-top:1.4rem;">
                    <button type="submit" class="seller-primary-btn">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <div class="seller-card">
        <div class="seller-card-header">
            <div>
                <h3>Your Listings</h3>
                <p class="seller-card-subtitle">Manage your existing products. Thumbnails show what buyers see in the shop.</p>
            </div>
        </div>

        <table class="seller-products-table">
            <thead>
                <tr>
                    <th>Images</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="seller-thumb-strip">
                                @if($product->image_01)
                                    <img src="{{ asset('uploaded_img/' . $product->image_01) }}" alt="">
                                @endif
                            </div>
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td class="seller-table-actions">
                            <a href="{{ route('seller.products.edit', $product->id) }}">Edit</a>
                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete product?')" style="border:none;background:none;cursor:pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:0.9rem 0.8rem;color:#6b7280;">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:1.2rem;">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        const toggleBtn = document.getElementById('toggleAddProduct');
        const body = document.getElementById('addProductBody');
        const icon = document.getElementById('toggleAddProductIcon');
        if (!toggleBtn || !body || !icon) return;

        // Start collapsed by default
        body.classList.remove('expanded');

        toggleBtn.addEventListener('click', () => {
            const isExpanded = body.classList.contains('expanded');
            if (isExpanded) {
                body.classList.remove('expanded');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                toggleBtn.title = 'Show form';
            } else {
                body.classList.add('expanded');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                toggleBtn.title = 'Hide form';
            }
        });
    })();
</script>
@endpush
