<?php

namespace App\Services;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;

class StockService
{
    public function getCurrentStock(Item $item): int
    {
        $in = $item->stockMovements()
            ->where('direction', 'in')
            ->sum('quantity');

        $out = $item->stockMovements()
            ->where('direction', 'out')
            ->sum('quantity');

        return (int) ($in - $out);
    }

    public function getAllStockLevels(): Collection
    {
        return Item::with('category')
            ->where('is_active', true)
            ->get()
            ->map(function ($item) {
                $item->current_stock = $this->getCurrentStock($item);

                return $item;
            });
    }

    public function recordMovement(
        Item $item,
        string $type,
        string $direction,
        int $quantity,
        $reference = null,
        ?string $notes = null,
        ?float $unitCost = null,
        ?int $userId = null
    ): StockMovement {
        $balanceBefore = $this->getCurrentStock($item);

        if ($direction === 'in') {
            $balanceAfter = $balanceBefore + $quantity;
        } else {
            $balanceAfter = $balanceBefore - $quantity;
        }

        $movementData = [
            'item_id'       => $item->id,
            'type'          => $type,
            'quantity'      => $quantity,
            'direction'     => $direction,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'unit_cost'     => $unitCost,
            'notes'         => $notes,
            'user_id'       => $userId ?? auth()->id(),
        ];

        if ($reference) {
            $movementData['reference_type'] = get_class($reference);
            $movementData['reference_id']   = $reference->id;
        }

        return StockMovement::create($movementData);
    }

    public function adjustStock(
        Item $item,
        int $quantity,
        string $type,
        ?string $notes = null,
        ?int $userId = null
    ): StockMovement {
        $currentStock = $this->getCurrentStock($item);
        $direction = $quantity >= 0 ? 'in' : 'out';
        $absoluteQuantity = abs($quantity);

        if ($direction === 'out' && $absoluteQuantity > $currentStock) {
            throw new \InvalidArgumentException(
                "Cannot reduce stock by {$absoluteQuantity}. Current stock is {$currentStock}."
            );
        }

        return $this->recordMovement(
            item: $item,
            type: $type,
            direction: $direction,
            quantity: $absoluteQuantity,
            notes: $notes ?? "Stock adjustment: {$type}",
            userId: $userId
        );
    }

    public function getStockValue(): float
    {
        $items = Item::where('is_active', true)->get();

        $totalValue = 0.0;

        foreach ($items as $item) {
            $stock = $this->getCurrentStock($item);
            $totalValue += $stock * (float) $item->cost_price;
        }

        return round($totalValue, 2);
    }

    public function getLowStockItems(): Collection
    {
        $items = Item::with('category')
            ->where('is_active', true)
            ->get();

        return $items->filter(function ($item) {
            $currentStock = $this->getCurrentStock($item);
            $reorderPoint = $item->reorder_point ?? 0;

            return $currentStock <= $reorderPoint;
        })->values();
    }

    public function checkAvailability(Item $item, int $quantity): bool
    {
        return $this->getCurrentStock($item) >= $quantity;
    }
}
