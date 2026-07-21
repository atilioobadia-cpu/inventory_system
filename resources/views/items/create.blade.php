@extends('layouts.app')

@section('title', 'Create Item')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('items.index') }}" class="hover:text-electric transition-colors">Items</a>
<span class="mx-2">/</span>
<span class="text-gray-800">Create New Item</span>
@endsection

@section('content')
<div x-data="{ autoSku: false, imagePreview: null }">
    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Create New Item</h1>
            <div class="flex gap-3">
                <a href="{{ route('items.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-white bg-electric rounded-lg hover:bg-blue-600 transition-colors">
                    Save Item
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="e.g. Brake Pads - Front">
                            @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric font-mono"
                                   placeholder="e.g. BP-FR-001">
                            <label class="flex items-center gap-2 mt-1 cursor-pointer">
                                <input type="checkbox" x-model="autoSku" class="w-3.5 h-3.5 text-electric border-gray-300 rounded focus:ring-electric">
                                <span class="text-xs text-gray-500">Auto-generate SKU</span>
                            </label>
                            @error('sku')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                            <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric font-mono"
                                   placeholder="Scan or enter barcode">
                            @error('barcode')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric text-gray-600">
                                <option value="">Select Category</option>
                                @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric text-gray-600">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Description</h2>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric resize-none"
                              placeholder="Enter item description...">{{ old('description') }}</textarea>
                    @error('description')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-1">Cost Price (TZS) <span class="text-danger">*</span></label>
                            <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}" required min="0" step="0.01"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0">
                            @error('cost_price')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price (TZS) <span class="text-danger">*</span></label>
                            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" required min="0" step="0.01"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0">
                            @error('selling_price')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', 0) }}" min="0" max="100" step="0.01"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0">
                            @error('tax_rate')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Stock Levels --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Stock Levels</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock</label>
                            <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 0) }}" min="0"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0">
                            @error('min_stock')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="max_stock" class="block text-sm font-medium text-gray-700 mb-1">Maximum Stock</label>
                            <input type="number" name="max_stock" id="max_stock" value="{{ old('max_stock') }}" min="0"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0">
                            @error('max_stock')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="reorder_point" class="block text-sm font-medium text-gray-700 mb-1">Reorder Point</label>
                            <input type="number" name="reorder_point" id="reorder_point" value="{{ old('reorder_point', 0) }}" min="0"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0">
                            @error('reorder_point')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Item Image</h2>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-electric/50 transition-colors">
                        <div x-show="!imagePreview">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/>
                            </svg>
                            <p class="text-sm text-gray-500 mb-2">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                        <div x-show="imagePreview" x-cloak>
                            <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg mx-auto mb-3">
                            <button type="button" @click="imagePreview = null; $refs.fileInput.value = ''" class="text-xs text-danger hover:underline">Remove image</button>
                        </div>
                        <input type="file" name="image" accept="image/*" x-ref="fileInput"
                               @change="if($event.target.files[0]) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL($event.target.files[0]); }"
                               class="hidden">
                        <button type="button" @click="$refs.fileInput.click()" class="mt-3 text-sm text-electric hover:underline font-medium">
                            Choose File
                        </button>
                    </div>
                    @error('image')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Unit & Status --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Other Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
                            <select name="unit" id="unit" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric text-gray-600">
                                <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                                <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="pair" {{ old('unit') == 'pair' ? 'selected' : '' }}>Pair</option>
                                <option value="set" {{ old('unit') == 'set' ? 'selected' : '' }}>Set</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                <option value="litre" {{ old('unit') == 'litre' ? 'selected' : '' }}>Litre</option>
                                <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                                       class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-electric/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('items.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-electric rounded-lg hover:bg-blue-600 transition-colors">
                Save Item
            </button>
        </div>
    </form>
</div>
@endsection