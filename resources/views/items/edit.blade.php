@extends('layouts.app')

@section('title', 'Edit Item - ' . ($item->name ?? ''))

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('items.index') }}" class="hover:text-accent transition-colors">Items</a>
<span class="mx-2">/</span>
<a href="{{ route('items.show', $item) }}" class="hover:text-accent transition-colors">{{ $item->name }}</a>
<span class="mx-2">/</span>
<span class="text-heading">Edit</span>
@endsection

@section('content')
<div x-data="{ imagePreview: '{{ $item->image ? asset('storage/' . $item->image) : '' }}' }">
    <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-heading">Edit Item</h1>
            <div class="flex gap-3">
                <a href="{{ route('items.show', $item) }}" class="px-4 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-hover transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Update Item
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white rounded-lg border border-border p-5">
                    <h2 class="text-lg font-semibold text-heading mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="name" class="form-label flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                Item Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required
                                   class="">
                            @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="sku" class="form-label flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z M13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/></svg>
                                SKU
                            </label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $item->sku) }}"
                                   class="font-mono">
                            @error('sku')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="barcode" class="form-label flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z M13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/></svg>
                                Barcode
                            </label>
                            <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $item->barcode) }}"
                                   class="font-mono">
                            @error('barcode')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="category_id" class="form-label flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z"/></svg>
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" required class="">
                                <option value="">Select Category</option>
                                @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-lg border border-border p-5">
                    <h2 class="text-lg font-semibold text-heading mb-4">Description</h2>
                    <textarea name="description" rows="4"
                              class="resize-none"
                              placeholder="Enter item description...">{{ old('description', $item->description) }}</textarea>
                    @error('description')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-lg border border-border p-5">
                    <h2 class="text-lg font-semibold text-heading mb-4">Pricing</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="cost_price" class="form-label flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cost Price (TZS) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', $item->cost_price) }}" required min="0" step="0.01"
                                   class="">
                            @error('cost_price')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="selling_price" class="form-label flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Selling Price (TZS) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $item->selling_price) }}" required min="0" step="0.01"
                                   class="">
                            @error('selling_price')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $item->tax_rate) }}" min="0" max="100" step="0.01"
                                   class="">
                            @error('tax_rate')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Stock Levels --}}
                <div class="bg-white rounded-lg border border-border p-5">
                    <h2 class="text-lg font-semibold text-heading mb-4">Stock Levels</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="min_stock" class="form-label">Minimum Stock</label>
                            <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $item->min_stock) }}" min="0"
                                   class="">
                            @error('min_stock')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="max_stock" class="form-label">Maximum Stock</label>
                            <input type="number" name="max_stock" id="max_stock" value="{{ old('max_stock', $item->max_stock) }}" min="0"
                                   class="">
                            @error('max_stock')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="reorder_point" class="form-label">Reorder Point</label>
                            <input type="number" name="reorder_point" id="reorder_point" value="{{ old('reorder_point', $item->reorder_point) }}" min="0"
                                   class="">
                            @error('reorder_point')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                <div class="bg-white rounded-lg border border-border p-5">
                    <h2 class="text-lg font-semibold text-heading mb-4">Item Image</h2>
                    <div class="border-2 border-dashed border-border rounded-lg p-6 text-center hover:border-accent/50 transition-colors">
                        <div x-show="!imagePreview">
                            <svg class="w-12 h-12 text-muted mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/>
                            </svg>
                            <p class="text-sm text-muted mb-2">Click to upload or drag and drop</p>
                            <p class="text-xs text-muted">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                        <div x-show="imagePreview" x-cloak>
                            <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg mx-auto mb-3">
                            <button type="button" @click="imagePreview = null; $refs.fileInput.value = ''" class="text-xs text-danger hover:underline">Remove image</button>
                        </div>
                        <input type="file" name="image" accept="image/*" x-ref="fileInput"
                               @change="if($event.target.files[0]) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL($event.target.files[0]); }"
                               class="hidden">
                        <button type="button" @click="$refs.fileInput.click()" class="mt-3 text-sm text-accent hover:underline font-medium">
                            Change Image
                        </button>
                    </div>
                    @error('image')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Unit & Status --}}
                <div class="bg-white rounded-lg border border-border p-5">
                    <h2 class="text-lg font-semibold text-heading mb-4">Other Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="unit" class="form-label">Unit of Measure</label>
                            <select name="unit" id="unit" class="">
                                <option value="piece" {{ old('unit', $item->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                                <option value="box" {{ old('unit', $item->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="pair" {{ old('unit', $item->unit) == 'pair' ? 'selected' : '' }}>Pair</option>
                                <option value="set" {{ old('unit', $item->unit) == 'set' ? 'selected' : '' }}>Set</option>
                                <option value="kg" {{ old('unit', $item->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                <option value="litre" {{ old('unit', $item->unit) == 'litre' ? 'selected' : '' }}>Litre</option>
                                <option value="meter" {{ old('unit', $item->unit) == 'meter' ? 'selected' : '' }}>Meter</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-body mb-2">Status</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-control-bg peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                <span class="ml-2 text-sm text-body">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-border">
            <a href="{{ route('items.show', $item) }}" class="px-6 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-hover transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Update Item
            </button>
        </div>
    </form>
</div>
@endsection
