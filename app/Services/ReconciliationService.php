<?php

namespace App\Services;

use App\Models\Reconciliation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReconciliationService
{
    public function createDailyReconciliation(int $userId): Reconciliation
    {
        $today = Carbon::today();

        $totalSales = DB::table('sales')
            ->whereDate('sale_date', $today)
            ->whereNull('voided_at')
            ->sum('paid_amount');

        $totalExpenses = DB::table('expenses')
            ->whereDate('expense_date', $today)
            ->sum('amount');

        $totalPurchases = DB::table('purchases')
            ->whereDate('purchase_date', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('paid_amount');

        $expectedCash = $totalSales - $totalExpenses - $totalPurchases;

        $reconciliation = Reconciliation::create([
            'reconciliation_date' => $today,
            'type' => 'daily',
            'status' => 'pending',
            'expected_cash' => round($expectedCash, 2),
            'actual_cash' => 0,
            'difference' => 0,
            'total_sales' => round($totalSales, 2),
            'total_purchases' => round($totalPurchases, 2),
            'total_expenses' => round($totalExpenses, 2),
            'notes' => null,
            'reconciled_by' => $userId,
            'completed_at' => null,
        ]);

        return $reconciliation;
    }

    public function completeReconciliation(
        Reconciliation $reconciliation,
        float $actualCash,
        ?string $notes = null
    ): Reconciliation {
        $difference = round($actualCash - $reconciliation->expected_cash, 2);

        $status = abs($difference) < 0.01 ? 'completed' : 'discrepancy';

        $reconciliation->update([
            'actual_cash' => round($actualCash, 2),
            'difference' => $difference,
            'status' => $status,
            'notes' => $notes,
            'completed_at' => now(),
        ]);

        return $reconciliation->fresh();
    }

    public function getSummary(string $type, Carbon $startDate, Carbon $endDate): array
    {
        $query = Reconciliation::query()
            ->whereBetween('reconciliation_date', [$startDate, $endDate]);

        if ($type !== 'all') {
            $query->where('status', $type);
        }

        $reconciliations = $query->get();

        $totalExpected = $reconciliations->sum('expected_cash');
        $totalActual = $reconciliations->sum('actual_cash');
        $totalDifference = $reconciliations->sum('difference');

        return [
            'count' => $reconciliations->count(),
            'total_expected' => round($totalExpected, 2),
            'total_actual' => round($totalActual, 2),
            'total_difference' => round($totalDifference, 2),
            'completed' => $reconciliations->where('status', 'completed')->count(),
            'discrepancy_count' => $reconciliations->where('status', 'discrepancy')->count(),
            'pending' => $reconciliations->where('status', 'pending')->count(),
            'items' => $reconciliations,
        ];
    }
}
