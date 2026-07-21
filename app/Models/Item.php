<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'category_id',
        'supplier_id',
        'description',
        'cost_price',
        'selling_price',
        'tax_rate',
        'unit',
        'min_stock',
        'max_stock',
        'reorder_point',
        'image',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Item $item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name);
            }

            if (empty($item->sku)) {
                $item->sku = static::generateSku();
            }
        });

        static::updating(function (Item $item) {
            if ($item->isDirty('name') && !$item->isDirty('slug')) {
                $item->slug = Str::slug($item->name);
            }
        });
    }

    protected static function generateSku(): string
    {
        $lastItem = static::latest('id')->first();

        if ($lastItem && $lastItem->sku) {
            $lastNumber = (int) filter_var($lastItem->sku, FILTER_SANITIZE_NUMBER_INT);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'ITM-' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getCurrentStock(): int
    {
        $in = $this->stockMovements()
            ->where('direction', 'in')
            ->sum('quantity');

        $out = $this->stockMovements()
            ->where('direction', 'out')
            ->sum('quantity');

        return (int) ($in - $out);
    }

    public function isLowStock(): bool
    {
        return $this->getCurrentStock() <= $this->reorder_point;
    }

    public function getMarginAttribute(): ?float
    {
        if ($this->cost_price <= 0) {
            return null;
        }

        return round((($this->selling_price - $this->cost_price) / $this->cost_price) * 100, 2);
    }

    public function getFormattedSkuAttribute(): string
    {
        return $this->sku ? $this->sku : 'N/A';
    }
}
