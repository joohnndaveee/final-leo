<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $seller   = Auth::guard('seller')->user();
        $sellerId = $seller->id;
        $year     = $request->input('year',  now()->year);
        $period   = $request->input('period', 'monthly'); // daily | monthly

        // --- Monthly revenue for selected year ---
        $monthlySalesQuery = OrderItem::selectRaw('MONTH(orders.created_at) as month, SUM(order_items.price * order_items.quantity) as revenue, COUNT(DISTINCT order_items.order_id) as orders')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->whereYear('orders.created_at', $year)
            ->groupBy(DB::raw('MONTH(orders.created_at)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyRevenue = [];
        $monthlyOrders  = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[$m] = isset($monthlySalesQuery[$m]) ? (float) $monthlySalesQuery[$m]->revenue : 0;
            $monthlyOrders[$m]  = isset($monthlySalesQuery[$m]) ? (int)   $monthlySalesQuery[$m]->orders  : 0;
        }

        // --- Daily revenue for current month ---
        $currentMonth = $request->input('month', now()->month);
        $daysInMonth  = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $year);

        $dailySalesQuery = OrderItem::selectRaw('DAY(orders.created_at) as day, SUM(order_items.price * order_items.quantity) as revenue')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $currentMonth)
            ->groupBy(DB::raw('DAY(orders.created_at)'))
            ->get()
            ->keyBy('day');

        $dailyRevenue = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyRevenue[$d] = isset($dailySalesQuery[$d]) ? (float) $dailySalesQuery[$d]->revenue : 0;
        }

        // --- Top selling products ---
        $topProducts = OrderItem::selectRaw('order_items.product_id, order_items.name, SUM(order_items.quantity) as total_qty, SUM(order_items.price * order_items.quantity) as revenue')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->whereYear('orders.created_at', $year)
            ->groupBy('order_items.product_id', 'order_items.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // --- Summary ---
        $totalRevenue = array_sum($monthlyRevenue);
        $totalOrders  = array_sum($monthlyOrders);
        $totalProducts = \App\Models\Product::where('seller_id', $sellerId)->count();

        $availableYears = range(now()->year, max(2024, now()->year - 4));

        return view('seller.analytics', compact(
            'seller', 'year', 'currentMonth',
            'monthlyRevenue', 'monthlyOrders',
            'dailyRevenue', 'daysInMonth',
            'topProducts',
            'totalRevenue', 'totalOrders', 'totalProducts',
            'availableYears'
        ));
    }

    /**
     * Export seller sales report as CSV
     */
    public function export(Request $request)
    {
        $seller   = Auth::guard('seller')->user();
        $sellerId = $seller->id;
        $year     = $request->input('year',  now()->year);
        $month    = $request->input('month', null);

        $items = OrderItem::with('order')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->whereYear('orders.created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('orders.created_at', $month))
            ->select('order_items.*', 'orders.created_at as order_date')
            ->orderByDesc('orders.created_at')
            ->get();

        $filename = 'sales_' . $seller->shop_name . '_' . $year . ($month ? "_m{$month}" : '') . '.csv';
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Product', 'Qty', 'Unit Price', 'Total', 'Order Status', 'Date']);
            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->order_id,
                    $item->name,
                    $item->quantity,
                    $item->price,
                    $item->price * $item->quantity,
                    optional($item->order)->status,
                    $item->order_date,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
