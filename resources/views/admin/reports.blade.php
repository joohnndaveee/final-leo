@extends('layouts.admin')

@section('title', 'Sales Reports - Admin')

@push('styles')
<style>
.dashboard-content { padding: 2rem 2.2rem; }
.report-shell { max-width: none; margin: 0; }
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
.page-header h1 { font-size:2rem; font-weight:700; color:#111827; }
.filter-bar { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.2rem 1.5rem; margin-bottom:2rem; display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end; }
.filter-bar label { font-size:.8rem; font-weight:600; color:#6b7280; display:block; margin-bottom:.3rem; }
.filter-bar select { padding:.55rem .9rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.25rem; border-radius:8px; font-size:.9rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:.2s; }
.btn-primary { background:#2d5016; color:#fff; }
.btn-export { background:#16a34a; color:#fff; }
.btn-export:hover { background:#15803d; }
.stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; margin-bottom:2rem; }
.stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.5rem; }
.stat-card .label { font-size:.8rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; }
.stat-card .value { font-size:2rem; font-weight:800; color:#111827; margin:.3rem 0; }
.stat-card .sub { font-size:.85rem; color:#9ca3af; }
.chart-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.5rem; margin-bottom:2rem; }
.chart-card h3 { font-size:1rem; font-weight:700; color:#374151; margin-bottom:1.5rem; }
.table-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; }
.table-card table { width:100%; border-collapse:collapse; }
.table-card th { padding:.9rem 1.2rem; background:#f9fafb; font-size:.8rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; text-align:left; border-bottom:1px solid #e5e7eb; }
.table-card td { padding:.9rem 1.2rem; border-bottom:1px solid #f3f4f6; font-size:.9rem; color:#374151; }
.export-row { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:2rem; }
@media (max-width:900px) {
    .dashboard-content { padding: 1.6rem; }
}
</style>
@endpush

@section('content')
<section class="report-shell">
<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Sales & System Reports</h1>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.reports.index') }}">
<div class="filter-bar">
    <div>
        <label>Year</label>
        <select name="year">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Month (optional)</label>
        <select name="month">
            <option value="">All Months</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
</div>
</form>

{{-- Summary Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="label">Total Revenue</div>
        <div class="value">₱{{ number_format($totalRevenue, 2) }}</div>
        <div class="sub">{{ $year }}{{ $month ? ' / ' . date('F', mktime(0,0,0,$month,1)) : '' }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Orders</div>
        <div class="value">{{ number_format($totalOrders) }}</div>
        <div class="sub">All statuses</div>
    </div>
    <div class="stat-card">
        <div class="label">Paid Orders</div>
        <div class="value">{{ number_format($paidOrders) }}</div>
        <div class="sub">Completed / Delivered</div>
    </div>
    <div class="stat-card">
        <div class="label">Pending Orders</div>
        <div class="value">{{ number_format($pendingOrders) }}</div>
        <div class="sub">Awaiting payment</div>
    </div>
</div>

{{-- Export Buttons --}}
<div class="export-row">
    <a href="{{ route('admin.reports.export.sales', ['year' => $year, 'month' => $month]) }}" class="btn btn-export">
        <i class="fas fa-download"></i> Export Sales CSV
    </a>
    <a href="{{ route('admin.reports.export.payments', ['year' => $year, 'month' => $month]) }}" class="btn btn-export" style="background:#0369a1">
        <i class="fas fa-download"></i> Export Payment History CSV
    </a>
</div>

{{-- Monthly Revenue Chart --}}
<div class="chart-card">
    <h3>Monthly Revenue — {{ $year }}</h3>
    <canvas id="monthlyChart" height="80"></canvas>
</div>

{{-- Yearly Comparison --}}
<div class="chart-card">
    <h3>Yearly Revenue Comparison</h3>
    <canvas id="yearlyChart" height="60"></canvas>
</div>

{{-- Top Products --}}
<div class="table-card" style="margin-bottom:2rem">
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty Sold</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
        @forelse($topProducts as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ number_format($p->total_qty) }}</td>
                <td>₱{{ number_format($p->revenue, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" style="text-align:center;color:#9ca3af;padding:2rem">No sales data for this period.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyRevenue = @json(array_values($monthlyData));
const monthlyCount   = @json(array_values($monthlyCount));

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (₱)',
            data: monthlyRevenue,
            backgroundColor: 'rgba(45,80,22,0.7)',
            borderRadius: 6,
        }, {
            label: 'Orders',
            data: monthlyCount,
            type: 'line',
            borderColor: '#16a34a',
            backgroundColor: 'transparent',
            yAxisID: 'y2',
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y:  { beginAtZero: true, title: { display:true, text:'Revenue (₱)' }},
            y2: { beginAtZero: true, position:'right', title: { display:true, text:'Orders' }, grid: { drawOnChartArea:false } }
        }
    }
});

const yearlyLabels  = @json($yearlySales->pluck('year'));
const yearlyTotals  = @json($yearlySales->pluck('total')->map(fn($v) => (float)$v));
const yearlyCounts  = @json($yearlySales->pluck('count'));

new Chart(document.getElementById('yearlyChart'), {
    type: 'bar',
    data: {
        labels: yearlyLabels,
        datasets: [{
            label: 'Revenue (₱)',
            data: yearlyTotals,
            backgroundColor: 'rgba(22,163,74,0.7)',
            borderRadius: 6,
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
