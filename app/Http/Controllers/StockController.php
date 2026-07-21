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
}
