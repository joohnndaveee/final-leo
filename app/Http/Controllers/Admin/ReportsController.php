<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SellerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Sales & system reports page
     */
    public function index(Request $request)
    {
        $year  = $request->input('year',  now()->year);
        $month = $request->input('month', null);

        // --- Monthly sales for selected year ---
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereIn('payment_status', ['paid', 'completed', 'delivered', 'complete'])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyData  = [];
        $monthlyCount = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m]  = isset($monthlySales[$m]) ? (float) $monthlySales[$m]->total : 0;
            $monthlyCount[$m] = isset($monthlySales[$m]) ? (int)   $monthlySales[$m]->count : 0;
        }

        // --- Yearly totals (last 5 years) ---
        $yearlySales = Order::selectRaw('YEAR(created_at) as year, SUM(total_price) as total, COUNT(*) as count')
            ->where('created_at', '>=', now()->subYears(5))
            ->whereIn('payment_status', ['paid', 'completed', 'delivered', 'complete'])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();

        // --- Top selling products ---
        $topProducts = OrderItem::selectRaw('order_items.product_id, order_items.name, SUM(order_items.quantity) as total_qty, SUM(order_items.price * order_items.quantity) as revenue')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($month, fn($q) => $q->whereMonth('orders.created_at', $month))
            ->whereYear('orders.created_at', $year)
            ->groupBy('order_items.product_id', 'order_items.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // --- Summary stats ---
        $query = Order::when($month, fn($q) => $q->whereMonth('created_at', $month))
                      ->whereYear('created_at', $year);

        $totalRevenue = (clone $query)->whereIn('payment_status', ['paid','completed','delivered','complete'])->sum('total_price');
        $totalOrders  = (clone $query)->count();
        $paidOrders   = (clone $query)->whereIn('payment_status', ['paid','completed','delivered','complete'])->count();
        $pendingOrders = (clone $query)->where('payment_status', 'pending')->count();

        // --- Payment history for export ---
        $payments = SellerPayment::with('seller')
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->whereYear('created_at', $year)
            ->orderByDesc('created_at')
            ->get();

        $availableYears = range(now()->year, max(2024, now()->year - 4));

        return view('admin.reports', compact(
            'year', 'month',
            'monthlyData', 'monthlyCount',
            'yearlySales', 'topProducts',
            'totalRevenue', 'totalOrders', 'paidOrders', 'pendingOrders',
            'payments', 'availableYears'
        ));
    }

    /**
     * Export payment history as CSV
     */
    public function exportPayments(Request $request)
    {
        $year  = $request->input('year',  now()->year);
        $month = $request->input('month', null);

        $payments = SellerPayment::with('seller')
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->whereYear('created_at', $year)
            ->orderByDesc('created_at')
            ->get();

        $filename = 'payment_history_' . $year . ($month ? "_month{$month}" : '') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Seller', 'Email', 'Type', 'Amount', 'Method', 'Status', 'Reference', 'Paid At', 'Created At']);

            foreach ($payments as $p) {
                fputcsv($handle, [
                    $p->id,
                    optional($p->seller)->name,
                    optional($p->seller)->email,
                    $p->payment_type,
                    $p->amount,
                    $p->payment_method,
                    $p->payment_status,
                    $p->reference_number,
                    $p->paid_at,
                    $p->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export sales report as CSV
     */
    public function exportSales(Request $request)
    {
        $year  = $request->input('year',  now()->year);
        $month = $request->input('month', null);

        $orders = Order::with('user')
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->whereYear('created_at', $year)
            ->whereIn('payment_status', ['paid','completed','delivered','complete'])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'sales_report_' . $year . ($month ? "_month{$month}" : '') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Customer', 'Email', 'Products', 'Total', 'Payment Method', 'Payment Status', 'Order Status', 'Date']);

            foreach ($orders as $o) {
                fputcsv($handle, [
                    $o->id,
                    $o->name,
                    $o->email,
                    $o->total_products,
                    $o->total_price,
                    $o->method,
                    $o->payment_status,
                    $o->status,
                    $o->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
