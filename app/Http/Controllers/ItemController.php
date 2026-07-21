<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Services\ActivityService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'supplier']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $stockService = app(StockService::class);
        $items = $query->orderBy('name')->paginate(25)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        $items->getCollection()->transform(function ($item) use ($stockService) {
            $item->current_stock = $stockService->getCurrentStock($item);
            return $item;
        });

        return view('items.index', compact('items', 'categories', 'suppliers'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('items.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:items,sku',
            'barcode' => 'nullable|string|max:100|unique:items,barcode',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string|max:1000',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'unit' => 'nullable|string|max:50',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        DB::beginTransaction();
        try {
            $item = Item::create($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_item',
                subject: $item,
                description: "Created item: {$item->name}",
                newValues: $item->toArray()
            );

            DB::commit();
            return redirect()->route('items.show', $item)->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create item: ' . $e->getMessage());
        }
    }

    public function show(Item $item)
    {
        $item->load(['category', 'supplier', 'stockMovements' => function ($q) {
            $q->with('user')->latest()->limit(20);
        }]);

        $stockService = app(StockService::class);
        $item->current_stock = $stockService->getCurrentStock($item);

        $recentPurchases = $item->purchaseItems()
            ->with('purchase')
            ->latest()
            ->limit(10)
            ->get();

        $recentSales = $item->saleItems()
            ->with('sale')
            ->latest()
            ->limit(10)
            ->get();

        return view('items.show', compact('item', 'recentPurchases', 'recentSales'));
    }

    public function edit(Item $item)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('items.edit', compact('item', 'categories', 'suppliers'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:items,sku,' . $item->id,
            'barcode' => 'nullable|string|max:100|unique:items,barcode,' . $item->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string|max:1000',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'unit' => 'nullable|string|max:50',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        DB::beginTransaction();
        try {
            $oldValues = $item->toArray();
            $item->update($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_item',
                subject: $item,
                description: "Updated item: {$item->name}",
                oldValues: $oldValues,
                newValues: $item->toArray()
            );

            DB::commit();
            return redirect()->route('items.show', $item)->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update item: ' . $e->getMessage());
        }
    }

    public function destroy(Item $item)
    {
        $hasTransactions = $item->purchaseItems()->exists() || $item->saleItems()->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Cannot delete item with existing transactions. You can deactivate it instead.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_item',
                subject: $item,
                description: "Deleted item: {$item->name}",
                oldValues: $item->toArray()
            );

            $item->delete();
            DB::commit();
            return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');
        $categoryId = $request->input('category');
        $stockService = app(StockService::class);

        $items = Item::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->with('category')
            ->limit(50)
            ->get()
            ->map(function ($item) use ($stockService) {
                $item->current_stock = $stockService->getCurrentStock($item);
                return $item;
            });

        return response()->json($items);
    }
}
