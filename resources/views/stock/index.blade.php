@extends('layouts.app')

@section('title', 'Stock Levels - Mtokoma')

@section('header-title', 'Stock Levels')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Stock Levels</span>
@endsection

@section('content')
<div x-data="{
    search: '{{ request('search') }}',
    category: '{{ request('category_id') }}',
    status: '{{ request('stock_status', 'all') }}'
}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-heading">Stock Levels</h2>
            <p class="text-sm text-muted mt-1">Monitor current inventory levels across all items</p>
        </div>
        <a href="{{ route('stock.movements') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-body bg-white border border-border rounded-lg hover:bg-card-bg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            View Movements
        </a>
    </div>

    <div class="bg-white rounded-xl border border-border">
        <form method="GET" action="{{ route('stock.index') }}">
            <div class="p-4 border-b border-border grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" x-model="search" placeholder="Search by name or SKU..." class="w-full pl-10 pr-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                    </div>
                </div>
                <div>
                    <select name="category_id" x-model="category" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="stock_status" x-model="status" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="all">All Status</option>
                        <option value="out">Out of Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="in">In Stock</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-b border-border flex items-center gap-3">
                <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">
                    Filter
                </button>
                <a href="{{ route('stock.index') }}" class="text-muted hover:text-body px-3 py-1.5 text-sm">Reset</a>
                <div class="ml-auto">
                    <a href="{{ route('stock.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium text-body border border-border rounded-lg hover:bg-card-bg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Export
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-card-bg border-b border-border">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider w-12">Image</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Name</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">SKU</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Category</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Cost Price</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Selling Price</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Current Stock</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Min Stock</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Reorder Pt</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($items ?? [] as $item)
                        @php
                            $qty = $item->current_stock ?? 0;
                            $reorder = $item->reorder_point ?? 0;
                            $min = $item->min_stock ?? 0;
                            $max = $item->max_stock ?? PHP_INT_MAX;
                            if ($qty == 0) {
                                $statusText = 'Out of Stock';
                                $statusBg = 'bg-danger-light';
                                $statusText2 = 'text-danger';
                            } elseif ($qty < $reorder) {
                                $statusText = 'Low Stock';
                                $statusBg = 'bg-warning-light';
                                $statusText2 = 'text-amber-800';
                            } elseif ($qty > $max) {
                                $statusText = 'Overstocked';
                                $statusBg = 'bg-accent-light';
                                $statusText2 = 'text-accent';
                            } else {
                                $statusText = 'In Stock';
                                $statusBg = 'bg-success-light';
                                $statusText2 = 'text-success';
                            }
                        @endphp
                        <tr class="hover:bg-card-bg transition-colors">
                            <td class="px-4 py-3">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-control-bg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-heading">{{ $item->name }}</td>
                            <td class="px-4 py-3 text-sm text-muted font-mono">{{ $item->sku }}</td>
                            <td class="px-4 py-3 text-sm text-muted">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($item->cost_price, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($item->selling_price, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm font-bold {{ $qty == 0 ? 'text-danger' : ($qty < $reorder ? 'text-warning' : 'text-heading') }}">{{ $qty }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-muted text-center">{{ $min }}</td>
                            <td class="px-4 py-3 text-sm text-muted text-center">{{ $reorder }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBg }} {{ $statusText2 }}">{{ $statusText }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-heading text-right">TZS {{ number_format($item->cost_price * $qty, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-16 text-center">
                                <svg class="w-16 h-16 text-muted mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                </svg>
                                <p class="text-muted font-medium">No items found</p>
                                <p class="text-sm text-muted mt-1">Add items to see stock levels here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(isset($items) && $items->count())
                <tfoot>
                    <tr class="bg-card-bg border-t-2 border-border">
                        <td colspan="6" class="px-4 py-3 text-sm font-semibold text-heading">Summary</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-sm font-bold text-heading">{{ $items->sum('current_stock') }}</span>
                        </td>
                        <td colspan="3" class="px-4 py-3 text-sm text-muted">
                            {{ $items->total() }} item(s)
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-bold text-heading">TZS {{ number_format($items->sum(function($i) { return ($i->cost_price ?? 0) * ($i->current_stock ?? 0); }), 2) }}</span>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        @if(isset($items) && $items->hasPages())
        <div class="px-4 py-3 border-t border-border">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
