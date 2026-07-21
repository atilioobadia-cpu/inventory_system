@extends('layouts.app')

@section('title', 'Stock Movements - Mtokoma')

@section('header-title', 'Stock Movements')

@section('breadcrumbs')
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('stock.index') }}" class="hover:text-tz-green transition-colors">Stock</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-700 font-medium">Movements</span>
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
            <h2 class="text-2xl font-bold text-gray-900">Stock Movements</h2>
            <p class="text-sm text-gray-500 mt-1">Track all inventory movements and adjustments</p>
        </div>
        <button @click="showAdjustModal = true" class="inline-flex items-center gap-2 bg-tz-green text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-tz-green-dark transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Adjust Stock
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('stock.movements') }}">
            <div class="p-4 border-b border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" x-model="search" placeholder="Search by item name..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green">
                    </div>
                </div>
                <div>
                    <input type="date" name="date_from" x-model="dateFrom" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green">
                </div>
                <div>
                    <input type="date" name="date_to" x-model="dateTo" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green">
                </div>
                <div>
                    <select name="type" x-model="type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green bg-white">
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
            <div class="p-4 border-b border-gray-100 flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600">Direction:</label>
                    <select name="direction" x-model="direction" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green bg-white">
                        <option value="">All</option>
                        <option value="in">In (Stock Received)</option>
                        <option value="out">Out (Stock Sent)</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 ml-auto">
                    <button type="submit" class="bg-tz-green text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-tz-green-dark transition-colors">Filter</button>
                    <a href="{{ route('stock.movements') }}" class="text-gray-500 hover:text-gray-700 px-3 py-1.5 text-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Direction</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Cost</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bal. Before</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bal. After</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $typeStyles = [
                            'purchase' => ['bg-blue-100 text-blue-800', 'Purchase'],
                            'sale' => ['bg-green-100 text-green-800', 'Sale'],
                            'adjustment' => ['bg-amber-100 text-amber-800', 'Adjustment'],
                            'opening_stock' => ['bg-gray-100 text-gray-800', 'Opening'],
                            'return' => ['bg-purple-100 text-purple-800', 'Return'],
                            'damage' => ['bg-red-100 text-red-800', 'Damage'],
                            'transfer' => ['bg-cyan-100 text-cyan-800', 'Transfer'],
                        ];
                    @endphp
                    @forelse($movements ?? [] as $m)
                        @php
                            $style = $typeStyles[$m->type] ?? ['bg-gray-100 text-gray-800', ucfirst($m->type)];
                            $isIn = $m->direction === 'in';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $m->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $m->item->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $style[0] }}">{{ $style[1] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isIn)
                                    <span class="inline-flex items-center gap-1 text-green-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75"/></svg>
                                        <span class="text-xs font-medium">In</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75"/></svg>
                                        <span class="text-xs font-medium">Out</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-center {{ $isIn ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isIn ? '+' : '-' }}{{ $m->quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">TZS {{ number_format($m->unit_cost ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-right">{{ $m->balance_before ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">{{ $m->balance_after ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                @if($m->reference)
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $m->reference }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $m->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 max-w-[150px] truncate" title="{{ $m->notes ?? '' }}">{{ $m->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">No stock movements found</p>
                                <p class="text-sm text-gray-400 mt-1">Movements will appear here as inventory changes.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($movements) && $movements->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
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
             class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <form method="POST" action="{{ route('stock.adjust') }}">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Adjustment</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Item *</label>
                            <select name="item_id" x-model="adjustForm.item_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green bg-white">
                                <option value="">Select item...</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                            <input type="number" name="quantity" x-model.number="adjustForm.quantity" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green" placeholder="Positive = add, Negative = remove">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                            <select name="type" x-model="adjustForm.type" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green bg-white">
                                <option value="adjustment">General Adjustment</option>
                                <option value="damage">Damage</option>
                                <option value="return">Return</option>
                                <option value="opening_stock">Opening Stock</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" x-model="adjustForm.notes" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/50 focus:border-tz-green" placeholder="Reason for adjustment..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-xl">
                    <button type="button" @click="showAdjustModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-tz-green text-white rounded-lg hover:bg-tz-green-dark transition-colors">Save Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
