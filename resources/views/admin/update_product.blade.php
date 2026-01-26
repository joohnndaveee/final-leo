@extends('layouts.admin')

@section('title', 'Update Product - Admin Panel')

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

    /* Image Preview Section */
    .image-preview-section {
        background: #f7fafc;
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 3rem;
    }

    .main-preview {
        text-align: center;
        margin-bottom: 2rem;
    }

    .main-preview img {
        max-width: 100%;
        height: 400px;
        object-fit: contain;
        border-radius: 1rem;
        background: white;
    }

    .thumbnail-preview {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .thumbnail-preview img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 0.8rem;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .thumbnail-preview img:hover {
        border-color: #667eea;
        transform: scale(1.05);
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

    .input-group .help-text {
        font-size: 1.3rem;
        color: #a0aec0;
        margin-top: 0.5rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .btn-update {
        flex: 1;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 1.5rem 3rem;
        font-size: 1.7rem;
        font-weight: 600;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
    }

    .btn-back {
        flex: 1;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #2d3748;
        border: none;
        padding: 1.5rem 3rem;
        font-size: 1.7rem;
        font-weight: 600;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(168, 237, 234, 0.3);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(168, 237, 234, 0.4);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .modern-card {
            padding: 2rem;
        }

        .main-preview img {
            height: 300px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Update Product</h1>

<div class="modern-card">
    <h2><i class="fas fa-edit"></i> Edit Product Details</h2>
    
    {{-- Image Preview Section --}}
    <div class="image-preview-section">
        <div class="main-preview">
            <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                 alt="{{ $product->name }}" 
                 id="mainImage">
        </div>
        <div class="thumbnail-preview">
            <img src="{{ asset('uploaded_img/' . $product->image_01) }}" 
                 alt="{{ $product->name }}" 
                 onclick="document.getElementById('mainImage').src = this.src">
            <img src="{{ asset('uploaded_img/' . $product->image_02) }}" 
                 alt="{{ $product->name }}" 
                 onclick="document.getElementById('mainImage').src = this.src">
            <img src="{{ asset('uploaded_img/' . $product->image_03) }}" 
                 alt="{{ $product->name }}" 
                 onclick="document.getElementById('mainImage').src = this.src">
        </div>
    </div>

    {{-- Update Form --}}
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            {{-- Row 1: Name and Price --}}
            <div class="input-group">
                <label>Product Name <span class="required">*</span></label>
                <input type="text" 
                       name="name" 
                       required 
                       maxlength="100"
                       placeholder="Enter product name" 
                       value="{{ old('name', $product->name) }}">
            </div>

            <div class="input-group">
                <label>Product Price (₱) <span class="required">*</span></label>
                <input type="number" 
                       name="price" 
                       required 
                       min="0" 
                       max="9999999999"
                       placeholder="Enter price" 
                       value="{{ old('price', $product->price) }}">
            </div>

            {{-- Row 2: Type and Size --}}
            <div class="input-group">
                <label>Product Type</label>
                <select name="type" id="productType" onchange="updateSizeOptions()">
                    <option value="">Select Type</option>
                    <option value="Polo" {{ old('type', $product->type) == 'Polo' ? 'selected' : '' }}>Polo</option>
                    <option value="T-Shirt" {{ old('type', $product->type) == 'T-Shirt' ? 'selected' : '' }}>T-Shirt</option>
                    <option value="Dress" {{ old('type', $product->type) == 'Dress' ? 'selected' : '' }}>Dress</option>
                    <option value="Pants" {{ old('type', $product->type) == 'Pants' ? 'selected' : '' }}>Pants</option>
                    <option value="Jacket" {{ old('type', $product->type) == 'Jacket' ? 'selected' : '' }}>Jacket</option>
                    <option value="Heels" {{ old('type', $product->type) == 'Heels' ? 'selected' : '' }}>Heels</option>
                    <option value="Shoes" {{ old('type', $product->type) == 'Shoes' ? 'selected' : '' }}>Shoes</option>
                    <option value="Cap" {{ old('type', $product->type) == 'Cap' ? 'selected' : '' }}>Cap</option>
                </select>
            </div>

            <div class="input-group">
                <label>Size</label>
                <select name="size" id="productSize">
                    <option value="">Select Size</option>
                </select>
            </div>

            {{-- Row 3: Color --}}
            <div class="input-group">
                <label>Color</label>
                <input type="text" 
                       name="color" 
                       maxlength="50"
                       placeholder="Enter color" 
                       value="{{ old('color', $product->color) }}">
            </div>

            {{-- Full Width: Details --}}
            <div class="input-group full-width">
                <label>Product Details <span class="required">*</span></label>
                <textarea name="details" 
                          required 
                          maxlength="500"
                          placeholder="Enter product details...">{{ old('details', $product->details) }}</textarea>
            </div>

            {{-- Row 4: Update Images (Optional) --}}
            <div class="input-group">
                <label>Update Image 01</label>
                <input type="file" 
                       name="image_01" 
                       accept="image/jpg, image/jpeg, image/png, image/webp">
                <span class="help-text">Leave empty to keep current image</span>
            </div>

            <div class="input-group">
                <label>Update Image 02</label>
                <input type="file" 
                       name="image_02" 
                       accept="image/jpg, image/jpeg, image/png, image/webp">
                <span class="help-text">Leave empty to keep current image</span>
            </div>

            <div class="input-group">
                <label>Update Image 03</label>
                <input type="file" 
                       name="image_03" 
                       accept="image/jpg, image/jpeg, image/png, image/webp">
                <span class="help-text">Leave empty to keep current image</span>
            </div>
        </div>
        
        <div class="action-buttons">
            <button type="submit" class="btn-update">
                <i class="fas fa-save"></i>
                <span>Update Product</span>
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Products</span>
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updateSizeOptions() {
    var type = document.getElementById("productType").value;
    var sizeSelect = document.getElementById("productSize");
    var currentSize = "{{ old('size', $product->size ?? '') }}";

    var sizeOptions = {
        "Polo": ["XS", "S", "M", "L", "XL", "XXL"],
        "T-Shirt": ["XS", "S", "M", "L", "XL", "XXL"],
        "Dress": ["XS", "S", "M", "L", "XL", "XXL"],
        "Pants": ["XS", "S", "M", "L", "XL", "XXL"],
        "Jacket": ["XS", "S", "M", "L", "XL", "XXL"],
        "Heels": ["35", "36", "37", "38", "39", "40"],
        "Shoes": ["36", "37", "38", "39", "40", "42", "43", "44"],
        "Cap": ["One Size"]
    };

    sizeSelect.innerHTML = '<option value="">Select Size</option>';
    
    if (type && sizeOptions[type]) {
        sizeOptions[type].forEach(function(size) {
            var option = document.createElement("option");
            option.value = size;
            option.text = size;
            if (size === currentSize) {
                option.selected = true;
            }
            sizeSelect.add(option);
        });
    } else if (currentSize) {
        // If no type selected but there's a current size, add it
        var option = document.createElement("option");
        option.value = currentSize;
        option.text = currentSize;
        option.selected = true;
        sizeSelect.add(option);
    }
}

// Initialize size options on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSizeOptions();
});
</script>
@endpush
