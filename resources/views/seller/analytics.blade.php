@extends('layouts.seller')

@section('title', 'Analytics - Seller Dashboard')

@push('styles')
<style>
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
.page-header h1 { font-size:1.8rem; font-weight:700; }
.filter-row { display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap; margin-bottom:2rem; }
.filter-row label { font-size:.8rem; font-weight:600; color:#6b7280; display:block; margin-bottom:.3rem; }
.filter-row select { padding:.55rem .9rem; border:1px solid #d1d5db; border-radius:8px; font-size:.9rem; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.2rem; border-radius:8px; font-size:.85rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:.2s; }
.btn-primary { background:#2d5016; color:#fff; }
.btn-export  { background:#16a34a; color:#fff; }
.stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1.5rem; margin-bottom:2rem; }
.stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.2rem 1.5rem; }
.stat-card .label { font-size:.75rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.06em; }
.stat-card .value { font-size:1.8rem; font-weight:800; color:#111827; margin:.25rem 0; }
.chart-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem; }
.chart-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.5rem; }
.chart-card h3 { font-size:.95rem; font-weight:700; color:#374151; margin-bottom:1.2rem; }
.table-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; }
.table-card h3 { font-size:.95rem; font-weight:700; padding:1rem 1.5rem; border-bottom:1px solid #f3f4f6; }
.table-card table { width:100%; border-collapse:collapse; }
.table-card th { padding:.8rem 1.2rem; background:#f9fafb; font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; text-align:left; border-bottom:1px solid #e5e7eb; }
.table-card td { padding:.8rem 1.2rem; border-bottom:1px solid #f3f4f6; font-size:.88rem; }
@media(max-width:768px){ .chart-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Analytics</h1>
    <a href="{{ route('seller.analytics.export', ['year' => $year, 'month' => $currentMonth]) }}" class="btn btn-export">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('seller.analytics.index') }}">
<div class="filter-row">
    <div>
        <label>Year</label>
        <select name="year">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Month (for daily view)</label>
        <select name="month">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>
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
    </div>
    <div class="stat-card">
        <div class="label">Total Orders</div>
        <div class="value">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Active Products</div>
        <div class="value">{{ $totalProducts }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Avg Order Value</div>
        <div class="value">₱{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2) : '0.00' }}</div>
    </div>
</div>

{{-- Charts --}}
<div class="chart-grid">
    <div class="chart-card">
        <h3>Monthly Revenue — {{ $year }}</h3>
        <canvas id="monthlyChart" height="140"></canvas>
    </div>
    <div class="chart-card">
        <h3>Daily Revenue — {{ date('F', mktime(0,0,0,$currentMonth,1)) }} {{ $year }}</h3>
        <canvas id="dailyChart" height="140"></canvas>
    </div>
</div>

{{-- Top Products --}}
<div class="table-card">
    <h3>Top Products</h3>
    <table>
        <thead>
            <tr><th>Product</th><th>Qty Sold</th><th>Revenue</th></tr>
        </thead>
        <tbody>
        @forelse($topProducts as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ number_format($p->total_qty) }}</td>
                <td>₱{{ number_format($p->revenue, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" style="text-align:center;color:#9ca3af;padding:2rem">No sales data available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyRev = @json(array_values($monthlyRevenue));
const monthlyOrd = @json(array_values($monthlyOrders));
const dailyRev   = @json(array_values($dailyRevenue));
const dailyLabels= @json(array_keys($dailyRevenue));

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (₱)', data: monthlyRev,
            backgroundColor: 'rgba(45,80,22,.7)', borderRadius: 5
        }, {
            label: 'Orders', data: monthlyOrd, type: 'line',
            borderColor: '#16a34a', backgroundColor: 'transparent',
            yAxisID: 'y2', tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y:  { beginAtZero: true },
            y2: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
        }
    }
});

new Chart(document.getElementById('dailyChart'), {
    type: 'bar',
    data: {
        labels: dailyLabels,
        datasets: [{ label: 'Revenue (₱)', data: dailyRev, backgroundColor: 'rgba(22,163,74,.7)', borderRadius: 5 }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
@endsection
