@extends('layouts.app')

@section('title', $category->name . ' - Category')

@section('header-title', $category->name)

@section('breadcrumbs')
<span class="mx-2 text-muted">/</span>
<a href="{{ route('categories.index') }}" class="hover:text-accent transition-colors">Categories</a>
<span class="mx-2 text-muted">/</span>
<span class="text-body font-medium">{{ $category->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-heading">{{ $category->name }}</h2>
            <p class="text-sm text-muted mt-1">{{ $category->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-border p-6">
            <p class="text-sm text-muted">Total Items</p>
            <p class="text-2xl font-bold text-heading">{{ $itemsCount ?? $category->items()->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <p class="text-sm text-muted">Parent Category</p>
            <p class="text-2xl font-bold text-heading">{{ $category->parent->name ?? 'None' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <p class="text-sm text-muted">Status</p>
            <p class="text-2xl font-bold {{ $category->is_active ? 'text-success' : 'text-danger' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>

    @if(isset($category->items) && $category->items->count())
    <div class="bg-white rounded-xl border border-border">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-heading">Items in this Category</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Name</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">SKU</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($category->items as $item)
                    <tr class="hover:bg-card-bg">
                        <td class="px-6 py-3"><a href="{{ route('items.show', $item) }}" class="text-accent hover:underline">{{ $item->name }}</a></td>
                        <td class="px-6 py-3 text-muted">{{ $item->sku }}</td>
                        <td class="px-6 py-3 text-right">TZS {{ number_format($item->selling_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
