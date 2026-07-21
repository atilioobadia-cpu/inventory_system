<?php

namespace App\Http\Controllers;

use App\Models\Reconciliation;
use App\Services\ActivityService;
use App\Services\ReconciliationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reconciliation::with('reconciledBy');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('reconciliation_date', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('reconciliation_date', '<=', $to);
        }

        $reconciliations = $query->latest('reconciliation_date')->paginate(25)->withQueryString();

        return view('reconciliations.index', compact('reconciliations'));
    }

    public function create()
    {
        $reconciliationService = app(ReconciliationService::class);
        $expectedSales = DB::table('sales')
            ->whereDate('sale_date', today())
            ->whereNull('voided_at')
            ->sum('paid_amount');

        $expectedExpenses = DB::table('expenses')
            ->whereDate('expense_date', today())
            ->sum('amount');

        $expectedPurchases = DB::table('purchases')
            ->whereDate('purchase_date', today())
            ->where('status', '!=', 'cancelled')
            ->sum('paid_amount');

        $expectedCash = $expectedSales - $expectedExpenses - $expectedPurchases;

        return view('reconciliations.create', compact('expectedSales', 'expectedExpenses', 'expectedPurchases', 'expectedCash'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $reconciliationService = app(ReconciliationService::class);
            $reconciliation = $reconciliationService->createDailyReconciliation(auth()->id());
            $completedReconciliation = $reconciliationService->completeReconciliation(
                $reconciliation,
                $validated['actual_cash'],
                $validated['notes']
            );

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_reconciliation',
                subject: 'App\\Models\\Reconciliation',
                description: "Created daily reconciliation for " . now()->format('d M Y')
            );

            DB::commit();
            return redirect()->route('reconciliations.show', $completedReconciliation->id)->with('success', 'Reconciliation completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create reconciliation: ' . $e->getMessage());
        }
    }

    public function show(Reconciliation $reconciliation)
    {
        return view('reconciliations.show', compact('reconciliation'));
    }
}
