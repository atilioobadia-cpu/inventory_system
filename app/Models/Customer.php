<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'tin_number',
        'customer_type',
        'credit_limit',
        'current_balance',
        'notes',
        'is_walk_in',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_walk_in' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->slug)) {
                $customer->slug = Str::slug($customer->name);
            }
        });

        static::updating(function (Customer $customer) {
            if ($customer->isDirty('name') && !$customer->isDirty('slug')) {
                $customer->slug = Str::slug($customer->name);
            }
        });
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function scopeWalkIn(Builder $query): Builder
    {
        return $query->where('is_walk_in', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
