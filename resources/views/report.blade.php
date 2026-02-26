@extends('layouts.app')

@section('title', 'Submit Report')

@push('styles')
<style>
.report-wrap { max-width:600px; margin:2rem auto; }
.report-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:2rem; }
.report-card h2 { font-size:1.4rem; font-weight:700; margin-bottom:.5rem; }
.report-card .subject-info { background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:1rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:.8rem; color:#374151; font-size:.95rem; }
.report-card .subject-info i { color:#dc2626; font-size:1.2rem; }
.form-group { margin-bottom:1.2rem; }
.form-group label { display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
.form-group select,.form-group textarea { width:100%; padding:.7rem 1rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
.form-group textarea { resize:vertical; }
.form-group input[type=file] { padding:.5rem; border:1px dashed #d1d5db; border-radius:8px; width:100%; background:#fafafa; font-size:.85rem; }
.btn-submit { width:100%; padding:.85rem; background:#dc2626; color:#fff; border:none; border-radius:8px; font-size:1rem; font-weight:700; cursor:pointer; margin-top:1rem; }
.btn-submit:hover { background:#b91c1c; }
.warning-note { background:#fef3c7; border:1px solid #fbbf24; border-radius:8px; padding:.8rem 1rem; font-size:.85rem; color:#92400e; margin-bottom:1.5rem; }
</style>
@endpush

@section('content')
<div class="report-wrap">
    <div class="report-card">
        <h2><i class="fas fa-flag" style="color:#dc2626"></i> Submit Report</h2>
        <p style="color:#6b7280;font-size:.9rem;margin-bottom:1.2rem">Help us keep the platform safe. Your report will be reviewed by our team.</p>

        @php
            $reportedType = $type;
            $reportedName = $reportedEntity
                ? ($reportedEntity->name ?? $reportedEntity->title ?? 'ID #' . $reportedId)
                : 'Unknown (ID #' . $reportedId . ')';
        @endphp

        <div class="subject-info">
            <i class="fas fa-{{ $reportedType === 'product' ? 'box' : 'store' }}"></i>
            <div>
                <div style="font-weight:600">Reporting: {{ ucfirst($reportedType) }}</div>
                <div style="color:#6b7280;font-size:.85rem">{{ $reportedName }}</div>
            </div>
        </div>

        <div class="warning-note">
            <i class="fas fa-exclamation-triangle"></i>
            False reports may result in account suspension. Only submit if you have genuine concerns.
        </div>

        @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:1rem">
            <ul style="margin:0;padding-left:1.2rem">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="reported_type" value="{{ $type }}">
            <input type="hidden" name="reported_id"   value="{{ $reportedId }}">

            <div class="form-group">
                <label>Reason *</label>
                <select name="reason" required>
                    <option value="">Select a reason…</option>
                    <option value="spam"          {{ old('reason')==='spam'        ?'selected':'' }}>Spam or unsolicited messages</option>
                    <option value="counterfeit"   {{ old('reason')==='counterfeit' ?'selected':'' }}>Counterfeit / Fake product</option>
                    <option value="offensive"     {{ old('reason')==='offensive'   ?'selected':'' }}>Offensive content</option>
                    <option value="scam"          {{ old('reason')==='scam'        ?'selected':'' }}>Scam or fraud</option>
                    <option value="inappropriate" {{ old('reason')==='inappropriate'?'selected':'' }}>Inappropriate listing</option>
                    <option value="other"         {{ old('reason')==='other'       ?'selected':'' }}>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" placeholder="Provide additional details about your report…" maxlength="2000">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Evidence (optional — image only, max 2MB)</label>
                <input type="file" name="evidence_image" accept="image/*">
            </div>

            <button type="submit" class="btn-submit"><i class="fas fa-flag"></i> Submit Report</button>
        </form>
    </div>
</div>
@endsection
