@extends('layouts.admin')

@section('title', 'Discounts - Admin Panel')

@push('styles')
<style>
    .dashboard-content { padding: 2rem 2.2rem; }
    .heading { margin-bottom: 1.2rem; font-size: 2.4rem !important; }
    .discount-shell { max-width: none; margin: 0; }
    .page-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.1rem; }
    .page-header .btn { width:auto !important; flex:0 0 auto; align-self:center; white-space:nowrap; }
    .btn { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; padding:.6rem 1.2rem; border-radius:8px; font-size:.85rem; font-weight:600; cursor:pointer; border:1px solid transparent; text-decoration:none; transition:.2s; width:auto !important; }
    .btn-primary { background:#2d5016; color:#fff; }
    .btn-danger  { background:#dc2626; color:#fff; }
    .btn-warning { background:#d97706; color:#fff; }
    .btn-sm { padding:.4rem .8rem; font-size:.78rem; min-width:34px; height:30px; }
    .table-card td .btn { width:auto !important; display:inline-flex !important; }
    .table-card td form { margin:0; }
    .action-buttons { display:inline-flex; align-items:center; gap:.45rem; }
    .table-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; }
    .table-card table { width:100%; border-collapse:collapse; }
    .table-card th { padding:.9rem 1.2rem; background:#f9fafb; font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; text-align:left; border-bottom:1px solid #e5e7eb; }
    .table-card td { padding:.9rem 1.2rem; border-bottom:1px solid #f3f4f6; font-size:.88rem; }
    .badge { padding:.25rem .6rem; border-radius:999px; font-size:.72rem; font-weight:700; }
    .badge-success { background:#dcfce7; color:#166534; }
    .badge-danger  { background:#fee2e2; color:#991b1b; }
    .badge-blue    { background:#dbeafe; color:#1e40af; }
    .badge-yellow  { background:#fef3c7; color:#92400e; }
    .toggle-btn { background:none; border:none; cursor:pointer; font-size:1.1rem; padding:.2rem .4rem; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:14px; padding:2rem; width:100%; max-width:500px; max-height:90vh; overflow-y:auto; }
    .modal h3 { font-size:1.1rem; font-weight:700; margin-bottom:1.5rem; }
    .form-group { margin-bottom:1.1rem; }
    .form-group label { display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:.35rem; }
    .form-group input,.form-group select,.form-group textarea { width:100%; padding:.65rem .9rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .form-actions { display:flex; gap:1rem; justify-content:flex-end; margin-top:1.5rem; }
    @media (max-width:900px) {
        .dashboard-content { padding: 1.6rem; }
        .page-header { align-items:flex-start; }
    }
</style>
@endpush

@section('content')
<h1 class="heading">Discounts</h1>

<section class="discount-shell">
<div class="page-header">
    <div>
        <div style="font-size:1.8rem;font-weight:700"><i class="fas fa-percent"></i> Seasonal Item Discounts</div>
        <div style="color:#6b7280;font-size:.95rem;margin-top:.2rem">Create discount rules and apply them to products in the product editor.</div>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('createModal').classList.add('active')">
        <i class="fas fa-plus"></i> New Discount
    </button>
</div>

@if(session('success'))<div class="message"><span>{{ session('success') }}</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>@endif
@if(session('error'))<div class="message" style="border-left-color:#dc2626"><span>{{ session('error') }}</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>@endif

@php
    $bannerBg = $settings?->seasonal_banner_bg_color ?: '#1a3009';
    $bannerTx = $settings?->seasonal_banner_text_color ?: '#ffffff';
    $bannerMsg = $settings?->seasonal_banner_message ?: null;
    $bannerEnabled = (bool) ($settings?->seasonal_banner_enabled ?? 1);
    $active = $activeDiscount ?? null;
    $computed = $active
        ? strtoupper($active->name) . ' ' . (
            $active->type === 'percentage'
                ? rtrim(rtrim(number_format((float) $active->value, 2), '0'), '.') . '%'
                : '₱' . number_format((float) $active->value, 2)
        )
        : null;
    $previewMsg = $active ? (trim((string) $bannerMsg) !== '' ? (string) $bannerMsg : $computed) : null;
@endphp

<div class="table-card" style="margin-bottom:1.6rem;padding:1.2rem">
    <div style="font-weight:800;margin-bottom:.75rem">Banner Preview</div>
    @if(!$active)
        <div style="border-radius:10px;padding:.8rem 1rem;text-align:center;background:#f3f4f6;color:#6b7280;font-weight:800;">
            NO DISCOUNT AVAILABLE
        </div>
    @else
        <div style="border-radius:10px;padding:.8rem 1rem;text-align:center;background:{{ $bannerBg }};color:{{ $bannerTx }};font-weight:800;opacity:{{ $bannerEnabled ? '1' : '.55' }};">
            SALE <span style="margin-left:.55rem;font-weight:700">{{ $previewMsg }}</span>
        </div>
    @endif
    <div style="color:#6b7280;font-size:.85rem;margin-top:.6rem">Only 1 active seasonal discount is allowed at a time.</div>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Name</th><th>Type</th><th>Value</th><th>Min Price</th><th>Valid Period</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($discounts as $d)
            <tr>
                <td>
                    <strong>{{ $d->name }}</strong>
                    @if($d->description)<div style="font-size:.78rem;color:#9ca3af">{{ Str::limit($d->description, 50) }}</div>@endif
                </td>
                <td>
                    @if($d->type==='percentage') <span class="badge badge-blue">% Off</span>
                    @else <span class="badge badge-yellow">Fixed ₱</span> @endif
                </td>
                <td>{{ $d->type==='percentage' ? $d->value.'%' : '₱'.number_format($d->value,2) }}</td>
                <td>₱{{ number_format($d->min_price ?? 0, 2) }}</td>
                <td>
                    @if($d->start_date) {{ \Carbon\Carbon::parse($d->start_date)->format('M d') }} – @endif
                    @if($d->end_date) {{ \Carbon\Carbon::parse($d->end_date)->format('M d, Y') }}
                    @else <em style="color:#9ca3af">No end</em> @endif
                </td>
                <td>
                    <button class="toggle-btn" onclick="toggleDiscount({{ $d->id }}, this)" title="{{ $d->is_active ? 'Deactivate' : 'Activate' }}">
                        @if($d->is_active) <span style="color:#16a34a">●</span> Active
                        @else <span style="color:#dc2626">●</span> Inactive @endif
                    </button>
                </td>
                <td>
                    <div class="action-buttons">
                    <button class="btn btn-warning btn-sm" onclick='openEditDiscount(@json($d))'>
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.discounts.destroy', $d->id) }}" onsubmit="return confirm('Delete this discount?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:3rem">No discounts yet. Create one for seasonal promos (e.g., Valentine’s Day).</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $discounts->links() }}
</section>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
<div class="modal">
    <h3>Create Discount</h3>
    <form method="POST" action="{{ route('admin.discounts.store') }}">
        @csrf
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" required maxlength="100" placeholder="e.g. Valentines Sale">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="2" style="resize:vertical" placeholder="Optional"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Type *</label>
                <select name="type" required>
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (₱)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Value *</label>
                <input type="number" name="value" required min="0.01" step="0.01" placeholder="e.g. 10">
            </div>
        </div>
        <div class="form-group">
            <label>Minimum Product Price</label>
            <input type="number" name="min_price" min="0" step="0.01" placeholder="0 = no minimum">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date">
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date">
            </div>
        </div>

        <div style="margin-top:1.2rem;padding-top:1.2rem;border-top:1px solid #e5e7eb">
            <div style="font-weight:800;margin-bottom:.75rem">Navbar Banner</div>
            <div class="form-group" style="margin-bottom:.9rem">
                <label style="display:flex;align-items:center;gap:.6rem">
                <input type="hidden" name="seasonal_banner_enabled" value="0">
                <input type="checkbox" name="seasonal_banner_enabled" value="1" {{ $bannerEnabled ? 'checked' : '' }}>
                Enable banner
            </label>
        </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Background color</label>
                    <input type="text" name="seasonal_banner_bg_color" value="{{ $bannerBg }}" placeholder="#1a3009">
                </div>
                <div class="form-group">
                    <label>Text color</label>
                    <input type="text" name="seasonal_banner_text_color" value="{{ $bannerTx }}" placeholder="#ffffff">
                </div>
            </div>

            <div class="form-group">
                <label>Banner message</label>
                <input type="text" name="seasonal_banner_message" value="{{ $bannerMsg }}" placeholder="VALENTINES DAY 20%">
                <div style="color:#6b7280;font-size:.8rem;margin-top:.35rem">If empty, the banner uses this discount’s name + value.</div>
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
    <h3>Edit Discount</h3>
    <form method="POST" id="editDiscountForm">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" id="ec_name" required maxlength="100">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="ec_desc" rows="2" style="resize:vertical"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Type *</label>
                <select name="type" id="ec_type" required>
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (₱)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Value *</label>
                <input type="number" name="value" id="ec_value" required min="0.01" step="0.01">
            </div>
        </div>
        <div class="form-group">
            <label>Minimum Product Price</label>
            <input type="number" name="min_price" id="ec_min" min="0" step="0.01">
        </div>
        <div class="form-row">
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date" id="ec_start"></div>
            <div class="form-group"><label>End Date</label><input type="date" name="end_date" id="ec_end"></div>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="is_active" id="ec_active">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="button" class="btn" style="background:#f3f4f6;color:#374151" onclick="document.getElementById('editModal').classList.remove('active')">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
function openEditDiscount(d) {
    document.getElementById('editDiscountForm').action = '/admin/discounts/' + d.id;
    document.getElementById('ec_name').value   = d.name;
    document.getElementById('ec_desc').value   = d.description || '';
    document.getElementById('ec_type').value   = d.type;
    document.getElementById('ec_value').value  = d.value;
    document.getElementById('ec_min').value    = d.min_price || '';
    document.getElementById('ec_start').value  = d.start_date ? d.start_date.substring(0,10) : '';
    document.getElementById('ec_end').value    = d.end_date   ? d.end_date.substring(0,10)   : '';
    document.getElementById('ec_active').value = d.is_active ? '1' : '0';
    document.getElementById('editModal').classList.add('active');
}
function toggleDiscount(id, btn) {
    fetch('/admin/discounts/' + id + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(data => {
        if (data.is_active !== undefined) {
            btn.innerHTML = data.is_active
                ? '<span style="color:#16a34a">●</span> Active'
                : '<span style="color:#dc2626">●</span> Inactive';
        }
    });
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
</script>
@endpush
