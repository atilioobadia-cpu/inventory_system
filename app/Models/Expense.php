<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'expense_category_id',
        'amount',
        'expense_date',
        'payment_method',
        'reference',
        'description',
        'receipt_path',
        'is_recurring',
        'recurring_frequency',
        'recurring_end_date',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'is_recurring' => 'boolean',
        'approved_at' => 'datetime',
        'recurring_end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Expense $expense) {
            if (empty($expense->reference_number)) {
                $expense->reference_number = static::generateReferenceNumber();
            }
        });
    }

    protected static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $prefix = 'EXP-' . $year . '-';

        $lastRecord = static::where('reference_number', 'like', $prefix . '%')
            ->orderByRaw("SUBSTRING(reference_number, " . (strlen($prefix) + 1) . ") DESC")
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->reference_number, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
