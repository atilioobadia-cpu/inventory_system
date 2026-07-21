<?php

namespace App\Services;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReconciliationService
{
    public function createDailyReconciliation(int $userId): object
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

        $reconciliationNumber = $this->generateReconciliationNumber();

        $id = DB::table('reconciliations')->insertGetId([
            'reconciliation_number' => $reconciliationNumber,
            'date'                  => $today,
            'expected_cash'         => round($expectedCash, 2),
            'actual_cash'           => null,
            'discrepancy'           => null,
            'status'                => 'pending',
            'notes'                 => null,
            'user_id'               => $userId,
            'completed_at'          => null,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        return DB::table('reconciliations')->find($id);
    }

    public function completeReconciliation(
        object $reconciliation,
        float $actualCash,
        ?string $notes = null
    ): object {
        $discrepancy = round($actualCash - $reconciliation->expected_cash, 2);

        $status = abs($discrepancy) < 0.01 ? 'completed' : 'discrepancy';

        DB::table('reconciliations')
            ->where('id', $reconciliation->id)
            ->update([
                'actual_cash'   => round($actualCash, 2),
                'discrepancy'   => $discrepancy,
                'status'        => $status,
                'notes'         => $notes,
                'completed_at'  => now(),
                'updated_at'    => now(),
            ]);

        return DB::table('reconciliations')->find($reconciliation->id);
    }

    public function getSummary(string $type, Carbon $startDate, Carbon $endDate): array
    {
        $query = DB::table('reconciliations')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($type !== 'all') {
            $query->where('status', $type);
        }

        $reconciliations = $query->get();

        $totalExpected = $reconciliations->sum('expected_cash');
        $totalActual   = $reconciliations->sum('actual_cash');
        $totalDiscrepancy = $reconciliations->sum('discrepancy');

        return [
            'count'             => $reconciliations->count(),
            'total_expected'    => round($totalExpected, 2),
            'total_actual'      => round($totalActual, 2),
            'total_discrepancy' => round($totalDiscrepancy, 2),
            'completed'         => $reconciliations->where('status', 'completed')->count(),
            'discrepancy_count' => $reconciliations->where('status', 'discrepancy')->count(),
            'pending'           => $reconciliations->where('status', 'pending')->count(),
            'items'             => $reconciliations,
        ];
    }

    private function generateReconciliationNumber(): string
    {
        $year = date('Y');
        $prefix = "REC-{$year}-";

        $lastRecord = DB::table('reconciliations')
            ->where('reconciliation_number', 'like', $prefix . '%')
            ->orderByRaw("SUBSTRING(reconciliation_number, " . (strlen($prefix) + 1) . ") DESC")
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->reconciliation_number, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
