<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $stockService = app(StockService::class);
        $query = Item::with(['category', 'supplier']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->where('is_active', true)->orderBy('name')->paginate(50)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $items->getCollection()->transform(function ($item) use ($stockService, $request) {
            $item->current_stock = $stockService->getCurrentStock($item);
            return $item;
        });

        if ($status = $request->input('stock_status')) {
            $items->setCollection($items->getCollection()->filter(function ($item) use ($status, $stockService) {
                $stock = $item->current_stock ?? $stockService->getCurrentStock($item);
                return match ($status) {
                    'out' => $stock <= 0,
                    'low' => $stock > 0 && $stock <= $item->reorder_point,
                    'in' => $stock > $item->reorder_point,
                    default => true,
                };
            })->values());
        }

        return view('stock.index', compact('items', 'categories'));
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['item', 'user']);

        if ($itemId = $request->input('item_id')) {
            $query->where('item_id', $itemId);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($direction = $request->input('direction')) {
            $query->where('direction', $direction);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $movements = $query->latest()->paginate(50)->withQueryString();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('stock.movements', compact('movements', 'items'));
    }

    public function getItemStock(Item $item)
    {
        $stockService = app(StockService::class);
        $currentStock = $stockService->getCurrentStock($item);

        return response()->json([
            'item_id' => $item->id,
            'name' => $item->name,
            'sku' => $item->sku,
            'current_stock' => $currentStock,
            'reorder_point' => $item->reorder_point,
            'min_stock' => $item->min_stock,
            'max_stock' => $item->max_stock,
            'is_low_stock' => $item->isLowStock(),
        ]);
    }

    public function export(Request $request)
    {
        $stockService = app(StockService::class);
        $items = Item::with(['category', 'supplier'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($item) use ($stockService) {
                $item->current_stock = $stockService->getCurrentStock($item);
                return $item;
            });

        $filename = 'stock_levels_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'SKU', 'Category', 'Supplier', 'Cost Price', 'Selling Price', 'Current Stock', 'Min Stock', 'Max Stock', 'Reorder Point']);
            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->name,
                    $item->sku,
                    $item->category->name ?? '',
                    $item->supplier->name ?? '',
                    $item->cost_price,
                    $item->selling_price,
                    $item->current_stock,
                    $item->min_stock,
                    $item->max_stock,
                    $item->reorder_point,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
