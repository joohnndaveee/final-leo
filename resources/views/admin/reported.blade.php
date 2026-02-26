@extends('layouts.admin')

@section('title', 'Reports & Flags - Admin')

@push('styles')
<style>
.dashboard-content { padding: 2rem 2.2rem; }
.report-shell { max-width: none; margin: 0; }
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
.page-header h1 { font-size:2rem; font-weight:700; color:#111827; }
.stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:2rem; }
.stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.2rem; text-align:center; }
.stat-card .num { font-size:1.8rem; font-weight:800; }
.stat-card .lbl { font-size:.8rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; }
.filter-bar { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; align-items:center; }
.filter-bar select { padding:.55rem .9rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.55rem 1rem; border-radius:8px; font-size:.85rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:.2s; }
.btn-primary { background:#2d5016; color:#fff; }
.btn-danger  { background:#dc2626; color:#fff; }
.table-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; }
.table-card table { width:100%; border-collapse:collapse; }
.table-card th { padding:.9rem 1.2rem; background:#f9fafb; font-size:.78rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; text-align:left; border-bottom:1px solid #e5e7eb; }
.table-card td { padding:.9rem 1.2rem; border-bottom:1px solid #f3f4f6; font-size:.88rem; color:#374151; }
.badge { padding:.25rem .6rem; border-radius:999px; font-size:.72rem; font-weight:700; }
.badge-yellow { background:#fef3c7; color:#92400e; }
.badge-blue   { background:#dbeafe; color:#1e40af; }
.badge-green  { background:#dcfce7; color:#166534; }
.badge-gray   { background:#f3f4f6; color:#6b7280; }
.badge-red    { background:#fee2e2; color:#991b1b; }
.badge-purple { background:#ede9fe; color:#5b21b6; }
.action-row { display:flex; gap:.5rem; }
@media (max-width:900px) {
    .dashboard-content { padding: 1.6rem; }
}
</style>
@endpush

@section('content')
<section class="report-shell">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="page-header">
        <h1><i class="fas fa-flag"></i> Reports & Flagged Items</h1>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card"><div class="num" style="color:#d97706">{{ $stats['pending'] }}</div><div class="lbl">Pending</div></div>
        <div class="stat-card"><div class="num" style="color:#2563eb">{{ $stats['reviewed'] }}</div><div class="lbl">Reviewed</div></div>
        <div class="stat-card"><div class="num" style="color:#16a34a">{{ $stats['resolved'] }}</div><div class="lbl">Resolved</div></div>
        <div class="stat-card"><div class="num" style="color:#6b7280">{{ $stats['dismissed'] }}</div><div class="lbl">Dismissed</div></div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.reported.index') }}">
    <div class="filter-bar">
        <div>
            <select name="status" onchange="this.form.submit()">
                <option value="all" {{ $status==='all' ? 'selected' : '' }}>All Statuses</option>
                <option value="pending"  {{ $status==='pending'  ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ $status==='reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="resolved" {{ $status==='resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="dismissed"{{ $status==='dismissed'? 'selected' : '' }}>Dismissed</option>
            </select>
        </div>
        <div>
            <select name="type" onchange="this.form.submit()">
                <option value="all"    {{ $type==='all'    ? 'selected' : '' }}>All Types</option>
                <option value="product"{{ $type==='product'? 'selected' : '' }}>Product</option>
                <option value="seller" {{ $type==='seller' ? 'selected' : '' }}>Seller</option>
                <option value="user"   {{ $type==='user'   ? 'selected' : '' }}>User</option>
            </select>
        </div>
    </div>
    </form>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Type</th>
                    <th>Reported</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($reports as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ optional($r->reporter)->name ?? 'Unknown' }}<br><small style="color:#9ca3af">{{ optional($r->reporter)->email }}</small></td>
                    <td>
                        @if($r->reported_type==='product') <span class="badge badge-blue">Product</span>
                        @elseif($r->reported_type==='seller') <span class="badge badge-purple">Seller</span>
                        @else <span class="badge badge-gray">User</span> @endif
                    </td>
                    <td>{{ $r->getReportedName() }}</td>
                    <td>{{ $r->reason }}</td>
                    <td>
                        @if($r->status==='pending')   <span class="badge badge-yellow">Pending</span>
                        @elseif($r->status==='reviewed') <span class="badge badge-blue">Reviewed</span>
                        @elseif($r->status==='resolved') <span class="badge badge-green">Resolved</span>
                        @else <span class="badge badge-gray">Dismissed</span> @endif
                    </td>
                    <td>{{ $r->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-row">
                            <a href="{{ route('admin.reported.show', $r->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                            <form method="POST" action="{{ route('admin.reported.destroy', $r->id) }}" onsubmit="return confirm('Delete this report?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:3rem">No reports found.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:1rem">{{ $reports->links() }}</div>
    </div>
</section>
@endsection
