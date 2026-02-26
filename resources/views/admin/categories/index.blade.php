@extends('layouts.admin')

@section('title', 'Manage Categories - Admin')

@push('styles')
<style>
.dashboard-content { padding: 2rem 2.2rem; }
.categories-shell { max-width: none; margin: 0; }
.page-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.2rem; }
.page-header h1 { font-size:2rem; font-weight:700; color:#111827; }
.page-header .btn { width:auto !important; flex:0 0 auto; white-space:nowrap; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.25rem; border-radius:8px; font-size:.9rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:.2s; }
.btn-primary { background:#2d5016; color:#fff; }
.btn-primary:hover { background:#1a3009; }
.btn-danger  { background:#dc2626; color:#fff; }
.btn-warning { background:#d97706; color:#fff; }
.btn-sm { padding:.45rem .9rem; font-size:.8rem; }
.table-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.06); }
.table-card table { width:100%; border-collapse:collapse; }
.table-card th { padding:1rem 1.2rem; background:#f9fafb; font-size:.8rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; text-align:left; border-bottom:1px solid #e5e7eb; }
.table-card td { padding:1rem 1.2rem; border-bottom:1px solid #f3f4f6; font-size:.9rem; color:#374151; }
.table-card tr:last-child td { border-bottom:none; }
.badge { padding:.25rem .6rem; border-radius:999px; font-size:.75rem; font-weight:600; }
.badge-success { background:#dcfce7; color:#166534; }
.badge-danger  { background:#fee2e2; color:#991b1b; }
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.active { display:flex; }
.modal { background:#fff; border-radius:16px; padding:2rem; width:100%; max-width:480px; max-height:90vh; overflow-y:auto; }
.cat-thumb { width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb; }
.cat-thumb-placeholder { width:40px; height:40px; border-radius:50%; background:#f3f4f6; display:inline-flex; align-items:center; justify-content:center; color:#d1d5db; font-size:1.1rem; border:2px solid #e5e7eb; }
.img-preview-wrap { margin-top:.5rem; }
.img-preview-wrap img { width:80px; height:80px; border-radius:8px; object-fit:cover; border:1px solid #e5e7eb; }
.modal h3 { font-size:1.2rem; font-weight:700; margin-bottom:1.5rem; }
.form-group { margin-bottom:1.2rem; }
.form-group label { display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
.form-group input,.form-group textarea,.form-group select { width:100%; padding:.7rem 1rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
.form-actions { display:flex; gap:1rem; justify-content:flex-end; margin-top:1.5rem; }
@media (max-width:900px) {
    .dashboard-content { padding: 1.6rem; }
}
</style>
@endpush

@section('content')
<section class="categories-shell">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="page-header">
        <h1><i class="fas fa-tags"></i> Manage Categories</h1>
        <button class="btn btn-primary" onclick="document.getElementById('createModal').classList.add('active')">
            <i class="fas fa-plus"></i> New Category
        </button>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td>
                        @if($cat->image)
                            <img src="{{ asset('uploaded_img/' . $cat->image) }}" class="cat-thumb" alt="{{ $cat->name }}">
                        @else
                            <span class="cat-thumb-placeholder"><i class="fas fa-tag"></i></span>
                        @endif
                    </td>
                    <td><strong>{{ $cat->name }}</strong>
                        @if($cat->description)<div style="font-size:.78rem;color:#9ca3af">{{ Str::limit($cat->description,60) }}</div>@endif
                    </td>
                    <td><code style="font-size:.8rem;background:#f3f4f6;padding:2px 6px;border-radius:4px">{{ $cat->slug }}</code></td>
                    <td>{{ $cat->products_count }}</td>
                    <td>{{ $cat->sort_order }}</td>
                    <td>
                        <span class="badge {{ $cat->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="display:flex;gap:.5rem">
                        <button class="btn btn-warning btn-sm" onclick='openEdit({{ json_encode($cat) }})'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:3rem">No categories yet. Create one to get started.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:1rem">{{ $categories->links() }}</div>
    </div>
</section>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
    <div class="modal">
        <h3>New Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" required maxlength="100" placeholder="e.g. Electronics">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" maxlength="500" style="resize:vertical"></textarea>
            </div>
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="0" min="0">
            </div>
            <div class="form-group">
                <label>Category Image <span style="font-weight:400;color:#9ca3af">(optional, max 2MB)</span></label>
                <input type="file" name="image" accept="image/*" onchange="previewImg(this,'createPreview')">
                <div class="img-preview-wrap" id="createPreview" style="display:none">
                    <img src="" alt="preview">
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn" style="background:#f3f4f6;color:#374151" onclick="document.getElementById('createModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h3>Edit Category</h3>
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" id="editName" required maxlength="100">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="editDesc" rows="3" style="resize:vertical"></textarea>
            </div>
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" id="editOrder" min="0">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" id="editActive">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label>Category Image <span style="font-weight:400;color:#9ca3af">(leave blank to keep current)</span></label>
                <div class="img-preview-wrap" id="editCurrentImg" style="display:none;margin-bottom:.5rem">
                    <img id="editCurrentImgEl" src="" alt="current">
                    <div style="font-size:.75rem;color:#9ca3af;margin-top:.25rem">Current image</div>
                </div>
                <input type="file" name="image" accept="image/*" onchange="previewImg(this,'editNewPreview')">
                <div class="img-preview-wrap" id="editNewPreview" style="display:none">
                    <img src="" alt="new preview">
                    <div style="font-size:.75rem;color:#9ca3af;margin-top:.25rem">New image (will replace current)</div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn" style="background:#f3f4f6;color:#374151" onclick="document.getElementById('editModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEdit(cat) {
    document.getElementById('editForm').action = '/admin/categories/' + cat.id;
    document.getElementById('editName').value   = cat.name;
    document.getElementById('editDesc').value   = cat.description || '';
    document.getElementById('editOrder').value  = cat.sort_order || 0;
    document.getElementById('editActive').value = cat.is_active ? '1' : '0';
    // Show current image preview
    const currentWrap = document.getElementById('editCurrentImg');
    const currentImg  = document.getElementById('editCurrentImgEl');
    if (cat.image) {
        currentImg.src = '/uploaded_img/' + cat.image;
        currentWrap.style.display = 'block';
    } else {
        currentWrap.style.display = 'none';
    }
    // Reset new image preview
    const newPreview = document.getElementById('editNewPreview');
    newPreview.style.display = 'none';
    newPreview.querySelector('img').src = '';
    // Reset file input
    document.querySelector('#editForm input[type=file]').value = '';
    document.getElementById('editModal').classList.add('active');
}
function previewImg(input, previewId) {
    const wrap = document.getElementById(previewId);
    const img  = wrap.querySelector('img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    } else {
        wrap.style.display = 'none';
    }
}
// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
</script>
@endpush
@endsection
