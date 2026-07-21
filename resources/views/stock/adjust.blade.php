@extends('layouts.app')

@section('title', 'Adjust Stock - Mtokoma')

@section('header-title', 'Adjust Stock')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <a href="{{ route('stock.index') }}" class="hover:text-accent transition-colors">Stock</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Adjust Stock</span>
@endsection

@section('content')
<div x-data="{
    search: '',
    selectedItem: null,
    items: [],
    adjustingType: 'add',
    quantity: 1,
    notes: '',
    searching: false,
    dropdownOpen: false,
    async loadInitialItems() {
        if (this.items.length > 0) return;
        this.searching = true;
        try {
            const res = await fetch('{{ route('api.items.search') }}?q=');
            const data = await res.json();
            this.items = data.slice(0, 5);
        } catch(e) { this.items = []; }
        this.searching = false;
    },
    async searchItems() {
        this.searching = true;
        try {
            const res = await fetch('{{ route('api.items.search') }}?q=' + encodeURIComponent(this.search));
            const data = await res.json();
            this.items = this.search.length === 0 ? data.slice(0, 5) : data;
        } catch(e) { this.items = []; }
        this.searching = false;
    },
    selectItem(item) {
        this.selectedItem = item;
        this.items = [];
        this.dropdownOpen = false;
        this.search = item.name;
    },
    get projectedStock() {
        if (!this.selectedItem) return null;
        const current = this.selectedItem.current_stock || 0;
        const qty = parseInt(this.quantity) || 0;
        return this.adjustingType === 'adjustment' ? current + qty : current - qty;
    }
}">
    <div class="max-w-2xl mx-auto">
        <div class="bg-card-bg rounded-lg border border-border">
            <div class="px-5 py-4 border-b border-border">
                <h3 class="text-lg font-semibold text-heading">Stock Adjustment</h3>
                <p class="text-sm text-muted mt-1">Add or remove stock for an item</p>
            </div>

            <form action="{{ route('stock.adjust') }}" method="POST" class="p-5">
                @csrf
                <div class="space-y-6">
                    {{-- Item Search --}}
                    <div>
                        <label class="block text-sm font-medium text-body mb-1">Item <span class="text-danger">*</span></label>
                        <div class="relative">
                            <input type="text" x-model="search" @input.debounce.300ms="searchItems()" @focus="dropdownOpen = true; loadInitialItems()" placeholder="Search items by name or SKU..."
                                   class="w-full px-4 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent" required>
                            <input type="hidden" name="item_id" :value="selectedItem?.id">
                            <div x-show="dropdownOpen && items.length > 0" @click.away="dropdownOpen = false; items = []" class="absolute z-10 w-full mt-1 bg-control-bg border border-border rounded-lg max-h-60 overflow-y-auto">
                                <template x-for="item in items" :key="item.id">
                                    <button type="button" @click="selectItem(item)" class="w-full text-left px-4 py-3 hover:bg-card-bg border-b border-border last:border-0">
                                        <p class="text-sm font-medium text-heading" x-text="item.name"></p>
                                        <p class="text-xs text-muted">
                                            SKU: <span x-text="item.sku"></span> | Stock: <span x-text="item.current_stock ?? 0" class="font-bold"></span>
                                        </p>
                                    </button>
                                </template>
                            </div>
                            <div x-show="searching" class="absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin h-4 w-4 text-muted" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </div>
                        </div>
                        @error('item_id') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Selected Item Info --}}
                    <div x-show="selectedItem" x-cloak class="bg-card-bg rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs text-muted uppercase tracking-wider">Item</p>
                                <p class="text-sm font-semibold text-heading mt-1" x-text="selectedItem?.name"></p>
                            </div>
                            <div>
                                <p class="text-xs text-muted uppercase tracking-wider">Current Stock</p>
                                <p class="text-lg font-bold text-heading mt-1" x-text="selectedItem?.current_stock ?? 0"></p>
                            </div>
                            <div>
                                <p class="text-xs text-muted uppercase tracking-wider">SKU</p>
                                <p class="text-sm font-mono text-body mt-1" x-text="selectedItem?.sku"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Adjustment Type --}}
                    <div>
                        <label class="block text-sm font-medium text-body mb-2">Adjustment Type <span class="text-danger">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="adjustment" x-model="adjustingType" class="peer sr-only" required>
                                <div class="border-2 border-border rounded-lg p-4 text-center peer-checked:border-success peer-checked:bg-success-light transition-all hover:border-border">
                                    <svg class="w-6 h-6 text-success mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    <span class="text-sm font-medium text-body peer-checked:text-success">Add Stock</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="damage" x-model="adjustingType" class="peer sr-only">
                                <div class="border-2 border-border rounded-lg p-4 text-center peer-checked:border-danger peer-checked:bg-danger-light transition-all hover:border-border">
                                    <svg class="w-6 h-6 text-danger mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                                    </svg>
                                    <span class="text-sm font-medium text-body peer-checked:text-danger">Remove Stock</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-body mb-1">Quantity <span class="text-danger">*</span></label>
                        <input type="number" x-model="quantity" min="1" required
                               class="w-full px-4 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent">
                        <input type="hidden" name="quantity" :value="adjustingType === 'damage' ? -Math.abs(parseInt(quantity) || 0) : Math.abs(parseInt(quantity) || 0)">
                        @error('quantity') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Projected Stock Preview --}}
                    <div x-show="selectedItem && quantity > 0" x-cloak class="rounded-lg p-4"
                         :class="projectedStock < 0 ? 'bg-danger-light border border-danger' : 'bg-accent-light border border-accent'">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" :class="projectedStock < 0 ? 'text-danger' : 'text-accent'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium" :class="projectedStock < 0 ? 'text-danger' : 'text-accent'">
                                    Projected Stock After Adjustment:
                                    <span class="font-bold" x-text="projectedStock"></span>
                                </p>
                                <p x-show="projectedStock < 0" class="text-xs text-danger mt-0.5">Warning: Stock will be negative!</p>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-body mb-1">Notes</label>
                        <textarea name="notes" x-model="notes" rows="3" placeholder="Reason for adjustment..."
                                  class="w-full px-4 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent"></textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                    <a href="{{ route('stock.index') }}" class="px-4 py-2.5 text-sm font-medium text-body bg-control-bg border border-border rounded-lg hover:bg-card-bg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-hover transition-colors">
                        Submit Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
