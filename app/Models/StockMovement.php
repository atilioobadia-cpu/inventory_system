<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'type',
        'reference_type',
        'reference_id',
        'quantity',
        'direction',
        'balance_before',
        'balance_after',
        'unit_cost',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
        'quantity' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getReferenceAttribute(): ?string
    {
        if ($this->reference_type && $this->reference_id) {
            return class_basename($this->reference_type) . ' #' . $this->reference_id;
        }
        return null;
    }

    public function scopeIn(Builder $query): Builder
    {
        return $query->where('direction', 'in');
    }

    public function scopeOut(Builder $query): Builder
    {
        return $query->where('direction', 'out');
    }

    public function scopeForItem(Builder $query, int $itemId): Builder
    {
        return $query->where('item_id', $itemId);
    }
}
