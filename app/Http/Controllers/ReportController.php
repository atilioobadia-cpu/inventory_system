<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $query = Sale::with(['customer', 'createdBy'])
            ->withCount('items')
            ->whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to);

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->latest('sale_date')
            ->paginate(50)
            ->withQueryString();

        $summary = Sale::whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to)
            ->selectRaw('COUNT(*) as count, SUM(total_before_tax) as subtotal, SUM(vat_amount) as tax, SUM(discount_amount) as discount, SUM(total_after_tax) as total, SUM(paid_amount) as paid, SUM(due_amount) as due')
            ->first();

        $totalSales = $summary->total ?? 0;
        $totalItemsSold = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereNull('sales.voided_at')
            ->whereDate('sales.sale_date', '>=', $from)
            ->whereDate('sales.sale_date', '<=', $to)
            ->sum('sale_items.quantity');
        $averageSale = $summary->count > 0 ? $totalSales / $summary->count : 0;
        $vatCollected = $summary->tax ?? 0;

        $chartCollection = Sale::whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to)
            ->select(DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d') as date"), DB::raw('SUM(total_after_tax) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = $chartCollection->keys()->toArray();
        $chartData = $chartCollection->values()->toArray();

        $customers = Customer::orderBy('name')->get();

        return view('reports.sales', compact('sales', 'summary', 'from', 'to', 'totalSales', 'totalItemsSold', 'averageSale', 'vatCollected', 'chartLabels', 'chartData', 'customers'));
    }

    public function purchases(Request $request)
    {
        $from = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to_date', now()->format('Y-m-d'));

        $query = Purchase::with(['supplier', 'createdBy'])
            ->withCount('items')
            ->where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to);

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        $purchases = $query->latest('purchase_date')
            ->paginate(50)
            ->withQueryString();

        $summary = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->selectRaw('COUNT(*) as count, SUM(subtotal) as subtotal, SUM(tax_amount) as tax, SUM(discount_amount) as discount, SUM(total_amount) as total, SUM(paid_amount) as paid, SUM(due_amount) as due')
            ->first();

        $totalPurchases = $summary->total ?? 0;
        $itemsReceived = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', '!=', 'cancelled')
            ->whereDate('purchases.purchase_date', '>=', $from)
            ->whereDate('purchases.purchase_date', '<=', $to)
            ->sum('purchase_items.received_quantity');
        $averagePurchase = $summary->count > 0 ? $totalPurchases / $summary->count : 0;
        $vatPaid = $summary->tax ?? 0;

        $chartCollection = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->select(DB::raw("DATE_FORMAT(purchase_date, '%Y-%m-%d') as date"), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = $chartCollection->keys()->toArray();
        $chartData = $chartCollection->values()->toArray();

        $suppliers = Supplier::orderBy('name')->get();

        return view('reports.purchases', compact('purchases', 'summary', 'from', 'to', 'suppliers', 'totalPurchases', 'itemsReceived', 'averagePurchase', 'vatPaid', 'chartLabels', 'chartData'));
    }

    public function inventory(Request $request)
    {
        $stockService = app(StockService::class);

        $query = Item::with(['category', 'supplier'])
            ->where('is_active', true);

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->orderBy('name')->paginate(100)->withQueryString();

        $items->getCollection()->transform(function ($item) use ($stockService) {
            $item->current_stock = $stockService->getCurrentStock($item);
            $item->stock_value = $item->current_stock * $item->cost_price;
            return $item;
        });

        $totalValue = $items->getCollection()->sum('stock_value');
        $totalRetailValue = $items->getCollection()->sum(function ($item) {
            return $item->current_stock * $item->selling_price;
        });
        $totalItems = $items->total();
        $lowStockCount = $items->getCollection()->filter(fn($item) => $item->current_stock <= $item->reorder_point && $item->current_stock > 0)->count();
        $outOfStockCount = $items->getCollection()->filter(fn($item) => $item->current_stock <= 0)->count();

        $categories = \App\Models\Category::orderBy('name')->get();

        $categoryBreakdown = $items->getCollection()
            ->groupBy(fn($item) => $item->category->name ?? 'Uncategorized')
            ->map(fn($group) => ['name' => $group->first()->category->name ?? 'Uncategorized', 'count' => $group->count()])
            ->values()
            ->toArray();

        return view('reports.inventory', compact('items', 'totalValue', 'totalItems', 'lowStockCount', 'outOfStockCount', 'totalRetailValue', 'categories', 'categoryBreakdown'));
    }

    public function expenses(Request $request)
    {
        $from = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to_date', now()->format('Y-m-d'));

        $expenses = Expense::with(['category', 'createdBy'])
            ->whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->latest('expense_date')
            ->paginate(50)
            ->withQueryString();

        $summary = Expense::whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->selectRaw('COUNT(*) as count, SUM(amount) as total')
            ->first();

        $byCategory = Expense::select('expense_categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->whereDate('expenses.expense_date', '>=', $from)
            ->whereDate('expenses.expense_date', '<=', $to)
            ->groupBy('expense_categories.name')
            ->orderByDesc('total')
            ->get();

        $totalExpenses = $summary->total ?? 0;
        $averageExpense = $summary->count > 0 ? $totalExpenses / $summary->count : 0;
        $topCategory = $byCategory->first()->name ?? '-';

        $categoryLabels = $byCategory->pluck('name')->toArray();
        $categoryAmounts = $byCategory->pluck('total')->toArray();

        $monthlyData = Expense::select(
                DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyLabels = $monthlyData->keys()->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'))->toArray();
        $monthlyAmounts = $monthlyData->values()->toArray();

        $expenseCategories = \App\Models\ExpenseCategory::orderBy('name')->get();

        return view('reports.expenses', compact('expenses', 'summary', 'byCategory', 'from', 'to', 'totalExpenses', 'averageExpense', 'topCategory', 'categoryLabels', 'categoryAmounts', 'monthlyLabels', 'monthlyAmounts', 'expenseCategories'));
    }

    public function profitLoss(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $totalSales = Sale::whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to)
            ->sum('total_amount');

        $totalCost = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereNull('sales.voided_at')
            ->whereDate('sales.sale_date', '>=', $from)
            ->whereDate('sales.sale_date', '<=', $to)
            ->selectRaw('SUM(sale_items.cost_price * sale_items.quantity) as total')
            ->value('total') ?? 0;

        $totalExpenses = Expense::whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->sum('amount');

        $grossProfit = $totalSales - $totalCost;
        $netProfit = $grossProfit - $totalExpenses;

        $monthlyData = Sale::whereNull('voided_at')
            ->where('sale_date', '>=', $from)
            ->where('sale_date', '<=', $to)
            ->select(
                DB::raw("DATE_FORMAT(sale_date, '%Y-%m') as month"),
                DB::raw('SUM(total_amount) as sales')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $returns = 0;
        $netRevenue = $totalSales;
        $openingStock = 0;
        $purchases = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->sum('total_amount');
        $closingStock = $stockService = app(StockService::class);
        $items = Item::where('is_active', true)->get();
        $closingStock = $items->sum(function ($item) use ($stockService) {
            return $stockService->getCurrentStock($item) * $item->cost_price;
        });
        $totalCogs = $totalCost;
        $expenseBreakdown = Expense::select('expense_categories.name', DB::raw('SUM(expenses.amount) as amount'))
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->whereDate('expenses.expense_date', '>=', $from)
            ->whereDate('expenses.expense_date', '<=', $to)
            ->groupBy('expense_categories.name')
            ->get()
            ->toArray();

        $monthlyLabels = $monthlyData->pluck('month')->toArray();
        $monthlyRevenue = $monthlyData->pluck('sales')->toArray();
        $monthlyCogs = [];
        $monthlyExpenses = [];

        return view('reports.profit-loss', compact(
            'totalSales', 'totalCost', 'totalExpenses', 'grossProfit', 'netProfit',
            'returns', 'netRevenue', 'openingStock', 'purchases', 'closingStock', 'totalCogs',
            'expenseBreakdown', 'monthlyLabels', 'monthlyRevenue', 'monthlyCogs', 'monthlyExpenses',
            'from', 'to'
        ));
    }

    public function tax(Request $request)
    {
        $from = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to_date', now()->format('Y-m-d'));

        $salesTax = Sale::whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to)
            ->sum('tax_amount');

        $purchaseTax = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->sum('tax_amount');

        $netTax = $salesTax - $purchaseTax;

        $salesVat = $salesTax;
        $purchaseVat = $purchaseTax;
        $netVat = $netTax;

        $salesByMonth = Sale::whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to)
            ->select(
                DB::raw("DATE_FORMAT(sale_date, '%Y-%m') as month"),
                DB::raw('SUM(tax_amount) as sales_vat')
            )
            ->groupBy('month')
            ->pluck('sales_vat', 'month');

        $purchasesByMonth = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->select(
                DB::raw("DATE_FORMAT(purchase_date, '%Y-%m') as month"),
                DB::raw('SUM(tax_amount) as purchase_vat')
            )
            ->groupBy('month')
            ->pluck('purchase_vat', 'month');

        $allMonths = $salesByMonth->keys()->merge($purchasesByMonth->keys())->unique()->sort()->values();

        $monthlyVat = $allMonths->map(function ($month) use ($salesByMonth, $purchasesByMonth) {
            $salesVat = $salesByMonth->get($month, 0);
            $purchaseVat = $purchasesByMonth->get($month, 0);
            return [
                'month' => $month,
                'label' => \Carbon\Carbon::parse($month . '-01')->format('M Y'),
                'sales_vat' => $salesVat,
                'purchase_vat' => $purchaseVat,
                'net' => $salesVat - $purchaseVat,
            ];
        })->toArray();

        $vatByRate = [];

        return view('reports.tax', compact('salesTax', 'purchaseTax', 'netTax', 'salesVat', 'purchaseVat', 'netVat', 'monthlyVat', 'vatByRate', 'from', 'to'));
    }

    public function customers(Request $request)
    {
        $customers = Customer::withCount(['sales' => function ($q) {
            $q->whereNull('voided_at');
        }])
        ->withSum(['sales' => function ($q) {
            $q->whereNull('voided_at');
        }], 'total_amount')
        ->withSum(['sales' => function ($q) {
            $q->whereNull('voided_at');
        }], 'paid_amount')
        ->orderByDesc('sales_sum_total_amount')
        ->paginate(25);

        $customers->getCollection()->transform(function ($customer) {
            $customer->total_sales = $customer->sales_sum_total_amount ?? 0;
            $customer->total_paid = $customer->sales_sum_paid_amount ?? 0;
            $customer->balance = $customer->total_sales - $customer->total_paid;
            $customer->last_purchase = $customer->sales()->whereNull('voided_at')->latest('sale_date')->value('sale_date');
            return $customer;
        });

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_active', true)->count();
        $totalSales = Sale::whereNull('voided_at')->sum('total_amount');
        $outstandingBalances = Sale::whereNull('voided_at')->where('due_amount', '>', 0)->sum('due_amount');

        return view('reports.customers', compact('customers', 'totalCustomers', 'activeCustomers', 'totalSales', 'outstandingBalances'));
    }

    public function suppliers(Request $request)
    {
        $suppliers = Supplier::withCount('purchases')
            ->withSum('purchases', 'total_amount')
            ->withSum('purchases', 'paid_amount')
            ->orderByDesc('purchases_sum_total_amount')
            ->paginate(25);

        $suppliers->getCollection()->transform(function ($supplier) {
            $supplier->total_purchases = $supplier->purchases_sum_total_amount ?? 0;
            $supplier->total_paid = $supplier->purchases_sum_paid_amount ?? 0;
            $supplier->balance = $supplier->total_purchases - $supplier->total_paid;
            $supplier->last_purchase = $supplier->purchases()->where('status', '!=', 'cancelled')->latest('purchase_date')->value('purchase_date');
            return $supplier;
        });

        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $totalPurchases = Purchase::where('status', '!=', 'cancelled')->sum('total_amount');
        $outstanding = Purchase::where('status', '!=', 'cancelled')->where('due_amount', '>', 0)->sum('due_amount');

        return view('reports.suppliers', compact('suppliers', 'totalSuppliers', 'activeSuppliers', 'totalPurchases', 'outstanding'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $from = $request->input('from');
        $to = $request->input('to');

        $filename = "{$type}_report_{$from}_{$to}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $from, $to) {
            $handle = fopen('php://output', 'w');

            switch ($type) {
                case 'sales':
                    fputcsv($handle, ['Invoice #', 'Date', 'Customer', 'Subtotal', 'Tax', 'Discount', 'Total', 'Paid', 'Due', 'Status']);
                    $sales = Sale::with('customer')->whereNull('voided_at')
                        ->whereDate('sale_date', '>=', $from)->whereDate('sale_date', '<=', $to)->get();
                    foreach ($sales as $sale) {
                        fputcsv($handle, [
                            $sale->invoice_number,
                            $sale->sale_date->format('d/m/Y'),
                            $sale->customer->name ?? 'Walk-in',
                            $sale->total_before_tax,
                            $sale->vat_amount,
                            $sale->discount_amount,
                            $sale->total_after_tax,
                            $sale->paid_amount,
                            $sale->due_amount,
                            $sale->payment_status,
                        ]);
                    }
                    break;

                case 'purchases':
                    fputcsv($handle, ['Invoice #', 'Date', 'Supplier', 'Subtotal', 'Tax', 'Discount', 'Total', 'Paid', 'Due', 'Status']);
                    $purchases = Purchase::with('supplier')->where('status', '!=', 'cancelled')
                        ->whereDate('purchase_date', '>=', $from)->whereDate('purchase_date', '<=', $to)->get();
                    foreach ($purchases as $purchase) {
                        fputcsv($handle, [
                            $purchase->invoice_number,
                            $purchase->purchase_date->format('d/m/Y'),
                            $purchase->supplier->name,
                            $purchase->subtotal,
                            $purchase->tax_amount,
                            $purchase->discount_amount,
                            $purchase->total_amount,
                            $purchase->paid_amount,
                            $purchase->due_amount,
                            $purchase->status,
                        ]);
                    }
                    break;

                case 'inventory':
                    $stockService = app(StockService::class);
                    fputcsv($handle, ['SKU', 'Name', 'Category', 'Stock', 'Cost Price', 'Selling Price', 'Stock Value']);
                    $items = Item::with('category')->where('is_active', true)->get();
                    foreach ($items as $item) {
                        $stock = $stockService->getCurrentStock($item);
                        fputcsv($handle, [
                            $item->sku,
                            $item->name,
                            $item->category->name ?? 'N/A',
                            $stock,
                            $item->cost_price,
                            $item->selling_price,
                            $stock * $item->cost_price,
                        ]);
                    }
                    break;

                case 'expenses':
                    fputcsv($handle, ['Ref #', 'Date', 'Category', 'Description', 'Amount', 'Payment Method', 'Status']);
                    $expenses = Expense::with('category')
                        ->whereDate('expense_date', '>=', $from)->whereDate('expense_date', '<=', $to)->get();
                    foreach ($expenses as $expense) {
                        fputcsv($handle, [
                            $expense->reference_number,
                            $expense->expense_date->format('d/m/Y'),
                            $expense->category->name,
                            $expense->description,
                            $expense->amount,
                            $expense->payment_method,
                            $expense->status,
                        ]);
                    }
                    break;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
