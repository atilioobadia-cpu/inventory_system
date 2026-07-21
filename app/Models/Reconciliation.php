<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reconciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reconciliation_date',
        'type',
        'status',
        'expected_cash',
        'actual_cash',
        'difference',
        'total_sales',
        'total_purchases',
        'total_expenses',
        'notes',
        'reconciled_by',
        'completed_at',
    ];

    protected $casts = [
        'reconciliation_date' => 'date',
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'difference' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function reconciledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }
}
