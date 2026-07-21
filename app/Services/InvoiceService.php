<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function generatePurchaseNumber(): string
    {
        return $this->generateNumber('PUR', 'purchases');
    }

    public function generateSaleNumber(): string
    {
        return $this->generateNumber('SAL', 'sales');
    }

    public function generateExpenseNumber(): string
    {
        return $this->generateNumber('EXP', 'expenses');
    }

    public function generatePaymentNumber(): string
    {
        return $this->generateNumber('PAY', 'payments');
    }

    private function generateNumber(string $prefix, string $table): string
    {
        $year = date('Y');
        $fullPrefix = "{$prefix}-{$year}-";

        $lastRecord = DB::table($table)
            ->where('invoice_number', 'like', $fullPrefix . '%')
            ->orderByRaw("SUBSTRING(invoice_number, " . (strlen($fullPrefix) + 1) . ") DESC")
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->invoice_number, strlen($fullPrefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $fullPrefix . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
