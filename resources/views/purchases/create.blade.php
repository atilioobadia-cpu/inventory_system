@extends('layouts.app')

@section('title', 'Create Purchase')

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
                <span class="text-heading font-medium">Create</span>
            </nav>
            <h1 class="text-2xl font-bold text-heading">Create Purchase</h1>
        </div>
        <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-control-bg hover:bg-gray-200 text-body rounded-lg text-sm font-medium">Cancel</a>
    </div>

    <form method="POST" action="{{ route('purchases.store') }}" @submit.prevent="submitForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Purchase Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white rounded-xl border p-6">
                    <h2 class="text-lg font-semibold text-heading mb-4">Purchase Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Supplier *</label>
                            <div class="relative" x-data="{ open: false, query: '' }" @click.away="open = false">
                                <input type="text" x-model="query" @input.debounce.300ms="searchSupplier()" @focus="open = true" :value="supplier.name || ''" placeholder="Search supplier..." required>
                                <input type="hidden" name="supplier_id" x-model="supplier.id" required>
                                <div x-show="open && supplierResults.length > 0" class="absolute z-20 w-full bg-white border rounded-lg mt-1 max-h-48 overflow-y-auto">
                                    <template x-for="s in supplierResults" :key="s.id">
                                        <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="supplier = s; open = false; query = ''" x-text="s.name"></div>
                                    </template>
                                </div>
                            </div>
                            @error('supplier_id')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Purchase Date *</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                        </div>
                        <div>
                            <label class="form-label">Payment Terms</label>
                            <select name="payment_terms" x-model="paymentTerms">
                                <option value="cash">Cash</option>
                                <option value="net_7">Net 7 Days</option>
                                <option value="net_15">Net 15 Days</option>
                                <option value="net_30">Net 30 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" x-model="dueDate">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Reference (Optional)</label>
                            <input type="text" name="reference" placeholder="Supplier reference number">
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div class="bg-white rounded-xl border p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-heading">Items</h2>
                        <button type="button" @click="addItem()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-accent-light text-blue-700 rounded-lg text-sm font-medium">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Add Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-card-bg border-b">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-600 w-2/5">Item</th>
                                    <th class="px-3 py-2 text-center font-medium text-gray-600 w-20">Qty</th>
                                    <th class="px-3 py-2 text-right font-medium text-gray-600 w-28">Unit Cost</th>
                                    <th class="px-3 py-2 text-right font-medium text-gray-600 w-24">Discount</th>
                                    <th class="px-3 py-2 text-right font-medium text-gray-600 w-28">Total</th>
                                    <th class="px-3 py-2 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr>
                                        <td class="px-3 py-2">
                                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                                <input type="text" x-model="row.itemQuery" @input.debounce.300ms="searchItem(index)" @focus="open = true" :placeholder="row.item_name || 'Search item...'" class="w-full px-2 py-1.5 text-sm focus:ring-1 focus:ring-accent/20">
                                                <input type="hidden" :name="'items[' + index + '][item_id]'" :value="row.item_id">
                                                <div x-show="open && row.itemResults.length > 0" class="absolute z-30 w-full bg-white border rounded mt-1 max-h-40 overflow-y-auto">
                                                    <template x-for="item in row.itemResults" :key="item.id">
                                                        <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="selectItem(index, item); open = false" x-text="item.name + ' (Stock: ' + item.stock + ')'"></div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="row.quantity" @input="calcRow(index)" min="1" class="w-full px-2 py-1.5 text-sm text-center">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" :name="'items[' + index + '][unit_cost]'" x-model.number="row.unit_cost" @input="calcRow(index)" min="0" step="0.01" class="w-full px-2 py-1.5 text-sm text-right">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" :name="'items[' + index + '][discount]'" x-model.number="row.discount" @input="calcRow(index)" min="0" step="0.01" class="w-full px-2 py-1.5 text-sm text-right">
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-heading" x-text="formatCurrency(row.total)"></td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" @click="removeRow(index)" x-show="rows.length > 1" class="text-muted hover:text-danger">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
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
                <div class="bg-white rounded-xl border p-6 sticky top-20">
                    <h2 class="text-lg font-semibold text-heading mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax (18%)</span>
                            <span class="font-medium" x-text="formatCurrency(taxAmount)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-danger" x-text="'- ' + formatCurrency(totalDiscount)"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-3">
                            <span>Total</span>
                            <span class="text-accent" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div>
                            <label class="form-label">Paid Amount</label>
                            <input type="number" name="paid_amount" x-model.number="paidAmount" min="0" :max="total" step="0.01">
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Due</span>
                            <span class="font-bold text-danger" x-text="formatCurrency(Math.max(0, total - paidAmount))"></span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" placeholder="Purchase notes..."></textarea>
                    </div>

                    <div class="mt-6 space-y-3">
                        <button type="submit" name="action" value="draft" class="w-full py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium text-sm transition-colors">Save as Draft</button>
                        <button type="submit" name="action" value="receive" class="w-full py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium text-sm transition-colors">Save & Receive</button>
                    </div>
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
        rows: [{ item_id: '', item_name: '', itemQuery: '', quantity: 1, unit_cost: 0, discount: 0, total: 0, itemResults: [] }],
        supplier: { id: '', name: '' },
        supplierResults: [],
        paymentTerms: 'cash',
        dueDate: '',
        paidAmount: 0,

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
