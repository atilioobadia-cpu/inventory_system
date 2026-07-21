@extends('layouts.app')

@section('title', 'Add Expense Category - Mtokoma')

@section('header-title', 'Add Expense Category')

@section('breadcrumbs')
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('expense-categories.index') }}" class="hover:text-tz-green transition-colors">Expense Categories</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-700 font-medium">Add Category</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('expense-categories.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Category Details</h3>
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Utilities, Rent, Salaries"
                           x-data="{ slug: '{{ old('slug') }}' }" x-init="$watch('slug', v => $refs.slugInput.value = v)">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="auto-generated-from-name">
                    @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" placeholder="Brief description of this category...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-5 h-5 text-tz-green rounded border-gray-300 focus:ring-electric/50">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('expense-categories.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-tz-green rounded-lg hover:bg-tz-green-dark transition-colors">
                Save Category
            </button>
        </div>
    </form>
</div>
@endsection
