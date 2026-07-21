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
            ->selectRaw('COUNT(*) as count, SUM(subtotal) as subtotal, SUM(tax_amount) as tax, SUM(discount_amount) as discount, SUM(total_amount) as total, SUM(paid_amount) as paid, SUM(due_amount) as due')
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
            ->select(DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d') as date"), DB::raw('SUM(total_amount) as total'))
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
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $purchases = Purchase::with(['supplier', 'createdBy'])
            ->where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->latest('purchase_date')
            ->paginate(50)
            ->withQueryString();

        $summary = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->selectRaw('COUNT(*) as count, SUM(subtotal) as subtotal, SUM(tax_amount) as tax, SUM(discount_amount) as discount, SUM(total_amount) as total, SUM(paid_amount) as paid, SUM(due_amount) as due')
            ->first();

        return view('reports.purchases', compact('purchases', 'summary', 'from', 'to'));
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
        $totalItems = $items->total();
        $lowStockCount = $items->getCollection()->filter(fn($item) => $item->current_stock <= $item->reorder_point)->count();

        return view('reports.inventory', compact('items', 'totalValue', 'totalItems', 'lowStockCount'));
    }

    public function expenses(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

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

        return view('reports.expenses', compact('expenses', 'summary', 'byCategory', 'from', 'to'));
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

        return view('reports.profit-loss', compact('totalSales', 'totalCost', 'totalExpenses', 'grossProfit', 'netProfit', 'monthlyData', 'from', 'to'));
    }

    public function tax(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $salesTax = Sale::whereNull('voided_at')
            ->whereDate('sale_date', '>=', $from)
            ->whereDate('sale_date', '<=', $to)
            ->sum('tax_amount');

        $purchaseTax = Purchase::where('status', '!=', 'cancelled')
            ->whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->sum('tax_amount');

        $netTax = $salesTax - $purchaseTax;

        return view('reports.tax', compact('salesTax', 'purchaseTax', 'netTax', 'from', 'to'));
    }

    public function customers(Request $request)
    {
        $customers = Customer::withCount(['sales' => function ($q) {
            $q->whereNull('voided_at');
        }])
        ->withSum(['sales' => function ($q) {
            $q->whereNull('voided_at');
        }], 'total_amount')
        ->orderByDesc('sales_sum_total_amount')
        ->paginate(25);

        return view('reports.customers', compact('customers'));
    }

    public function suppliers(Request $request)
    {
        $suppliers = Supplier::withCount('purchases')
            ->withSum('purchases', 'total_amount')
            ->orderByDesc('purchases_sum_total_amount')
            ->paginate(25);

        return view('reports.suppliers', compact('suppliers'));
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
                            $sale->subtotal,
                            $sale->tax_amount,
                            $sale->discount_amount,
                            $sale->total_amount,
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
