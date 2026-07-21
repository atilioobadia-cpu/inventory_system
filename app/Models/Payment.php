<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'payable_type',
        'payable_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference',
        'notes',
        'received_by',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (empty($payment->reference_number)) {
                $payment->reference_number = static::generateReferenceNumber();
            }
        });
    }

    protected static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $prefix = 'PAY-' . $year . '-';

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

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
