<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\ActivityService;
use App\Services\NotificationService;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $items = Item::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                $stockService = app(StockService::class);
                $item->current_stock = $stockService->getCurrentStock($item);
                return $item;
            });

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return view('pos.index', compact('items', 'categories', 'customers'));
    }

    public function processSale(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'sale_type' => 'required|in:cash,credit',
            'cash_received' => 'required|numeric|min:0',
            'is_vat_exempt' => 'boolean',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $customerId = $validated['customer_id'] ?? null;
            if (!$customerId) {
                $walkIn = Customer::where('is_walk_in', true)->first();
                if (!$walkIn) {
                    $walkIn = Customer::create([
                        'name' => 'Walk-In Customer',
                        'phone' => null,
                        'customer_type' => 'individual',
                        'credit_limit' => 0,
                        'current_balance' => 0,
                        'is_walk_in' => true,
                        'is_active' => true,
                    ]);
                }
                $customerId = $walkIn->id;
            }

            $stockService = app(StockService::class);
            $subtotal = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $currentStock = $stockService->getCurrentStock($item);

                if ($currentStock < $itemData['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$item->name}. Available: {$currentStock}",
                    ], 422);
                }

                $lineTotal = $itemData['unit_price'] * $itemData['quantity'];
                if (!($validated['is_vat_exempt'] ?? false)) {
                    $totalTax += $lineTotal * ($item->tax_rate / 100);
                }
                $subtotal += $lineTotal;
            }

            $discountAmount = $validated['discount'] ?? 0;
            $totalAmount = $subtotal + $totalTax - $discountAmount;
            $paidAmount = $validated['cash_received'];
            $dueAmount = max(0, $totalAmount - $paidAmount);

            if ($dueAmount > 0 && empty($validated['customer_id'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'A customer must be selected for credit sales.',
                ], 422);
            }

            $paymentStatus = 'paid';
            if ($dueAmount > 0 && $paidAmount > 0) {
                $paymentStatus = 'partial';
            } elseif ($dueAmount > 0) {
                $paymentStatus = 'unpaid';
            }

            $sale = Sale::create([
                'customer_id' => $customerId,
                'sale_date' => now(),
                'sale_type' => $validated['sale_type'],
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'is_vat_exempt' => $validated['is_vat_exempt'] ?? false,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $lineTotal = $itemData['unit_price'] * $itemData['quantity'];
                $taxAmount = 0;
                if (!($validated['is_vat_exempt'] ?? false)) {
                    $taxAmount = $lineTotal * ($item->tax_rate / 100);
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'cost_price' => $item->cost_price,
                    'discount' => 0,
                    'tax_amount' => $taxAmount,
                    'total' => $lineTotal + $taxAmount,
                ]);

                $stockService->recordMovement(
                    item: $item,
                    type: 'sale',
                    direction: 'out',
                    quantity: $itemData['quantity'],
                    reference: $sale,
                    notes: "POS Sale {$sale->invoice_number}",
                    unitCost: $item->cost_price,
                    userId: auth()->id()
                );
            }

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_sale',
                subject: $sale,
                description: "POS sale: {$sale->invoice_number} - TZS " . number_format($totalAmount)
            );

            $notificationService = app(NotificationService::class);
            $notificationService->sendSaleNotification($sale);

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $currentStock = $stockService->getCurrentStock($item);
                if ($item->isLowStock()) {
                    $notificationService->sendLowStockAlert($item, $currentStock);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'total_amount' => $totalAmount,
                'message' => 'Sale completed successfully.',
                'redirect' => route('receipts.show', $sale),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process sale: ' . $e->getMessage(),
            ], 500);
        }
    }
}
