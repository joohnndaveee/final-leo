@extends('layouts.admin')

@section('title', 'Report #{{ $report->id }} - Admin')

@push('styles')
<style>
.back-link { display:inline-flex; align-items:center; gap:.5rem; color:#6b7280; text-decoration:none; margin-bottom:1.5rem; font-size:.9rem; }
.back-link:hover { color:#374151; }
.detail-grid { display:grid; grid-template-columns:2fr 1fr; gap:2rem; }
.card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.5rem; margin-bottom:1.5rem; }
.card h3 { font-size:1rem; font-weight:700; color:#374151; border-bottom:1px solid #f3f4f6; padding-bottom:.8rem; margin-bottom:1.2rem; }
.info-row { display:flex; gap:1rem; padding:.6rem 0; border-bottom:1px solid #f9fafb; font-size:.9rem; }
.info-row:last-child { border-bottom:none; }
.info-row .lbl { font-weight:600; color:#6b7280; min-width:140px; }
.info-row .val { color:#111827; }
.badge { padding:.3rem .7rem; border-radius:999px; font-size:.75rem; font-weight:700; }
.badge-yellow { background:#fef3c7; color:#92400e; }
.badge-blue   { background:#dbeafe; color:#1e40af; }
.badge-green  { background:#dcfce7; color:#166534; }
.badge-gray   { background:#f3f4f6; color:#6b7280; }
.form-group { margin-bottom:1.2rem; }
.form-group label { display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
.form-group select, .form-group textarea { width:100%; padding:.7rem 1rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.25rem; border-radius:8px; font-size:.9rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:.2s; }
.btn-primary { background:#2d5016; color:#fff; }
.evidence-img { max-width:100%; border-radius:8px; border:1px solid #e5e7eb; margin-top:.5rem; }
</style>
@endpush

@section('content')
<div class="dashboard-content">
    <a href="{{ route('admin.reported.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Reports</a>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <h1 style="font-size:1.6rem;font-weight:700;margin-bottom:1.5rem">Report #{{ $report->id }}</h1>

    <div class="detail-grid">
        <div>
            {{-- Report Details --}}
            <div class="card">
                <h3><i class="fas fa-info-circle"></i> Report Details</h3>
                <div class="info-row"><span class="lbl">Reporter</span><span class="val">{{ optional($report->reporter)->name ?? 'Unknown' }} ({{ optional($report->reporter)->email }})</span></div>
                <div class="info-row"><span class="lbl">Reported Type</span><span class="val">{{ ucfirst($report->reported_type) }}</span></div>
                <div class="info-row"><span class="lbl">Reported Entity</span><span class="val">{{ $report->getReportedName() }}</span></div>
                <div class="info-row"><span class="lbl">Reason</span><span class="val">{{ ucfirst(str_replace('_',' ',$report->reason)) }}</span></div>
                <div class="info-row"><span class="lbl">Submitted</span><span class="val">{{ $report->created_at->format('M d, Y g:i A') }}</span></div>
                <div class="info-row"><span class="lbl">Status</span>
                    <span class="val">
                        @if($report->status==='pending')   <span class="badge badge-yellow">Pending</span>
                        @elseif($report->status==='reviewed') <span class="badge badge-blue">Reviewed</span>
                        @elseif($report->status==='resolved') <span class="badge badge-green">Resolved</span>
                        @else <span class="badge badge-gray">Dismissed</span> @endif
                    </span>
                </div>
            </div>

            {{-- Description --}}
            @if($report->description)
            <div class="card">
                <h3><i class="fas fa-align-left"></i> Description</h3>
                <p style="color:#374151;line-height:1.7">{{ $report->description }}</p>
            </div>
            @endif

            {{-- Evidence --}}
            @if($report->evidence_image)
            <div class="card">
                <h3><i class="fas fa-image"></i> Evidence</h3>
                <img src="{{ asset('uploaded_img/reports/' . $report->evidence_image) }}" alt="Evidence" class="evidence-img">
            </div>
            @endif

            {{-- Admin Notes --}}
            @if($report->admin_notes)
            <div class="card">
                <h3><i class="fas fa-sticky-note"></i> Existing Admin Notes</h3>
                <p style="color:#374151;line-height:1.7">{{ $report->admin_notes }}</p>
            </div>
            @endif
        </div>

        <div>
            {{-- Update Status --}}
            <div class="card">
                <h3><i class="fas fa-edit"></i> Update Status</h3>
                <form method="POST" action="{{ route('admin.reported.update', $report->id) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="pending"  {{ $report->status==='pending'  ?'selected':'' }}>Pending</option>
                            <option value="reviewed" {{ $report->status==='reviewed' ?'selected':'' }}>Reviewed</option>
                            <option value="resolved" {{ $report->status==='resolved' ?'selected':'' }}>Resolved</option>
                            <option value="dismissed"{{ $report->status==='dismissed'?'selected':'' }}>Dismissed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Admin Notes</label>
                        <textarea name="admin_notes" rows="5" style="resize:vertical" placeholder="Add notes about this report...">{{ $report->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-save"></i> Update Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
