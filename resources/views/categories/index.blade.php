@extends('layouts.app')

@section('title', 'Categories')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('categories.index') }}" class="hover:text-accent transition-colors">Categories</a>
<span class="mx-2">/</span>
<span class="text-heading">All Categories</span>
@endsection

@section('content')
<div x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent-light rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-heading">Categories</h1>
                <p class="text-sm text-muted mt-1">{{ $categories->total() }} categories</p>
            </div>
        </div>
        @can('create_categories')
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-hover transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Category
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg border border-border overflow-hidden">
        @if(($categories ?? collect())->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-muted">Name</th>
                        <th class="text-left px-4 py-3 font-medium text-muted">Parent</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Items Count</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Status</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Sort</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($categories as $category)
                    <tr class="hover:bg-card-bg transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($category->parent)
                                <span class="text-muted">└─</span>
                                @endif
                                <span class="font-medium text-heading">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-body">{{ $category->parent->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-control-bg text-sm font-medium text-body">
                                {{ $category->items_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($category->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Active</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-body">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-body">{{ $category->sort_order ?? 0 }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                @can('edit_categories')
                                <a href="{{ route('categories.edit', $category) }}" class="p-1.5 text-muted hover:text-accent rounded-lg hover:bg-accent-light transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                    </svg>
                                </a>
                                @endcan
                                @can('delete_categories')
                                <button @click="deleteModal = true; deleteUrl = '{{ route('categories.destroy', $category) }}'; deleteName = '{{ $category->name }}'"
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
        @else
        <div class="py-16 text-center">
            <svg class="w-16 h-16 text-muted/50 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
            </svg>
            <h3 class="text-lg font-medium text-muted mb-1">No categories found</h3>
            <p class="text-sm text-muted mb-4">Create your first category to organize items.</p>
            @can('create_categories')
            <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-hover transition-colors">
                Add Category
            </a>
            @endcan
        </div>
        @endif

        @if($categories->hasPages())
        <div class="px-4 py-3 border-t border-border overflow-x-auto">
            {{ $categories->withQueryString()->links() }}
        </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div x-show="deleteModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="deleteModal = false"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-heading mb-2">Delete Category</h3>
                <p class="text-sm text-body mb-6">Are you sure you want to delete <span class="font-semibold" x-text="deleteName"></span>?</p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg">Cancel</button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-danger rounded-lg hover:bg-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
