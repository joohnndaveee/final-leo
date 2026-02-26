@extends('layouts.seller')

@section('title', 'Vouchers - Seller')

@push('styles')
<style>
    .vouchers-wrap {
        width: 100%;
        display: grid;
        gap: 1rem;
    }

    .vouchers-wrap section {
        max-width: none !important;
        margin: 0 !important;
    }

    .vouchers-head {
        display: grid;
        gap: 0.6rem;
        padding: 1rem 1.25rem;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(255,255,255,0.94), rgba(240,249,255,0.94));
    }

    .vouchers-head-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .vouchers-wrap .vouchers-head-row {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto !important;
        align-items: center !important;
        width: 100%;
        column-gap: 1rem;
    }

    .vouchers-title {
        margin: 0;
        font-size: 2rem;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .vouchers-wrap .vouchers-title {
        font-size: 2rem !important;
        line-height: 1.1 !important;
    }

    .vouchers-subtitle {
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

    .vouchers-wrap .vouchers-head .btn-primary {
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

    .btn-muted {
        background: #f1f5f9;
        border-color: #dbe2ea;
        color: #334155;
    }

    .voucher-metrics {
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

    .code-pill {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', monospace;
        background: rgba(14, 165, 233, 0.12);
        border: 1px solid rgba(14, 165, 233, 0.25);
        color: #0c4a6e;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
    }

    .desc-sub {
        color: #94a3b8;
        font-size: 0.74rem;
        margin-top: 0.2rem;
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

    .badge-success {
        background: rgba(34, 197, 94, 0.14);
        color: #166534;
        border-color: rgba(34, 197, 94, 0.26);
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.14);
        color: #991b1b;
        border-color: rgba(239, 68, 68, 0.26);
    }

    .usage-wrap {
        display: grid;
        gap: 0.25rem;
    }

    .usage-text {
        font-size: 0.78rem;
        color: #475569;
        font-weight: 700;
    }

    .usage-bar {
        height: 7px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .usage-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #10b981, #0ea5e9);
    }

    .status-toggle {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }

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
        width: min(560px, 100%);
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

    @media (max-width: 1000px) {
        .voucher-metrics {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 820px) {
        .vouchers-head-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .vouchers-wrap .vouchers-head-row {
            grid-template-columns: 1fr !important;
            justify-items: start;
            gap: 0.6rem;
        }

        .table-card {
            overflow-x: auto;
        }

        .table-card table {
            min-width: 980px;
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
    $voucherCount = $vouchers->count();
    $activeCount = $vouchers->where('is_active', 1)->count();
    $inactiveCount = max($voucherCount - $activeCount, 0);
@endphp

<div class="vouchers-wrap">
    <section class="vouchers-head">
        <div class="vouchers-head-row">
            <h1 class="vouchers-title"><i class="fas fa-ticket-alt"></i> Vouchers</h1>
            <button class="btn btn-primary" onclick="document.getElementById('createModal').classList.add('active')">
                <i class="fas fa-plus"></i> New Voucher
            </button>
        </div>
        <p class="vouchers-subtitle">Create voucher codes and control usage, limits, and validity periods.</p>
    </section>

    <section class="voucher-metrics">
        <article class="metric-card">
            <p class="metric-label">Total Vouchers</p>
            <p class="metric-value">{{ $voucherCount }}</p>
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
                <tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Usage</th><th>Valid Period</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($vouchers as $v)
                <tr>
                    <td>
                        <span class="code-pill">{{ $v->code }}</span>
                        @if($v->description)<div class="desc-sub">{{ Str::limit($v->description, 54) }}</div>@endif
                    </td>
                    <td>
                        @if($v->type==='percentage') <span class="badge badge-blue">% Off</span>
                        @else <span class="badge badge-yellow">Fixed PHP</span> @endif
                    </td>
                    <td>
                        {{ $v->type==='percentage' ? $v->value.'%' : 'PHP '.number_format($v->value,2) }}
                        @if($v->max_discount_amount)
                            <div class="desc-sub">Max PHP {{ number_format($v->max_discount_amount,2) }}</div>
                        @endif
                    </td>
                    <td>PHP {{ number_format($v->min_order_amount ?? 0, 2) }}</td>
                    <td>
                        <div class="usage-wrap">
                            <div class="usage-text">{{ $v->used_count }} / {{ $v->usage_limit ?? 'Unlimited' }}</div>
                            @if($v->usage_limit)
                                <div class="usage-bar"><div class="usage-fill" style="width:{{ min(100, ($v->used_count / $v->usage_limit) * 100) }}%"></div></div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($v->start_date) {{ \Carbon\Carbon::parse($v->start_date)->format('M d') }} - @endif
                        @if($v->end_date) {{ \Carbon\Carbon::parse($v->end_date)->format('M d, Y') }}
                        @else <em style="color:#94a3b8">No end</em> @endif
                    </td>
                    <td>
                        <button class="status-toggle" onclick="toggleVoucher({{ $v->id }}, this)">
                            @if($v->is_active) <span class="badge badge-success">Active</span>
                            @else <span class="badge badge-danger">Inactive</span> @endif
                        </button>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <button class="btn btn-warning btn-sm" onclick='openEditVoucher(@json($v))'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('seller.vouchers.destroy', $v->id) }}" onsubmit="return confirm('Delete this voucher?')" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:2.2rem">No vouchers yet. Create coupon codes for your customers.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
<div class="modal">
    <h3>Create Voucher</h3>
    <form method="POST" action="{{ route('seller.vouchers.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Voucher Code *</label>
                <input type="text" name="code" required maxlength="50" placeholder="e.g. SAVE20" style="text-transform:uppercase">
            </div>
            <div class="form-group">
                <label>Usage Limit</label>
                <input type="number" name="usage_limit" min="1" placeholder="Leave blank = unlimited">
            </div>
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
                <input type="number" name="value" required min="0.01" step="0.01" placeholder="e.g. 20">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Min Order Amount</label>
                <input type="number" name="min_order_amount" min="0" step="0.01" placeholder="0 = no minimum">
            </div>
            <div class="form-group">
                <label>Max Discount (for %)</label>
                <input type="number" name="max_discount_amount" min="0" step="0.01" placeholder="Leave blank = no cap">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date"></div>
            <div class="form-group"><label>End Date</label><input type="date" name="end_date"></div>
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
    <h3>Edit Voucher</h3>
    <form method="POST" id="editVoucherForm">
        @csrf @method('PUT')
        <div class="form-row">
            <div class="form-group"><label>Code *</label><input type="text" name="code" id="ev_code" required maxlength="50" style="text-transform:uppercase"></div>
            <div class="form-group"><label>Usage Limit</label><input type="number" name="usage_limit" id="ev_limit" min="1"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" id="ev_desc" rows="2" style="resize:vertical"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Type</label><select name="type" id="ev_type"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
            <div class="form-group"><label>Value</label><input type="number" name="value" id="ev_value" required min="0.01" step="0.01"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Min Order</label><input type="number" name="min_order_amount" id="ev_min" min="0" step="0.01"></div>
            <div class="form-group"><label>Max Discount</label><input type="number" name="max_discount_amount" id="ev_max" min="0" step="0.01"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date" id="ev_start"></div>
            <div class="form-group"><label>End Date</label><input type="date" name="end_date" id="ev_end"></div>
        </div>
        <div class="form-group"><label>Status</label>
            <select name="is_active" id="ev_active"><option value="1">Active</option><option value="0">Inactive</option></select>
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
function openEditVoucher(v) {
    document.getElementById('editVoucherForm').action = '/seller/vouchers/' + v.id;
    document.getElementById('ev_code').value  = v.code;
    document.getElementById('ev_limit').value = v.usage_limit || '';
    document.getElementById('ev_desc').value  = v.description || '';
    document.getElementById('ev_type').value  = v.type;
    document.getElementById('ev_value').value = v.value;
    document.getElementById('ev_min').value   = v.min_order_amount || '';
    document.getElementById('ev_max').value   = v.max_discount_amount || '';
    document.getElementById('ev_start').value = v.start_date ? v.start_date.substring(0,10) : '';
    document.getElementById('ev_end').value   = v.end_date ? v.end_date.substring(0,10) : '';
    document.getElementById('ev_active').value= v.is_active ? '1' : '0';
    document.getElementById('editModal').classList.add('active');
}

function toggleVoucher(id, btn) {
    fetch('/seller/vouchers/' + id + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(data => {
        if (data.active !== undefined) {
            btn.innerHTML = data.active
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';
        }
    });
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
</script>
@endpush
@endsection
