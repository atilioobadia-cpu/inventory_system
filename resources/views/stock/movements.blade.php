@extends('layouts.app')

@section('title', 'Stock Movements - Mtokoma')

@section('header-title', 'Stock Movements')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <a href="{{ route('stock.index') }}" class="hover:text-accent transition-colors">Stock</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Movements</span>
@endsection

@section('content')
<div x-data="{
    search: '{{ request('search') }}',
    dateFrom: '{{ request('date_from') }}',
    dateTo: '{{ request('date_to') }}',
    type: '{{ request('type') }}',
    direction: '{{ request('direction') }}',
    showAdjustModal: false,
    adjustForm: { item_id: '', quantity: 0, type: 'adjustment', notes: '' }
}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-heading">Stock Movements</h2>
            <p class="text-sm text-muted mt-1">Track all inventory movements and adjustments</p>
        </div>
        <button @click="showAdjustModal = true" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Adjust Stock
        </button>
    </div>

    <div class="bg-white rounded-lg border border-border">
        <form method="GET" action="{{ route('stock.movements') }}">
            <div class="p-4 border-b border-border grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" x-model="search" placeholder="Search by item name..." class="w-full pl-10 pr-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent">
                    </div>
                </div>
                <div>
                    <input type="date" name="date_from" x-model="dateFrom" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent">
                </div>
                <div>
                    <input type="date" name="date_to" x-model="dateTo" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent">
                </div>
                <div>
                    <select name="type" x-model="type" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent bg-white">
                        <option value="">All Types</option>
                        <option value="purchase">Purchase</option>
                        <option value="sale">Sale</option>
                        <option value="adjustment">Adjustment</option>
                        <option value="opening_stock">Opening Stock</option>
                        <option value="return">Return</option>
                        <option value="damage">Damage</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-b border-border flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-body">Direction:</label>
                    <select name="direction" x-model="direction" class="px-3 py-1.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent bg-white">
                        <option value="">All</option>
                        <option value="in">In (Stock Received)</option>
                        <option value="out">Out (Stock Sent)</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 ml-auto">
                    <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">Filter</button>
                    <a href="{{ route('stock.movements') }}" class="text-muted hover:text-body px-3 py-1.5 text-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-card-bg border-b border-border">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Date/Time</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Item</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Type</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Direction</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Qty</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Unit Cost</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Bal. Before</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Bal. After</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Reference</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">User</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @php
                        $typeStyles = [
                            'purchase' => ['bg-accent-light text-accent', 'Purchase'],
                            'sale' => ['bg-success-light text-success', 'Sale'],
                            'adjustment' => ['bg-warning-light text-amber-800', 'Adjustment'],
                            'opening_stock' => ['bg-control-bg text-heading', 'Opening'],
                            'return' => ['bg-purple-100 text-purple-800', 'Return'],
                            'damage' => ['bg-danger-light text-danger', 'Damage'],
                            'transfer' => ['bg-cyan-100 text-cyan-800', 'Transfer'],
                        ];
                    @endphp
                    @forelse($movements ?? [] as $m)
                        @php
                            $style = $typeStyles[$m->type] ?? ['bg-control-bg text-heading', ucfirst($m->type)];
                            $isIn = $m->direction === 'in';
                        @endphp
                        <tr class="hover:bg-card-bg transition-colors">
                            <td class="px-4 py-3 text-sm text-muted whitespace-nowrap">{{ $m->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-heading">{{ $m->item->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $style[0] }}">{{ $style[1] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isIn)
                                    <span class="inline-flex items-center gap-1 text-success">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75"/></svg>
                                        <span class="text-xs font-medium">In</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-danger">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75"/></svg>
                                        <span class="text-xs font-medium">Out</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-center {{ $isIn ? 'text-success' : 'text-danger' }}">
                                {{ $isIn ? '+' : '-' }}{{ $m->quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($m->unit_cost ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-muted text-right">{{ $m->balance_before ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-heading text-right font-medium">{{ $m->balance_after ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-muted">
                                @if($m->reference)
                                    <span class="font-mono text-xs bg-control-bg px-2 py-0.5 rounded">{{ $m->reference }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-muted">{{ $m->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-muted max-w-[150px] truncate" title="{{ $m->notes ?? '' }}">{{ $m->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-16 text-center">
                                <svg class="w-16 h-16 text-muted mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-muted font-medium">No stock movements found</p>
                                <p class="text-sm text-muted mt-1">Movements will appear here as inventory changes.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($movements) && $movements->hasPages())
        <div class="px-4 py-3 border-t border-border">
            {{ $movements->links() }}
        </div>
        @endif
    </div>

    {{-- Stock Adjustment Modal --}}
    <div x-show="showAdjustModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="showAdjustModal = false"
             class="bg-white rounded-xl w-full max-w-md">
            <form method="POST" action="{{ route('stock.adjust') }}">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-heading mb-4">Stock Adjustment</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Item *</label>
                            <select name="item_id" x-model="adjustForm.item_id" required class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent bg-white">
                                <option value="">Select item...</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Quantity *</label>
                            <input type="number" name="quantity" x-model.number="adjustForm.quantity" required class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent" placeholder="Positive = add, Negative = remove">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Type *</label>
                            <select name="type" x-model="adjustForm.type" required class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent bg-white">
                                <option value="adjustment">General Adjustment</option>
                                <option value="damage">Damage</option>
                                <option value="return">Return</option>
                                <option value="opening_stock">Opening Stock</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-body mb-1">Notes</label>
                            <textarea name="notes" x-model="adjustForm.notes" rows="2" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent" placeholder="Reason for adjustment..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-card-bg rounded-b-xl">
                    <button type="button" @click="showAdjustModal = false" class="px-4 py-2 text-sm font-medium text-body bg-white border border-border rounded-lg hover:bg-card-bg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">Save Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
