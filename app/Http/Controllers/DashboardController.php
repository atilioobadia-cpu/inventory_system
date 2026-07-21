<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sale;
use App\Services\ActivityService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stockService = app(StockService::class);
        $activityService = app(ActivityService::class);

        $totalItems = Item::where('is_active', true)->count();
        $stockValue = $stockService->getStockValue();
        $lowStockItems = $stockService->getLowStockItems();
        $lowStockCount = $lowStockItems->count();

        $todaySales = Sale::whereDate('sale_date', today())->whereNull('voided_at');
        $todaySalesCount = $todaySales->count();
        $todaySalesAmount = (clone $todaySales)->sum('total_amount');

        $todayPurchases = Purchase::whereDate('purchase_date', today())->where('status', '!=', 'cancelled');
        $todayPurchasesCount = $todayPurchases->count();
        $todayPurchasesAmount = (clone $todayPurchases)->sum('total_amount');

        $pendingPurchases = Purchase::where('status', 'pending')->count();

        $recentActivities = $activityService->getRecent(10);

        $monthlySales = Sale::whereNull('voided_at')
            ->where('sale_date', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(sale_date, '%Y-%m') as month"),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyLabels = $monthlySales->pluck('month')->toArray();
        $monthlySalesData = $monthlySales->pluck('total')->toArray();

        $topSellingItems = Item::join('sale_items', 'items.id', '=', 'sale_items.item_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereNull('sales.voided_at')
            ->where('sales.sale_date', '>=', now()->subMonths(3))
            ->select('items.*', DB::raw('SUM(sale_items.quantity) as total_sold'), DB::raw('SUM(sale_items.total) as total_revenue'))
            ->groupBy('items.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalItems',
            'stockValue',
            'todaySalesCount',
            'todaySalesAmount',
            'todayPurchasesCount',
            'todayPurchasesAmount',
            'lowStockCount',
            'lowStockItems',
            'pendingPurchases',
            'recentActivities',
            'monthlySales',
            'topSellingItems',
            'monthlyLabels',
            'monthlySalesData'
        ));
    }
}
