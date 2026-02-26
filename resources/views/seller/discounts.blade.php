@extends('layouts.seller')

@section('title', 'Discounts - Seller')

@push('styles')
<style>
    .discounts-wrap {
        width: 100%;
        display: grid;
        gap: 1rem;
    }

    .discounts-wrap section {
        max-width: none !important;
        margin: 0 !important;
    }

    .discounts-head {
        display: grid;
        gap: 0.6rem;
        padding: 1rem 1.25rem;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(255,255,255,0.94), rgba(240,249,255,0.94));
    }

    .discounts-head-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .discounts-wrap .discounts-head-row {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto !important;
        align-items: center !important;
        width: 100%;
        column-gap: 1rem;
    }

    .discounts-title {
        margin: 0;
        font-size: 2rem;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .discounts-wrap .discounts-title {
        font-size: 2rem !important;
        line-height: 1.1 !important;
    }

    .discounts-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: auto;
        margin-top: 0;
        padding: 0.62rem 1rem;
        border-radius: 9px;
        font-size: 0.84rem;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #059669, #0ea5e9);
        color: #fff;
        box-shadow: 0 6px 16px rgba(14, 165, 233, 0.22);
    }

    .discounts-wrap .discounts-head .btn-primary {
        width: auto !important;
        display: inline-flex !important;
        white-space: nowrap;
        justify-self: end;
        align-self: center;
        margin: 0 !important;
    }

    .btn-primary:hover {
        filter: brightness(1.03);
    }

    .btn-warning {
        background: rgba(245, 158, 11, 0.14);
        color: #92400e;
        border-color: rgba(245, 158, 11, 0.3);
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.14);
        color: #991b1b;
        border-color: rgba(239, 68, 68, 0.28);
    }

    .btn-sm {
        padding: 0.4rem 0.68rem;
        font-size: 0.76rem;
    }

    .discount-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .metric-card {
        background: rgba(255,255,255,0.94);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 12px;
        padding: 0.85rem 0.95rem;
    }

    .metric-label {
        margin: 0;
        color: #64748b;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
    }

    .metric-value {
        margin: 0.35rem 0 0;
        color: #0f172a;
        font-size: 1.35rem;
        font-weight: 800;
    }

    .table-card {
        background: rgba(255,255,255,0.94);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-card th {
        padding: 0.78rem 0.9rem;
        background: #f8fafc;
        font-size: 0.72rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-card td {
        padding: 0.8rem 0.9rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.86rem;
        color: #334155;
        vertical-align: middle;
    }

    .table-card tbody tr:hover {
        background: #f8fafc;
    }

    .name-strong {
        font-weight: 700;
        color: #0f172a;
        display: block;
        margin-bottom: 0.2rem;
    }

    .name-sub {
        color: #94a3b8;
        font-size: 0.74rem;
    }

    .badge {
        padding: 0.22rem 0.56rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        border: 1px solid transparent;
    }

    .badge-blue {
        background: rgba(59,130,246,0.14);
        color: #1e40af;
        border-color: rgba(59,130,246,0.25);
    }

    .badge-yellow {
        background: rgba(245,158,11,0.16);
        color: #92400e;
        border-color: rgba(245,158,11,0.28);
    }

    .toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.84rem;
        padding: 0;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 700;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot.active { background: #22c55e; }
    .status-dot.inactive { background: #ef4444; }

    .actions-cell {
        display: inline-flex;
        gap: 0.4rem;
        align-items: center;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(2, 6, 23, 0.42);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay.active { display: flex; }

    .modal {
        background: #fff;
        border-radius: 14px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        width: min(520px, 100%);
        max-height: 92vh;
        overflow-y: auto;
        padding: 1.2rem;
    }

    .modal h3 {
        margin: 0 0 1rem;
        font-size: 1rem;
        color: #0f172a;
    }

    .form-group { margin-bottom: 0.86rem; }

    .form-group label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.35rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.86rem;
        background: #fff;
        color: #0f172a;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.16);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .form-actions {
        display: flex;
        gap: 0.6rem;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .btn-muted {
        background: #f1f5f9;
        border-color: #dbe2ea;
        color: #334155;
    }

    @media (max-width: 1000px) {
        .discount-metrics {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 820px) {
        .discounts-head-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .discounts-wrap .discounts-head-row {
            grid-template-columns: 1fr !important;
            justify-items: start;
            gap: 0.6rem;
        }

        .table-card {
            overflow-x: auto;
        }

        .table-card table {
            min-width: 860px;
            table-layout: auto;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $discountCount = $discounts->count();
    $activeCount = $discounts->where('is_active', 1)->count();
    $inactiveCount = max($discountCount - $activeCount, 0);
@endphp

<div class="discounts-wrap">
    <section class="discounts-head">
        <div class="discounts-head-row">
            <h1 class="discounts-title"><i class="fas fa-percent"></i> Discounts</h1>
            <button class="btn btn-primary" onclick="document.getElementById('createModal').classList.add('active')">
                <i class="fas fa-plus"></i> New Discount
            </button>
        </div>
        <p class="discounts-subtitle">Create and manage product discount rules for your shop.</p>
    </section>

    <section class="discount-metrics">
        <article class="metric-card">
            <p class="metric-label">Total Discounts</p>
            <p class="metric-value">{{ $discountCount }}</p>
        </article>
        <article class="metric-card">
            <p class="metric-label">Active</p>
            <p class="metric-value">{{ $activeCount }}</p>
        </article>
        <article class="metric-card">
            <p class="metric-label">Inactive</p>
            <p class="metric-value">{{ $inactiveCount }}</p>
        </article>
    </section>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <section class="table-card">
        <table>
            <thead>
                <tr><th>Name</th><th>Type</th><th>Value</th><th>Min Price</th><th>Valid Period</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($discounts as $d)
                <tr>
                    <td>
                        <span class="name-strong">{{ $d->name }}</span>
                        @if($d->description)<span class="name-sub">{{ Str::limit($d->description, 56) }}</span>@endif
                    </td>
                    <td>
                        @if($d->type==='percentage') <span class="badge badge-blue">% Off</span>
                        @else <span class="badge badge-yellow">Fixed PHP</span> @endif
                    </td>
                    <td>{{ $d->type==='percentage' ? $d->value.'%' : 'PHP '.number_format($d->value,2) }}</td>
                    <td>PHP {{ number_format($d->min_price ?? 0, 2) }}</td>
                    <td>
                        @if($d->start_date) {{ \Carbon\Carbon::parse($d->start_date)->format('M d') }} - @endif
                        @if($d->end_date) {{ \Carbon\Carbon::parse($d->end_date)->format('M d, Y') }}
                        @else <em style="color:#94a3b8">No end</em> @endif
                    </td>
                    <td>
                        <button class="toggle-btn" onclick="toggleDiscount({{ $d->id }}, this)" title="{{ $d->is_active ? 'Deactivate' : 'Activate' }}">
                            @if($d->is_active)
                                <span class="status-dot active"></span> Active
                            @else
                                <span class="status-dot inactive"></span> Inactive
                            @endif
                        </button>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <button class="btn btn-warning btn-sm" onclick='openEditDiscount(@json($d))'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('seller.discounts.destroy', $d->id) }}" onsubmit="return confirm('Delete this discount?')" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:2.2rem">No discounts yet. Create one to promote your products.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
<div class="modal">
    <h3>Create Discount</h3>
    <form method="POST" action="{{ route('seller.discounts.store') }}">
        @csrf
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" required maxlength="100" placeholder="e.g. Summer Sale">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="2" style="resize:vertical"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Type *</label>
                <select name="type" required>
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (PHP)</option>
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
        <div class="form-actions">
            <button type="button" class="btn btn-muted" onclick="document.getElementById('createModal').classList.remove('active')">Cancel</button>
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
                    <option value="fixed">Fixed Amount (PHP)</option>
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
            <button type="button" class="btn btn-muted" onclick="document.getElementById('editModal').classList.remove('active')">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
</div>

@push('scripts')
<script>
function openEditDiscount(d) {
    document.getElementById('editDiscountForm').action = '/seller/discounts/' + d.id;
    document.getElementById('ec_name').value   = d.name;
    document.getElementById('ec_desc').value   = d.description || '';
    document.getElementById('ec_type').value   = d.type;
    document.getElementById('ec_value').value  = d.value;
    document.getElementById('ec_min').value    = d.min_price || '';
    document.getElementById('ec_start').value  = d.start_date ? d.start_date.substring(0,10) : '';
    document.getElementById('ec_end').value    = d.end_date ? d.end_date.substring(0,10) : '';
    document.getElementById('ec_active').value = d.is_active ? '1' : '0';
    document.getElementById('editModal').classList.add('active');
}

function toggleDiscount(id, btn) {
    fetch('/seller/discounts/' + id + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(data => {
        if (data.active !== undefined) {
            btn.innerHTML = data.active
                ? '<span class="status-dot active"></span> Active'
                : '<span class="status-dot inactive"></span> Inactive';
        }
    });
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
</script>
@endpush
@endsection
