@extends('layouts.app')

@section('title', $item->name ?? 'Item Details')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('items.index') }}" class="hover:text-electric transition-colors">Items</a>
<span class="mx-2">/</span>
<span class="text-gray-800">{{ $item->name ?? 'Details' }}</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Item Details</h1>
        <div class="flex gap-3">
            @can('edit_items')
            <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                </svg>
                Edit
            </a>
            @endcan
            @can('delete_items')
            <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Are you sure you want to delete this item?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-danger bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Item Card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row gap-6">
                    {{-- Image --}}
                    <div class="w-full sm:w-48 h-48 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                        </svg>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $item->name }}</h2>
                                <p class="text-sm text-gray-500 mt-0.5">SKU: <span class="font-mono">{{ $item->sku }}</span></p>
                                @if($item->barcode)
                                <p class="text-sm text-gray-500">Barcode: <span class="font-mono">{{ $item->barcode }}</span></p>
                                @endif
                            </div>
                            @if($item->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Active</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </div>

                        @if($item->description)
                        <p class="text-sm text-gray-600 mb-4">{{ $item->description }}</p>
                        @endif

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Category</p>
                                <p class="text-sm font-medium text-gray-800">{{ $item->category->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Supplier</p>
                                <p class="text-sm font-medium text-gray-800">{{ $item->supplier->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Cost Price</p>
                                <p class="text-sm font-medium text-gray-800">TZS {{ number_format($item->cost_price) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Selling Price</p>
                                <p class="text-sm font-medium text-gray-800">TZS {{ number_format($item->selling_price) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock Movements --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Stock Movements</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-100">
                                <th class="pb-3 font-medium">Date</th>
                                <th class="pb-3 font-medium">Type</th>
                                <th class="pb-3 font-medium">Reference</th>
                                <th class="pb-3 font-medium text-right">Qty</th>
                                <th class="pb-3 font-medium text-right">Before</th>
                                <th class="pb-3 font-medium text-right">After</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($item->stockMovements as $movement)
                            <tr>
                                <td class="py-3 text-gray-600">{{ $movement->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3">
                                    @if($movement->type === 'in')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Stock In</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">Stock Out</span>
                                    @endif
                                </td>
                                <td class="py-3 text-gray-600 font-mono text-xs">{{ $movement->reference }}</td>
                                <td class="py-3 text-right font-medium {{ $movement->type === 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </td>
                                <td class="py-3 text-right text-gray-500">{{ $movement->quantity_before }}</td>
                                <td class="py-3 text-right text-gray-800 font-medium">{{ $movement->quantity_after }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">No stock movements recorded</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Current Stock --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Current Stock</h3>
                <p class="text-4xl font-bold {{ $item->current_stock <= 0 ? 'text-danger' : ($item->current_stock <= ($item->reorder_point ?? 0) ? 'text-warning' : 'text-success') }}">
                    {{ $item->current_stock }}
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $item->unit ?? 'pieces' }}</p>

                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Min Stock</span>
                        <span class="text-gray-800">{{ $item->min_stock }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Max Stock</span>
                        <span class="text-gray-800">{{ $item->max_stock }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Reorder Point</span>
                        <span class="text-gray-800">{{ $item->reorder_point }}</span>
                    </div>
                </div>

                @if($item->current_stock <= ($item->reorder_point ?? 0))
                <div class="mt-4 p-3 bg-amber-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-warning" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.004Z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-amber-700">Stock is low. Consider reordering.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Pricing Summary --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Pricing</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Cost Price</span>
                        <span class="font-medium text-gray-800">TZS {{ number_format($item->cost_price) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Selling Price</span>
                        <span class="font-medium text-gray-800">TZS {{ number_format($item->selling_price) }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Profit Margin</span>
                        <span class="font-medium text-success">
                            @if($item->cost_price > 0)
                            {{ number_format((($item->selling_price - $item->cost_price) / $item->cost_price) * 100, 1) }}%
                            @else
                            N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tax Rate</span>
                        <span class="font-medium text-gray-800">{{ $item->tax_rate }}%</span>
                    </div>
                </div>
            </div>

            {{-- Purchase History --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Recent Purchases</h3>
                <div class="space-y-3">
                    @forelse($recentPurchases as $purchase)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="text-gray-800">{{ $purchase->reference }}</p>
                            <p class="text-xs text-gray-500">{{ $purchase->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="text-gray-800">{{ $purchase->pivot->quantity }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">No purchase history</p>
                    @endforelse
                </div>
            </div>

            {{-- Sales History --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Recent Sales</h3>
                <div class="space-y-3">
                    @forelse($recentSales as $sale)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="text-gray-800">{{ $sale->reference }}</p>
                            <p class="text-xs text-gray-500">{{ $sale->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="text-gray-800">{{ $sale->pivot->quantity }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">No sales history</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection