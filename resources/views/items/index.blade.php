@extends('layouts.app')

@section('title', 'Items')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('items.index') }}" class="hover:text-primary transition-colors">Items</a>
<span class="mx-2">/</span>
<span class="text-heading">All Items</span>
@endsection

@section('content')
<div x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-heading flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                Items
            </h1>
            <p class="text-sm text-muted mt-1">{{ $items->total() ?? 0 }} items found</p>
        </div>
        @can('create_items')
        <a href="{{ route('items.create') }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-hover transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Item
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg border border-border p-4 mb-6">
        <form method="GET" action="{{ route('items.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Search --}}
                <div class="relative">
                    <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items..."
                           class="w-full pl-10 pr-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                {{-- Category Filter --}}
                <select name="category_id" class="border border-border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="is_active" class="border border-border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('items.index') }}" class="bg-white text-body px-4 py-2 rounded-lg text-sm font-medium hover:bg-white transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-lg border border-border overflow-hidden">
        @if(($items ?? collect())->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-muted">Item</th>
                        <th class="text-left px-4 py-3 font-medium text-muted">SKU</th>
                        <th class="text-left px-4 py-3 font-medium text-muted">Category</th>
                        <th class="text-right px-4 py-3 font-medium text-muted">Cost Price</th>
                        <th class="text-right px-4 py-3 font-medium text-muted">Selling Price</th>
                        <th class="text-right px-4 py-3 font-medium text-muted">Stock</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Status</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($items as $item)
                    <tr class="hover:bg-white transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                                    @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('items.show', $item) }}" class="font-medium text-heading hover:text-primary">{{ $item->name }}</a>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-body font-mono text-xs">{{ $item->sku }}</td>
                        <td class="px-4 py-3 text-body">{{ $item->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right text-body">TZS {{ number_format($item->cost_price) }}</td>
                        <td class="px-4 py-3 text-right font-medium text-heading">TZS {{ number_format($item->selling_price) }}</td>
                        <td class="px-4 py-3 text-right font-medium {{ $item->current_stock <= 0 ? 'text-danger' : ($item->current_stock <= ($item->reorder_point ?? 0) ? 'text-warning' : 'text-heading') }}">
                            {{ $item->current_stock }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item->current_stock <= 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">Out of Stock</span>
                            @elseif($item->current_stock <= ($item->reorder_point ?? 0))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-light text-warning">Low Stock</span>
                            @elseif($item->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">In Stock</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-body">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('items.show', $item) }}" class="p-1.5 text-muted hover:text-primary rounded-lg hover:bg-gray-100 transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>
                                @can('edit_items')
                                <a href="{{ route('items.edit', $item) }}" class="p-1.5 text-muted hover:text-primary rounded-lg hover:bg-gray-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                    </svg>
                                </a>
                                @endcan
                                @can('delete_items')
                                <button @click="deleteModal = true; deleteUrl = '{{ route('items.destroy', $item) }}'; deleteName = '{{ $item->name }}'"
                                        class="p-1.5 text-muted hover:text-danger rounded-lg hover:bg-danger-light transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($items->hasPages())
        <div class="px-4 py-3 border-t border-border">
            {{ $items->withQueryString()->links() }}
        </div>
        @endif
        @else
        {{-- Empty State --}}
        <div class="py-16 text-center">
            <svg class="w-16 h-16 text-muted/50 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
            </svg>
            <h3 class="text-lg font-medium text-muted mb-1">No items found</h3>
            <p class="text-sm text-muted mb-4">Get started by adding your first item.</p>
            @can('create_items')
            <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Item
            </a>
            @endcan
        </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="deleteModal = false"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-danger-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-heading">Delete Item</h3>
                        <p class="text-sm text-muted">This action cannot be undone.</p>
                    </div>
                </div>
                <p class="text-sm text-body mb-6">
                    Are you sure you want to delete <span class="font-semibold" x-text="deleteName"></span>? All associated data will be permanently removed.
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-body bg-white rounded-lg hover:bg-white transition-colors">
                        Cancel
                    </button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-danger rounded-lg hover:bg-danger transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
