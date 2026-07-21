@extends('layouts.app')

@section('title', 'Edit Expense Category - Mtokoma')

@section('header-title', 'Edit Expense Category')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <a href="{{ route('expense-categories.index') }}" class="hover:text-accent transition-colors">Expense Categories</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Edit Category</span>
@endsection

@section('content')
@php $category = $category ?? null; @endphp
@if($category)
<div class="max-w-2xl mx-auto">
    <form action="{{ route('expense-categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg border border-border p-6">
            <h3 class="text-lg font-semibold text-heading mb-6">Category Details</h3>
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}">
                    @error('slug') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 text-accent rounded border-border focus:ring-accent/50">
                        <span class="text-sm font-medium text-body">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('expense-categories.index') }}" class="px-4 py-2.5 text-sm font-medium text-body bg-white border border-border rounded-lg hover:bg-card-bg transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-hover transition-colors">
                Update Category
            </button>
        </div>
    </form>
</div>
@else
<div class="text-center py-16">
    <p class="text-muted">Category not found.</p>
</div>
@endif
@endsection
