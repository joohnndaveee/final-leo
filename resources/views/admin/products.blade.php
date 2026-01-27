@extends('layouts.admin')

@section('title', 'Products - Admin Panel')

@push('styles')
<style>
    /* Modern Card Styles */
    .modern-card {
        background: white;
        border-radius: 1.5rem;
        padding: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 3rem;
    }

    .modern-card h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modern-card h2 i {
        color: #667eea;
    }

    /* Form Grid Layout */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-grid .full-width {
        grid-column: 1 / -1;
    }

    .input-group {
        display: flex;
        flex-direction: column;
    }

    .input-group label {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.8rem;
    }

    .input-group .required {
        color: #f56565;
        margin-left: 0.3rem;
    }

    .input-group input,
    .input-group select,
    .input-group textarea {
        width: 100%;
        padding: 1.2rem 1.5rem;
        font-size: 1.6rem;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        background: #f7fafc;
        color: #2d3748;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .input-group input:focus,
    .input-group select:focus,
    .input-group textarea:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* Gradient Button */
    .gradient-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1.5rem 4rem;
        font-size: 1.7rem;
        font-weight: 600;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        font-family: 'Inter', sans-serif;
    }

    .gradient-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .gradient-btn i {
        font-size: 1.8rem;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2.5rem;
        margin-top: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .product-card .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #f7fafc;
    }

    .product-card .product-content {
        padding: 2rem;
    }

    .product-card .price-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 2rem;
        font-size: 1.8rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .product-card .product-name {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card .product-details {
        font-size: 1.4rem;
        color: #718096;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card .product-meta {
        font-size: 1.3rem;
        color: #a0aec0;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .product-card .product-meta span {
        background: #edf2f7;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }

    .product-card .stock-badge {
        font-weight: 600;
    }

    .product-card .stock-badge.in-stock {
        background: #d4edda !important;
        color: #155724 !important;
    }

    .product-card .stock-badge.low-stock {
        background: #fff3cd !important;
        color: #856404 !important;
    }

    .product-card .stock-badge.out-of-stock {
        background: #f8d7da !important;
        color: #721c24 !important;
    }

    .product-card .card-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-update {
        flex: 1;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 1.2rem;
        font-size: 1.5rem;
        font-weight: 600;
        border-radius: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }

    .btn-delete {
        flex: 1;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        padding: 1.2rem;
        font-size: 1.5rem;
        font-weight: 600;
        border-radius: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 6rem;
        color: #cbd5e0;
        margin-bottom: 2rem;
    }

    .empty-state p {
        font-size: 2rem;
        color: #718096;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .modern-card {
            padding: 2rem;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Products Management</h1>

{{-- Add Product Form --}}
<div class="modern-card">
    <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            {{-- Row 1: Name and Price --}}
            <div class="input-group">
                <label>Product Name <span class="required">*</span></label>
                <input type="text" 
                       name="name" 
                       required 
                       placeholder="Enter product name"
                       value="{{ old('name') }}">
            </div>

            <div class="input-group">
                <label>Product Price (₱) <span class="required">*</span></label>
                <input type="number" 
                       name="price" 
                       required 
                       min="0" 
                       max="9999999999"
                       placeholder="Enter price"
                       value="{{ old('price') }}">
            </div>

            {{-- Row 2: Type --}}
            <div class="input-group">
                <label>Product Type</label>
                <select name="type" id="productType">
                    <option value="">Select Type</option>
                    <option value="Polo" {{ old('type') == 'Polo' ? 'selected' : '' }}>Polo</option>
                    <option value="T-Shirt" {{ old('type') == 'T-Shirt' ? 'selected' : '' }}>T-Shirt</option>
                    <option value="Dress" {{ old('type') == 'Dress' ? 'selected' : '' }}>Dress</option>
                    <option value="Pants" {{ old('type') == 'Pants' ? 'selected' : '' }}>Pants</option>
                    <option value="Jacket" {{ old('type') == 'Jacket' ? 'selected' : '' }}>Jacket</option>
                    <option value="Heels" {{ old('type') == 'Heels' ? 'selected' : '' }}>Heels</option>
                    <option value="Shoes" {{ old('type') == 'Shoes' ? 'selected' : '' }}>Shoes</option>
                    <option value="Cap" {{ old('type') == 'Cap' ? 'selected' : '' }}>Cap</option>
                </select>
            </div>

            {{-- Row 3: Stock --}}
            <div class="input-group">
                <label>Stock Quantity <span class="required">*</span></label>
                <input type="number" 
                       name="stock" 
                       required 
                       min="0" 
                       max="999999"
                       placeholder="Enter stock quantity"
                       value="{{ old('stock', 0) }}">
            </div>

            {{-- Row 4: Images --}}
            <div class="input-group">
                <label>Image 01 <span class="required">*</span></label>
                <input type="file" 
                       name="image_01" 
                       accept="image/jpg, image/jpeg, image/png, image/webp" 
                       required>
            </div>

            <div class="input-group">
                <label>Image 02 <span class="required">*</span></label>
                <input type="file" 
                       name="image_02" 
                       accept="image/jpg, image/jpeg, image/png, image/webp" 
                       required>
            </div>

            <div class="input-group">
                <label>Image 03 <span class="required">*</span></label>
                <input type="file" 
                       name="image_03" 
                       accept="image/jpg, image/jpeg, image/png, image/webp" 
                       required>
            </div>

            {{-- Full Width: Details --}}
            <div class="input-group full-width">
                <label>Product Details <span class="required">*</span></label>
                <textarea name="details" 
                          required 
                          maxlength="500"
                          placeholder="Enter product details...">{{ old('details') }}</textarea>
            </div>
        </div>
        
        <button type="submit" class="gradient-btn">
            <i class="fas fa-plus"></i>
            <span>Add Product</span>
        </button>
    </form>
</div>

{{-- Products Display --}}
<div class="modern-card">
    <h2><i class="fas fa-box-open"></i> Products Inventory ({{ $products->count() }})</h2>
    
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                         alt="{{ $product->name }}" 
                         class="product-image">
                    
                    <div class="price-badge">₱{{ number_format($product->price, 2) }}</div>
                    
                    <div class="product-content">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-details">{{ $product->details }}</div>
                        
                        <div class="product-meta">
                            @if($product->type)
                                <span><i class="fas fa-tag"></i> {{ $product->type }}</span>
                            @endif
                            <span class="stock-badge {{ $product->stock <= 0 ? 'out-of-stock' : ($product->stock <= 10 ? 'low-stock' : 'in-stock') }}">
                                <i class="fas fa-box"></i> 
                                @if($product->stock <= 0)
                                    Out of Stock
                                @elseif($product->stock <= 10)
                                    Low Stock ({{ $product->stock }})
                                @else
                                    In Stock ({{ $product->stock }})
                                @endif
                            </span>
                        </div>
                        
                        <div class="card-actions">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-update">
                                <i class="fas fa-edit"></i>
                                <span>Update</span>
                            </a>
                            <a href="{{ route('admin.products.destroy', $product->id) }}" 
                               class="btn-delete"
                               onclick="return confirm('Delete this product?');">
                                <i class="fas fa-trash-alt"></i>
                                <span>Delete</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No products added yet! Start by adding your first product.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Placeholder for future JavaScript functionality
</script>
@endpush
