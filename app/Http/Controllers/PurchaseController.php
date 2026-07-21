<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\ActivityService;
use App\Services\NotificationService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'createdBy']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('purchase_date', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('purchase_date', '<=', $to);
        }

        $purchases = $query->latest('purchase_date')->paginate(25)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $lineTotal = ($itemData['unit_cost'] * $itemData['quantity']) - ($itemData['discount'] ?? 0);
                $taxAmount += $lineTotal * ($item->tax_rate / 100);
                $subtotal += $lineTotal;
            }

            $discountAmount = $validated['discount_amount'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'purchase_date' => $validated['purchase_date'],
                'status' => 'draft',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'due_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $lineTotal = ($itemData['unit_cost'] * $itemData['quantity']) - ($itemData['discount'] ?? 0);
                $taxAmountLine = $lineTotal * ($item->tax_rate / 100);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'received_quantity' => 0,
                    'unit_cost' => $itemData['unit_cost'],
                    'discount' => $itemData['discount'] ?? 0,
                    'tax_amount' => $taxAmountLine,
                    'total' => $lineTotal + $taxAmountLine,
                ]);
            }

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_purchase',
                subject: $purchase,
                description: "Created purchase: {$purchase->invoice_number}"
            );

            $notificationService = app(NotificationService::class);
            $notificationService->sendPurchaseNotification($purchase);

            DB::commit();
            return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create purchase: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.item', 'createdBy', 'payments', 'approvedBy']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return back()->with('error', 'Only draft purchases can be edited.');
        }

        $purchase->load('items.item');
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'items'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return back()->with('error', 'Only pending purchases can be updated.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $lineTotal = ($itemData['unit_cost'] * $itemData['quantity']) - ($itemData['discount'] ?? 0);
                $taxAmount += $lineTotal * ($item->tax_rate / 100);
                $subtotal += $lineTotal;
            }

            $discountAmount = $validated['discount_amount'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'purchase_date' => $validated['purchase_date'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'due_amount' => $totalAmount - $purchase->paid_amount,
                'notes' => $validated['notes'] ?? null,
            ]);

            $purchase->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $lineTotal = ($itemData['unit_cost'] * $itemData['quantity']) - ($itemData['discount'] ?? 0);
                $taxAmountLine = $lineTotal * ($item->tax_rate / 100);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'received_quantity' => 0,
                    'unit_cost' => $itemData['unit_cost'],
                    'discount' => $itemData['discount'] ?? 0,
                    'tax_amount' => $taxAmountLine,
                    'total' => $lineTotal + $taxAmountLine,
                ]);
            }

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_purchase',
                subject: $purchase,
                description: "Updated purchase: {$purchase->invoice_number}"
            );

            DB::commit();
            return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return back()->with('error', 'Only draft purchases can be deleted.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_purchase',
                subject: $purchase,
                description: "Deleted purchase: {$purchase->invoice_number}"
            );

            $purchase->items()->delete();
            $purchase->delete();
            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }

    public function receive(Purchase $purchase)
    {
        if ($purchase->status !== 'draft' && $purchase->status !== 'partial') {
            return back()->with('error', 'This purchase cannot be received.');
        }

        DB::beginTransaction();
        try {
            $stockService = app(StockService::class);

            foreach ($purchase->items as $purchaseItem) {
                $remaining = $purchaseItem->quantity - $purchaseItem->received_quantity;

                if ($remaining > 0) {
                    $stockService->recordMovement(
                        item: $purchaseItem->item,
                        type: 'purchase',
                        direction: 'in',
                        quantity: $remaining,
                        reference: $purchase,
                        notes: "Received from purchase {$purchase->invoice_number}",
                        unitCost: $purchaseItem->unit_cost,
                        userId: auth()->id()
                    );

                    $purchaseItem->update([
                        'received_quantity' => $purchaseItem->quantity,
                    ]);
                }
            }

            $purchase->update([
                'status' => 'received',
                'received_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            $notificationService = app(NotificationService::class);
            foreach ($purchase->items as $purchaseItem) {
                $currentStock = $stockService->getCurrentStock($purchaseItem->item);
                if ($purchaseItem->item->isLowStock()) {
                    $notificationService->sendLowStockAlert($purchaseItem->item, $currentStock);
                }
            }

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'receive_purchase',
                subject: $purchase,
                description: "Received purchase: {$purchase->invoice_number}"
            );

            DB::commit();
            return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase received and stock updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to receive purchase: ' . $e->getMessage());
        }
    }

    public function cancel(Purchase $purchase)
    {
        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Purchase is already cancelled.');
        }

        $purchase->load('items');

        if ($purchase->status === 'received' && $purchase->items->where('received_quantity', '>', 0)->isNotEmpty()) {
            return back()->with('error', 'Cannot cancel purchase with received items.');
        }

        DB::beginTransaction();
        try {
            $purchase->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_reason' => request('reason', 'Cancelled by user'),
            ]);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'cancel_purchase',
                subject: $purchase,
                description: "Cancelled purchase: {$purchase->invoice_number}"
            );

            DB::commit();
            return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel purchase: ' . $e->getMessage());
        }
    }
}
