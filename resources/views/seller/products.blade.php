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
    }

    .seller-card {
        background: #ffffff;
        border-radius: 0.6rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 1.6rem 2rem;
        margin-bottom: 1.6rem;
        border: 1px solid #e5e7eb;
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
    }

    .seller-card-subtitle {
        font-size: 1.3rem;
        color: #6b7280;
        margin-top: 0.3rem;
    }

    .seller-toggle-btn {
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        border: 1px solid #d1d5db;
        background: #f9fafb;
        font-size: 1.25rem;
        color: #374151;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
    }

    .seller-form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .seller-primary-btn {
        padding: 0.9rem 1.8rem;
        border-radius: 0.7rem;
        border: none;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        font-size: 1.45rem;
        font-weight: 600;
        cursor: pointer;
    }

    .seller-success-alert {
        background: #ecfdf3;
        border: 1px solid #bbf7d0;
        color: #166534;
        padding: 0.85rem 1.1rem;
        border-radius: 0.7rem;
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
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
        background: #f9fafb;
    }

    .seller-products-table th,
    .seller-products-table td {
        padding: 0.9rem 0.8rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .seller-products-table tbody tr:hover {
        background: #f9fafb;
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
        color: #2563eb;
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
                if($errors->has('type')) $missing[] = 'Type';
                if($errors->has('price')) $missing[] = 'Price';
                if($errors->has('stock')) $missing[] = 'Stock';
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
                        <label>Type</label>
                        <input type="text" name="type" value="{{ old('type') }}" placeholder="e.g. T-Shirt, Jacket" required>
                    </div>
                    <div class="seller-form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
                    </div>
                    <div class="seller-form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" value="{{ old('stock') }}" required>
                    </div>
                </div>

                <div class="seller-form-grid" style="margin-top:1.2rem;">
                    <div class="seller-form-group">
                        <label>Main Image <span style="color:#dc2626;">(required)</span></label>
                        <input type="file" name="image_01" accept="image/*" required>
                    </div>
                    <div class="seller-form-group">
                        <label>Image 2 (optional)</label>
                        <input type="file" name="image_02" accept="image/*">
                    </div>
                    <div class="seller-form-group">
                        <label>Image 3 (optional)</label>
                        <input type="file" name="image_03" accept="image/*">
                    </div>
                </div>

                <div class="seller-form-group" style="margin-top:1.2rem;">
                    <label>Details</label>
                    <textarea name="details" rows="2" placeholder="Short description that buyers will see.">{{ old('details') }}</textarea>
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
                                @if($product->image_02)
                                    <img src="{{ asset('uploaded_img/' . $product->image_02) }}" alt="">
                                @endif
                                @if($product->image_03)
                                    <img src="{{ asset('uploaded_img/' . $product->image_03) }}" alt="">
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
