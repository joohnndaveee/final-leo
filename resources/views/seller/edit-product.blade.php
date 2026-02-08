@extends('layouts.seller')

@section('title', 'Edit Product')

@push('styles')
<style>
    .seller-edit-page {
        max-width: 950px;
        margin: 2rem auto 3rem;
    }

    .seller-edit-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 1.4rem;
    }

    .seller-edit-card {
        background: #ffffff;
        border-radius: 0.6rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 24px rgba(15,23,42,0.08);
        padding: 1.6rem 2rem 2rem;
    }

    .seller-edit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.2rem 1.6rem;
    }

    .seller-edit-group label {
        display: block;
        font-size: 1.3rem;
        color: #374151;
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .seller-edit-group input[type="text"],
    .seller-edit-group input[type="number"],
    .seller-edit-group textarea,
    .seller-edit-group input[type="file"] {
        width: 100%;
        padding: 0.8rem 0.9rem;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        font-size: 1.35rem;
    }

    .seller-edit-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .seller-edit-images {
        margin-top: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
        gap: 1.4rem;
    }

    .seller-edit-thumb {
        text-align: left;
    }

    .seller-edit-thumb img {
        width: 100%;
        max-width: 170px;
        height: 130px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .seller-edit-actions {
        margin-top: 1.2rem;
    }

    .seller-edit-primary-btn {
        padding: 0.85rem 1.6rem;
        border-radius: 0.7rem;
        border: none;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        font-size: 1.45rem;
        font-weight: 600;
        cursor: pointer;
    }

    .seller-edit-secondary-link {
        margin-left: 1rem;
        font-size: 1.3rem;
    }

    .seller-error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 0.85rem 1.1rem;
        border-radius: 0.7rem;
        font-size: 1.3rem;
        margin-bottom: 1.4rem;
    }
</style>
@endpush

@section('content')
<section class="seller-edit-page">
    <h1 class="seller-edit-title">Edit Product</h1>

    @if($errors->any())
        <div class="seller-error-alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="seller-edit-card">
        <form method="POST" action="{{ route('seller.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="seller-edit-grid">
                <div class="seller-edit-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="seller-edit-group">
                    <label>Type</label>
                    <input type="text" name="type" value="{{ old('type', $product->type) }}">
                </div>
                <div class="seller-edit-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="seller-edit-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required>
                </div>
            </div>

            <div class="seller-edit-group" style="margin-top:1rem;">
                <label>Details</label>
                <textarea name="details" rows="3">{{ old('details', $product->details) }}</textarea>
            </div>

            <div class="seller-edit-images">
                <div class="seller-edit-group seller-edit-thumb">
                    <label>Current Main Image</label>
                    @if($product->image_01)
                        <div style="margin-top:0.4rem;">
                            <img src="{{ asset('uploaded_img/' . $product->image_01) }}" alt="">
                        </div>
                    @endif
                    <label style="margin-top:0.5rem;display:block;">Change Main Image</label>
                    <input type="file" name="image_01" accept="image/*">
                </div>
                <div class="seller-edit-group seller-edit-thumb">
                    <label>Current Image 2</label>
                    @if($product->image_02)
                        <div style="margin-top:0.4rem;">
                            <img src="{{ asset('uploaded_img/' . $product->image_02) }}" alt="">
                        </div>
                    @endif
                    <label style="margin-top:0.5rem;display:block;">Change Image 2</label>
                    <input type="file" name="image_02" accept="image/*">
                </div>
                <div class="seller-edit-group seller-edit-thumb">
                    <label>Current Image 3</label>
                    @if($product->image_03)
                        <div style="margin-top:0.4rem;">
                            <img src="{{ asset('uploaded_img/' . $product->image_03) }}" alt="">
                        </div>
                    @endif
                    <label style="margin-top:0.5rem;display:block;">Change Image 3</label>
                    <input type="file" name="image_03" accept="image/*">
                </div>
            </div>

            <div class="seller-edit-actions">
                <button type="submit" class="seller-edit-primary-btn">Update Product</button>
                <a href="{{ route('seller.products.index') }}" class="seller-edit-secondary-link">Back to products</a>
            </div>
        </form>
    </div>
</section>
@endsection
