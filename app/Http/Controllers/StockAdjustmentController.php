<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ActivityService;
use App\Services\NotificationService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function create()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('stock.adjust', compact('items'));
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'type' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $stockService = app(StockService::class);
            $item = Item::find($validated['item_id']);
            $oldStock = $stockService->getCurrentStock($item);

            $stockService->adjustStock(
                item: $item,
                quantity: $validated['quantity'],
                type: $validated['type'],
                notes: $validated['notes'],
                userId: auth()->id()
            );

            $newStock = $stockService->getCurrentStock($item);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'adjust_stock',
                subject: $item,
                description: "Stock adjusted for {$item->name}: {$oldStock} -> {$newStock} ({$validated['type']})"
            );

            $notificationService = app(NotificationService::class);
            $notificationService->sendStockAdjustmentNotification($item, $oldStock, $newStock);

            if ($item->isLowStock()) {
                $notificationService->sendLowStockAlert($item, $newStock);
            }

            DB::commit();
            return redirect()->route('stock.index')->with('success', "Stock adjusted for {$item->name}. New stock: {$newStock}");
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }
}
