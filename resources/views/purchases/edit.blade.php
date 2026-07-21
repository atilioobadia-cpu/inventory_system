@extends('layouts.app')

@section('title', 'Edit Purchase - ' . $purchase->invoice_number)

@section('content')
<div class="space-y-6" x-data="purchaseForm()">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center text-sm text-muted mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-accent">Dashboard</a>
                <svg class="h-4 w-4 mx-1 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('purchases.index') }}" class="hover:text-accent">Purchases</a>
                <svg class="h-4 w-4 mx-1 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-heading font-medium">Edit {{ $purchase->invoice_number }}</span>
            </nav>
            <h1 class="text-xl font-bold text-heading">Edit Purchase - {{ $purchase->invoice_number }}</h1>
        </div>
        <a href="{{ route('purchases.show', $purchase) }}" class="px-4 py-2 bg-control-bg hover:bg-control-bg text-body rounded-lg text-sm font-medium">Cancel</a>
    </div>

    @if($purchase->status !== 'draft')
    <div class="bg-warning-light border border-warning rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="h-5 w-5 text-warning flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <p class="text-sm text-warning font-medium">This purchase is <span class="capitalize">{{ $purchase->status }}</span> and cannot be edited.</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('purchases.update', $purchase) }}" @submit.prevent="submitForm()">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Purchase Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white rounded-lg border p-5">
                    <h2 class="text-xl font-semibold text-heading mb-4">Purchase Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Supplier *</label>
                            <div class="relative" x-data="{ open: false, query: '{{ addslashes($purchase->supplier->name ?? '') }}' }" @click.away="open = false">
                                <input type="text" x-model="query" @input.debounce.300ms="searchSupplier()" @focus="open = true; searchSupplier()" placeholder="Search supplier..." class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" {{ $purchase->status !== 'draft' ? 'disabled' : '' }} required>
                                <input type="hidden" name="supplier_id" x-model="supplier.id" required>
                                <div x-show="open && supplierResults.length > 0" class="absolute z-20 w-full bg-white border rounded-lg mt-1 max-h-48 overflow-y-auto">
                                    <template x-for="s in supplierResults" :key="s.id">
                                        <div class="px-3 py-2 hover:bg-accent-light cursor-pointer text-sm" @click="supplier = s; open = false; query = s.name" x-text="s.name"></div>
                                    </template>
                                </div>
                            </div>
                            @error('supplier_id')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Purchase Date *</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" {{ $purchase->status !== 'draft' ? 'disabled' : '' }} required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Payment Terms</label>
                            <select name="payment_terms" x-model="paymentTerms" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                                <option value="cash" {{ old('payment_terms', $purchase->payment_terms) === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="net_7" {{ old('payment_terms', $purchase->payment_terms) === 'net_7' ? 'selected' : '' }}>Net 7 Days</option>
                                <option value="net_15" {{ old('payment_terms', $purchase->payment_terms) === 'net_15' ? 'selected' : '' }}>Net 15 Days</option>
                                <option value="net_30" {{ old('payment_terms', $purchase->payment_terms) === 'net_30' ? 'selected' : '' }}>Net 30 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Due Date</label>
                            <input type="date" name="due_date" x-model="dueDate" value="{{ old('due_date', isset($purchase->due_date) ? $purchase->due_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-body mb-1">Reference (Optional)</label>
                            <input type="text" name="reference" value="{{ old('reference', $purchase->reference ?? '') }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" placeholder="Supplier reference number" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div class="bg-white rounded-lg border p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-heading">Items</h2>
                        @if($purchase->status === 'draft')
                        <button type="button" @click="addItem()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-accent-light hover:bg-accent-light text-accent rounded-lg text-sm font-medium">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Add Item
                        </button>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-card-bg border-b">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-body w-2/5">Item</th>
                                    <th class="px-3 py-2 text-center font-medium text-body w-20">Qty</th>
                                    <th class="px-3 py-2 text-right font-medium text-body w-28">Unit Cost</th>
                                    <th class="hidden sm:table-cell px-3 py-2 text-right font-medium text-body w-24">Discount</th>
                                    <th class="px-3 py-2 text-right font-medium text-body w-28">Total</th>
                                    <th class="px-3 py-2 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr>
                                        <td class="px-3 py-2">
                                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                                <input type="text" x-model="row.itemQuery" @input.debounce.300ms="searchItem(index)" @focus="open = true; searchItem(index)" placeholder="Search item..." class="w-full px-2 py-1.5 border border-border rounded text-sm focus:ring-1 focus:ring-accent/20" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                                                <input type="hidden" :name="'items[' + index + '][item_id]'" :value="row.item_id">
                                                <div x-show="open && row.itemResults.length > 0" class="absolute z-30 w-full bg-white border rounded mt-1 max-h-40 overflow-y-auto">
                                                    <template x-for="item in row.itemResults" :key="item.id">
                                                        <div class="px-3 py-2 hover:bg-accent-light cursor-pointer text-sm" @click="selectItem(index, item); open = false" x-text="item.name + ' (Stock: ' + item.stock + ')'"></div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="row.quantity" @input="calcRow(index)" min="1" class="w-full px-2 py-1.5 border border-border rounded text-sm text-center" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" :name="'items[' + index + '][unit_cost]'" x-model.number="row.unit_cost" @input="calcRow(index)" min="0" step="0.01" class="w-full px-2 py-1.5 border border-border rounded text-sm text-right" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 py-2">
                                            <input type="number" :name="'items[' + index + '][discount]'" x-model.number="row.discount" @input="calcRow(index)" min="0" step="0.01" class="w-full px-2 py-1.5 border border-border rounded text-sm text-right" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-heading" x-text="formatCurrency(row.total)"></td>
                                        <td class="px-3 py-2 text-center">
                                            @if($purchase->status === 'draft')
                                            <button type="button" @click="removeRow(index)" x-show="rows.length > 1" class="text-muted hover:text-danger">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right: Summary --}}
            <div class="space-y-6">
                <div class="bg-white rounded-lg border p-5 sticky top-20">
                    <h2 class="text-xl font-semibold text-heading mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-body">Subtotal</span>
                            <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-body">Tax (18%)</span>
                            <span class="font-medium" x-text="formatCurrency(taxAmount)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-body">Discount</span>
                            <span class="font-medium text-danger" x-text="'- ' + formatCurrency(totalDiscount)"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-3">
                            <span>Total</span>
                            <span class="text-accent" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Paid Amount</label>
                            <input type="number" name="paid_amount" x-model.number="paidAmount" min="0" :max="total" step="0.01" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-body">Due</span>
                            <span class="font-bold text-danger" x-text="formatCurrency(Math.max(0, total - paidAmount))"></span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-body mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" placeholder="Purchase notes..." {{ $purchase->status !== 'draft' ? 'disabled' : '' }}>{{ old('notes', $purchase->notes ?? '') }}</textarea>
                    </div>

                    @if($purchase->status === 'draft')
                    <div class="mt-6 space-y-3">
                        <button type="submit" name="action" value="draft" class="w-full inline-flex items-center justify-center gap-2 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium text-sm transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            Update Purchase
                        </button>
                        <button type="submit" name="action" value="receive" class="w-full inline-flex items-center justify-center gap-2 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium text-sm transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Update & Receive
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function purchaseForm() {
    return {
        rows: @json($purchase->items->map(fn($item) => [
            'item_id' => $item->item_id,
            'item_name' => $item->item->name ?? '',
            'itemQuery' => $item->item->name ?? '',
            'quantity' => $item->quantity,
            'unit_cost' => $item->unit_cost,
            'discount' => $item->discount ?? 0,
            'total' => ($item->quantity * $item->unit_cost) - ($item->discount ?? 0),
            'itemResults' => [],
        ])),
        supplier: { id: '{{ $purchase->supplier_id }}', name: '{{ addslashes($purchase->supplier->name ?? '') }}' },
        supplierResults: [],
        paymentTerms: '{{ old("payment_terms", $purchase->payment_terms) }}',
        dueDate: '{{ old("due_date", isset($purchase->due_date) ? $purchase->due_date->format("Y-m-d") : "") }}',
        paidAmount: {{ $purchase->paid_amount ?? 0 }},

        get subtotal() { return this.rows.reduce((s, r) => s + (r.quantity * r.unit_cost), 0); },
        get totalDiscount() { return this.rows.reduce((s, r) => s + r.discount, 0); },
        get taxAmount() { return (this.subtotal - this.totalDiscount) * 0.18; },
        get total() { return this.subtotal + this.taxAmount - this.totalDiscount; },

        formatCurrency(v) { return 'TZS ' + Number(v || 0).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 }); },
        addItem() { this.rows.push({ item_id: '', item_name: '', itemQuery: '', quantity: 1, unit_cost: 0, discount: 0, total: 0, itemResults: [] }); },
        removeRow(i) { this.rows.splice(i, 1); },
        calcRow(i) { this.rows[i].total = (this.rows[i].quantity * this.rows[i].unit_cost) - this.rows[i].discount; },
        async searchSupplier() {
            try { const r = await fetch('{{ route("api.suppliers.search") }}?q=' + encodeURIComponent(this.supplier.name || '')); this.supplierResults = await r.json(); } catch(e) {}
        },
        async searchItem(index) {
            try { const r = await fetch('{{ route("api.items.search") }}?q=' + encodeURIComponent(this.rows[index].itemQuery || '')); this.rows[index].itemResults = await r.json(); } catch(e) {}
        },
        selectItem(index, item) {
            this.rows[index].item_id = item.id;
            this.rows[index].item_name = item.name;
            this.rows[index].unit_cost = parseFloat(item.cost_price);
            this.rows[index].itemQuery = item.name;
            this.calcRow(index);
        },
        submitForm() { this.$el.querySelector('form').submit(); }
    };
}
</script>
@endpush
