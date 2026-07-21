@extends('layouts.app')

@section('title', $category->name . ' - Category')

@section('header-title', $category->name)

@section('breadcrumbs')
<span class="mx-2 text-gray-400">/</span>
<a href="{{ route('categories.index') }}" class="hover:text-tz-green transition-colors">Categories</a>
<span class="mx-2 text-gray-400">/</span>
<span class="text-gray-700 font-medium">{{ $category->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $category->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-tz-green text-white rounded-lg text-sm font-medium hover:bg-tz-green-dark transition-colors">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Total Items</p>
            <p class="text-2xl font-bold text-gray-900">{{ $itemsCount ?? $category->items()->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Parent Category</p>
            <p class="text-2xl font-bold text-gray-900">{{ $category->parent->name ?? 'None' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-2xl font-bold {{ $category->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>

    @if(isset($category->items) && $category->items->count())
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Items in this Category</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Name</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">SKU</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($category->items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3"><a href="{{ route('items.show', $item) }}" class="text-tz-green hover:underline">{{ $item->name }}</a></td>
                        <td class="px-6 py-3 text-gray-500">{{ $item->sku }}</td>
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
