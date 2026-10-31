<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PreOrder;
use App\Models\SalesHistory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        // Latest sales reset date
        $lastReset = SalesHistory::max('reset_at');

        // Total Products
        $totalProducts = Product::count();

        // Total Pre-Orders - Current Period
        $totalPreOrders = PreOrder::when($lastReset, function ($query) use ($lastReset) {
            $query->where('created_at', '>', $lastReset);
        })->count();

        // Pending Orders - Current Period
        $pendingOrders = PreOrder::where('status', 'Pending')
            ->when($lastReset, function ($query) use ($lastReset) {
                $query->where('created_at', '>', $lastReset);
            })
            ->count();

        // Current Sales
        $currentSalesQuery = PreOrder::where('status', 'Completed')
            ->whereNotNull('completed_at');

        if ($lastReset) {
            $currentSalesQuery->where('completed_at', '>', $lastReset);
        }

        $totalSales = $currentSalesQuery->sum('total_amount');

        // Recent Pre-Orders - Current Period
        $recentOrders = PreOrder::when($lastReset, function ($query) use ($lastReset) {
            $query->where('created_at', '>', $lastReset);
        })
            ->latest()
            ->take(5)
            ->get();

        // Monthly Sales - Current Sales Period
        $monthlySales = PreOrder::selectRaw(
                'MONTH(completed_at) as month, SUM(total_amount) as total'
            )
            ->where('status', 'Completed')
            ->whereNotNull('completed_at')
            ->when($lastReset, function ($query) use ($lastReset) {
                $query->where('completed_at', '>', $lastReset);
            })
            ->whereYear('completed_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $salesLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        $salesData = [];

        for ($i = 1; $i <= 12; $i++) {
            $salesData[] = $monthlySales[$i] ?? 0;
        }

        // Order Status Distribution - Current Period
        $statuses = [
            'Pending',
            'Approved',
            'Ready for Pickup',
            'Completed',
            'Cancelled'
        ];

        $statusLabels = $statuses;
        $statusData = [];

        foreach ($statuses as $status) {
            $statusData[] = PreOrder::where('status', $status)
                ->when($lastReset, function ($query) use ($lastReset) {
                    $query->where('created_at', '>', $lastReset);
                })
                ->count();
        }

        // Top 5 Selling Products - Current Sales Period
        $topProducts = DB::table('pre_order_items')
            ->join(
                'pre_orders',
                'pre_order_items.pre_order_id',
                '=',
                'pre_orders.id'
            )
            ->where('pre_orders.status', 'Completed')
            ->whereNotNull('pre_orders.completed_at')
            ->when($lastReset, function ($query) use ($lastReset) {
                $query->where('pre_orders.completed_at', '>', $lastReset);
            })
            ->select(
                'pre_order_items.product_name',
                DB::raw('SUM(pre_order_items.quantity) as total_sold')
            )
            ->groupBy('pre_order_items.product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $productLabels = $topProducts->pluck('product_name');
        $productData = $topProducts->pluck('total_sold');

        // Sales History
        $salesHistories = SalesHistory::latest('reset_at')->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalPreOrders',
            'pendingOrders',
            'totalSales',
            'recentOrders',
            'salesLabels',
            'salesData',
            'statusLabels',
            'statusData',
            'productLabels',
            'productData',
            'salesHistories'
        ));
    }
}
