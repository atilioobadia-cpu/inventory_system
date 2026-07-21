@extends('layouts.app')

@section('title', 'Point of Sale')

@push('styles')
<style>
    .pos-grid { display: grid; grid-template-columns: 1fr 420px; height: calc(100vh - 64px); gap: 0; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; padding: 16px; overflow-y: auto; }
    .product-card { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.15s; text-align: center; }
    .product-card:hover { border-color: #2563EB; }
    .product-card.out-of-stock { opacity: 0.5; pointer-events: none; }
    .cart-panel { background: #fff; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; height: calc(100vh - 64px); }
    .cart-items { flex: 1; overflow-y: auto; padding: 16px; }
    .cart-summary { border-top: 2px solid #e2e8f0; padding: 16px; background: #f8fafc; }
    .qty-btn { width: 28px; height: 28px; border-radius: 4px; border: 1px solid #d1d5db; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: 600; }
    .qty-btn:hover { background: #f3f4f6; }
    .quick-cash { padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; cursor: pointer; }
    .quick-cash:hover { background: #f3f4f6; }
    .category-pill { padding: 6px 16px; border-radius: 20px; border: 1px solid #d1d5db; font-size: 13px; cursor: pointer; white-space: nowrap; }
    .category-pill.active { background: #2563EB; color: white; border-color: #2563EB; }
    .category-pill:hover:not(.active) { background: #f3f4f6; }
    @media print { .pos-hide { display: none !important; } }
</style>
@endpush

@section('content')
<div x-data="posApp()" x-init="init()" class="pos-grid">

    {{-- LEFT: Product Grid --}}
    <div class="flex flex-col h-full bg-card-bg pos-hide">
        {{-- Search & Filters --}}
        <div class="p-4 bg-white border-b space-y-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" x-model="searchQuery" @input.debounce.300ms="searchItems()" placeholder="Search by name, SKU, or barcode..." class="w-full pl-10 pr-4 py-2.5 border border-border rounded-lg focus:ring-2 focus:ring-accent/20 focus:border-accent text-sm">
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button @click="selectedCategory = ''; searchItems()" :class="selectedCategory === '' ? 'active' : ''" class="category-pill">All</button>
                @foreach($categories as $category)
                    <button @click="selectedCategory = '{{ $category->id }}'; searchItems()" :class="selectedCategory == '{{ $category->id }}' ? 'active' : ''" class="category-pill">{{ $category->name }}</button>
                @endforeach
            </div>
        </div>

        {{-- Products --}}
        <div class="flex-1 overflow-y-auto p-4">
            <template x-if="loading">
                <div class="flex items-center justify-center h-40">
                    <svg class="animate-spin h-8 w-8 text-accent" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </div>
            </template>
            <template x-if="!loading && products.length === 0">
                <div class="flex flex-col items-center justify-center h-40 text-muted">
                    <svg class="h-12 w-12 mb-3 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    <p>No items found</p>
                </div>
            </template>
            <div class="product-grid" x-show="!loading">
                <template x-for="item in products" :key="item.id">
                    <div class="product-card" :class="item.current_stock <= 0 ? 'out-of-stock' : ''" @click="addToCart(item)">
                        <div class="w-full h-20 bg-control-bg rounded mb-2 flex items-center justify-center overflow-hidden">
                            <template x-if="item.image">
                                <img :src="'{{ asset('storage') }}/' + item.image" class="h-20 w-full object-cover rounded">
                            </template>
                            <template x-if="!item.image">
                                <svg class="h-10 w-10 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            </template>
                        </div>
                        <p class="text-xs font-medium text-heading truncate" x-text="item.name"></p>
                        <p class="text-sm font-bold text-accent mt-1" x-text="formatCurrency(item.selling_price)"></p>
                        <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full" :class="item.current_stock > 0 ? 'bg-success-light text-success' : 'bg-danger-light text-danger'" x-text="'Stock: ' + item.current_stock"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- RIGHT: Cart Panel --}}
    <div class="cart-panel">
        {{-- Cart Header --}}
        <div class="p-4 border-b bg-white flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-heading">Current Order</h2>
                <p class="text-xs text-muted" x-text="cartItems.length + ' item(s)'"></p>
            </div>
            <button @click="clearCart()" class="text-sm text-danger hover:text-danger font-medium" x-show="cartItems.length > 0">Clear All</button>
        </div>

        {{-- Cart Items --}}
        <div class="cart-items">
            <template x-if="cartItems.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-muted">
                    <svg class="h-16 w-16 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.328A1.125 1.125 0 0018.054 2.25H5.106m2.394 5.266L7.5 14.25m0 0l-1.5 6.75M7.5 14.25L3.75 3M20.25 21h-15"/></svg>
                    <p class="font-medium">Cart is empty</p>
                    <p class="text-sm mt-1">Click products to add</p>
                </div>
            </template>
            <template x-for="(item, index) in cartItems" :key="item.id">
                <div class="flex items-center gap-3 py-3 border-b border-border">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-heading truncate" x-text="item.name"></p>
                        <p class="text-xs text-muted" x-text="formatCurrency(item.selling_price) + ' each'"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="qty-btn" @click="updateQuantity(index, item.quantity - 1)">-</button>
                        <input type="number" :value="item.quantity" @change="updateQuantity(index, parseInt($event.target.value))" min="1" class="w-12 text-center border rounded text-sm py-1">
                        <button class="qty-btn" @click="updateQuantity(index, item.quantity + 1)">+</button>
                    </div>
                    <p class="text-sm font-semibold text-heading w-24 text-right" x-text="formatCurrency(item.selling_price * item.quantity)"></p>
                    <button @click="removeFromCart(index)" class="text-muted hover:text-danger ml-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- Cart Summary --}}
        <div class="cart-summary" x-show="cartItems.length > 0">
            {{-- Customer --}}
            <div class="mb-3">
                <label class="text-xs font-medium text-body mb-1 block">Customer</label>
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <input type="text" x-model="customerQuery" @input.debounce.300ms="searchCustomers()" @focus="open = true" placeholder="Walk-In Customer" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20">
                    <input type="hidden" x-model="customer.id" name="customer_id">
                    <div x-show="open && customerResults.length > 0" class="absolute z-20 w-full bg-white border rounded-lg mt-1 max-h-40 overflow-y-auto">
                        <template x-for="c in customerResults" :key="c.id">
                            <div class="px-3 py-2 hover:bg-accent-light cursor-pointer text-sm" @click="customer = c; open = false; customerQuery = ''" x-text="c.name + (c.phone ? ' (' + c.phone + ')' : '')"></div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- VAT & Sale Type --}}
            <div class="flex gap-3 mb-3">
                <div class="flex-1">
                    <label class="text-xs font-medium text-body mb-1 block">Sale Type</label>
                    <select x-model="saleType" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="cash">Cash</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="isVatExempt" class="w-4 h-4 rounded text-accent">
                        <span class="text-sm font-medium">VAT Exempt</span>
                    </label>
                </div>
            </div>

            {{-- Totals --}}
            <div class="space-y-2 mb-3">
                <div class="flex justify-between text-sm">
                    <span class="text-body">Subtotal</span>
                    <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                </div>
                <div class="flex justify-between text-sm" x-show="!isVatExempt">
                    <span class="text-body">VAT (18%)</span>
                    <span class="font-medium" x-text="formatCurrency(vat)"></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-body">Discount</span>
                    <input type="number" x-model.number="discount" min="0" class="w-24 px-2 py-1 border rounded text-sm text-right ml-auto">
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Total</span>
                    <span class="text-accent" x-text="formatCurrency(total)"></span>
                </div>
            </div>

            {{-- Cash Received (for cash sales) --}}
            <div x-show="saleType === 'cash'" class="mb-3">
                <label class="text-xs font-medium text-body mb-1 block">Cash Received</label>
                <input type="number" x-model.number="cashReceived" min="0" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent/20" placeholder="0">
                <div class="flex gap-2 mt-2 flex-wrap">
                    <button @click="cashReceived = total" class="quick-cash">Exact</button>
                    <button @click="cashReceived = Math.ceil(total / 1000) * 1000" class="quick-cash">TZS {{ number_format(ceil(10000/1000)*1000) }}</button>
                    <button @click="cashReceived = 10000" class="quick-cash">10,000</button>
                    <button @click="cashReceived = 20000" class="quick-cash">20,000</button>
                    <button @click="cashReceived = 50000" class="quick-cash">50,000</button>
                    <button @click="cashReceived = 100000" class="quick-cash">100,000</button>
                </div>
                <div class="flex justify-between text-sm mt-2" x-show="change > 0">
                    <span class="text-body">Change</span>
                    <span class="font-bold text-success" x-text="formatCurrency(change)"></span>
                </div>
                <div class="text-sm mt-1" x-show="cashReceived > 0 && cashReceived < total">
                    <span class="text-danger font-medium">Insufficient amount</span>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-3">
                <input type="text" x-model="notes" placeholder="Notes (optional)" class="w-full px-3 py-2 border border-border rounded-lg text-sm">
            </div>

            {{-- Process Button --}}
            <button @click="processSale()" :disabled="processing || cartItems.length === 0 || (saleType === 'cash' && cashReceived < total)" class="w-full py-3 bg-primary hover:bg-primary-hover disabled:bg-muted text-white font-bold rounded-lg text-lg transition-colors flex items-center justify-center gap-2">
                <template x-if="processing">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </template>
                <span x-text="processing ? 'Processing...' : 'Complete Sale - ' + formatCurrency(total)"></span>
            </button>
        </div>
    </div>

    {{-- Success Modal --}}
    <div x-show="showSuccess" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
        <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 text-center shadow-2xl">
            <div class="w-16 h-16 bg-success-light rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <h3 class="text-xl font-bold text-heading mb-2">Sale Completed!</h3>
            <p class="text-body mb-1" x-text="'Invoice: ' + lastSaleInvoice"></p>
            <p class="text-body mb-4" x-text="'Total: ' + formatCurrency(lastSaleTotal)"></p>
            <div class="flex gap-3">
                <a :href="lastSaleReceiptUrl" target="_blank" class="flex-1 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium text-sm">View Receipt</a>
                <button @click="showSuccess = false; resetPos()" class="flex-1 py-2 bg-control-bg hover:bg-control-bg text-heading rounded-lg font-medium text-sm">New Sale</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function posApp() {
    return {
        products: [],
        categories: @json($categories),
        cartItems: [],
        searchQuery: '',
        selectedCategory: '',
        loading: false,
        processing: false,
        customer: { id: null, name: 'Walk-In' },
        customerQuery: '',
        customerResults: [],
        saleType: 'cash',
        isVatExempt: false,
        discount: 0,
        cashReceived: 0,
        notes: '',
        showSuccess: false,
        lastSaleInvoice: '',
        lastSaleTotal: 0,
        lastSaleReceiptUrl: '',

        init() {
            this.searchItems();
        },

        get subtotal() {
            return this.cartItems.reduce((sum, item) => sum + (item.selling_price * item.quantity), 0);
        },

        get vat() {
            return this.isVatExempt ? 0 : this.subtotal * 0.18;
        },

        get total() {
            return this.subtotal + this.vat - this.discount;
        },

        get change() {
            return Math.max(0, this.cashReceived - this.total);
        },

        formatCurrency(amount) {
            return 'TZS ' + Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        },

        async searchItems() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('q', this.searchQuery);
                if (this.selectedCategory) params.append('category', this.selectedCategory);
                const res = await fetch('{{ route("api.items.search") }}?' + params.toString());
                const data = await res.json();
                this.products = data;
            } catch(e) { console.error(e); }
            this.loading = false;
        },

        async searchCustomers() {
            try {
                const res = await fetch('{{ route("api.customers.search") }}?q=' + encodeURIComponent(this.customerQuery || ''));
                this.customerResults = await res.json();
            } catch(e) {}
        },

        addToCart(item) {
            if (item.current_stock <= 0) return;
            const existing = this.cartItems.find(c => c.id === item.id);
            if (existing) {
                if (existing.quantity < item.current_stock) {
                    existing.quantity++;
                }
            } else {
                this.cartItems.push({
                    id: item.id,
                    name: item.name,
                    sku: item.sku,
                    selling_price: parseFloat(item.selling_price),
                    cost_price: parseFloat(item.cost_price),
                    quantity: 1,
                    max_stock: item.current_stock
                });
            }
        },

        removeFromCart(index) {
            this.cartItems.splice(index, 1);
        },

        updateQuantity(index, qty) {
            if (qty <= 0) {
                this.removeFromCart(index);
                return;
            }
            if (qty > this.cartItems[index].max_stock) {
                qty = this.cartItems[index].max_stock;
            }
            this.cartItems[index].quantity = qty;
        },

        clearCart() {
            this.cartItems = [];
            this.discount = 0;
            this.cashReceived = 0;
            this.notes = '';
        },

        resetPos() {
            this.clearCart();
            this.customer = { id: null, name: 'Walk-In' };
            this.customerQuery = '';
            this.saleType = 'cash';
            this.isVatExempt = false;
            this.searchQuery = '';
            this.selectedCategory = '';
            this.searchItems();
        },

        async processSale() {
            if (this.processing || this.cartItems.length === 0) return;
            if (this.saleType === 'cash' && this.cashReceived < this.total) return;

            this.processing = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('{{ route("pos.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_id: this.customer.id,
                        sale_type: this.saleType,
                        is_vat_exempt: this.isVatExempt,
                        discount: this.discount,
                        cash_received: this.cashReceived,
                        notes: this.notes,
                        items: this.cartItems.map(item => ({
                            item_id: item.id,
                            quantity: item.quantity,
                            unit_price: item.selling_price
                        }))
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    this.lastSaleInvoice = data.invoice_number;
                    this.lastSaleTotal = data.total_amount;
                    this.lastSaleReceiptUrl = '{{ url("/") }}/receipts/' + data.sale_id + '/print';
                    this.showSuccess = true;
                } else {
                    alert(data.message || 'Error processing sale');
                }
            } catch(e) {
                console.error(e);
                alert('Network error. Please try again.');
            }
            this.processing = false;
        }
    };
}
</script>
@endpush
