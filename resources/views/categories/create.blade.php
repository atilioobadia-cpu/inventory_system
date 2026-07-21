@extends('layouts.app')

@section('title', 'Create Category')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('categories.index') }}" class="hover:text-accent transition-colors">Categories</a>
<span class="mx-2">/</span>
<span class="text-heading">Create Category</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-heading">Create Category</h1>
        <div class="flex gap-3">
            <a href="{{ route('categories.index') }}" class="px-4 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                Cancel
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf

        <div class="bg-white rounded-xl border border-border p-6 space-y-5">
            <div>
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class=""
                       placeholder="e.g. Brake Parts">
                @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="font-mono"
                       placeholder="auto-generated-from-name">
                <p class="text-xs text-muted mt-1">Leave blank to auto-generate from name</p>
                @error('slug')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="parent_id" class="form-label">Parent Category</label>
                <select name="parent_id" id="parent_id" class="">
                    <option value="">None (Top Level)</option>
                    @foreach($parentCategories ?? [] as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" rows="3"
                          class="resize-none"
                          placeholder="Optional description...">{{ old('description') }}</textarea>
                @error('description')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="">
                    @error('sort_order')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-body mb-3">Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                               class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-control-bg peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                        <span class="ml-2 text-sm text-body">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('categories.index') }}" class="px-6 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-hover transition-colors">
                Save Category
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        document.getElementById('slug').placeholder = slug || 'auto-generated-from-name';
    });
</script>
@endpush
@endsection