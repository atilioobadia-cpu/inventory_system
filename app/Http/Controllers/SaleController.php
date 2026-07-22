<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Services\ActivityService;
use App\Services\NotificationService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'createdBy']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            if ($status === 'voided') {
                $query->whereNotNull('voided_at');
            } elseif ($status === 'active') {
                $query->whereNull('voided_at');
            }
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        if ($from = $request->input('date_from', $request->input('from'))) {
            $query->whereDate('sale_date', '>=', $from);
        }

        if ($to = $request->input('date_to', $request->input('to'))) {
            $query->whereDate('sale_date', '<=', $to);
        }

        $sales = $query->latest('sale_date')->paginate(25)->withQueryString();

        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return view('sales.index', compact('sales', 'customers'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.item', 'createdBy', 'voidedBy', 'payments']);

        return view('sales.show', compact('sale'));
    }

    public function destroy(Request $request, Sale $sale)
    {
        if ($sale->voided_at) {
            return back()->with('error', 'Sale is already voided.');
        }

        $validated = $request->validate([
            'void_reason' => 'required|string|min:3|max:500',
        ]);

        DB::beginTransaction();
        try {
            $stockService = app(StockService::class);

            foreach ($sale->items as $saleItem) {
                $stockService->recordMovement(
                    item: $saleItem->item,
                    type: 'sale_void',
                    direction: 'in',
                    quantity: $saleItem->quantity,
                    reference: $sale,
                    notes: "Stock returned from voided sale {$sale->invoice_number}",
                    unitCost: $saleItem->cost_price,
                    userId: auth()->id()
                );
            }

            $sale->update([
                'voided_at' => now(),
                'voided_by' => auth()->id(),
                'void_reason' => $validated['void_reason'],
            ]);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'void_sale',
                subject: $sale,
                description: "Voided sale: {$sale->invoice_number}. Reason: {$validated['void_reason']}"
            );

            $notificationService = app(NotificationService::class);
            $notificationService->sendVoidNotification($sale, $validated['void_reason']);

            DB::commit();
            return redirect()->route('sales.show', $sale)->with('success', 'Sale voided successfully. Stock has been restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to void sale: ' . $e->getMessage());
        }
    }

    public function void(Request $request, Sale $sale)
    {
        if ($sale->voided_at) {
            return back()->with('error', 'Sale is already voided.');
        }

        $validated = $request->validate([
            'void_reason' => 'required|string|min:3|max:500',
        ]);

        DB::beginTransaction();
        try {
            $stockService = app(StockService::class);

            foreach ($sale->items as $saleItem) {
                $stockService->recordMovement(
                    item: $saleItem->item,
                    type: 'sale_void',
                    direction: 'in',
                    quantity: $saleItem->quantity,
                    reference: $sale,
                    notes: "Stock returned from voided sale {$sale->invoice_number}",
                    unitCost: $saleItem->cost_price,
                    userId: auth()->id()
                );
            }

            $sale->update([
                'voided_at' => now(),
                'voided_by' => auth()->id(),
                'void_reason' => $validated['void_reason'],
            ]);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'void_sale',
                subject: $sale,
                description: "Voided sale: {$sale->invoice_number}. Reason: {$validated['void_reason']}"
            );

            $notificationService = app(NotificationService::class);
            $notificationService->sendVoidNotification($sale, $validated['void_reason']);

            DB::commit();
            return redirect()->route('sales.show', $sale)->with('success', 'Sale voided successfully. Stock has been restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to void sale: ' . $e->getMessage());
        }
    }
}
