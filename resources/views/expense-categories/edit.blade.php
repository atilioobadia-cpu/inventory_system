@extends('layouts.app')

@section('title', 'Edit Expense Category - Mtokoma')

@section('header-title', 'Edit Expense Category')

@section('breadcrumbs')
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('expense-categories.index') }}" class="hover:text-electric transition-colors">Expense Categories</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-700 font-medium">Edit Category</span>
@endsection

@section('content')
@php $category = $category ?? null; @endphp
@if($category)
<div class="max-w-2xl mx-auto">
    <form action="{{ route('expense-categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Category Details</h3>
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric bg-gray-50">
                    @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 text-electric rounded border-gray-300 focus:ring-electric/50">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('expense-categories.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-electric rounded-lg hover:bg-blue-600 transition-colors">
                Update Category
            </button>
        </div>
    </form>
</div>
@else
<div class="text-center py-16">
    <p class="text-gray-500">Category not found.</p>
</div>
@endif
@endsection
