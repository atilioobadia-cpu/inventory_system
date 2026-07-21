@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="space-y-4" x-data="inventoryReport()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Inventory Report</h1>
            <p class="text-muted mt-1">Stock levels, valuations and category breakdown</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.inventory') }}?export=csv" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Export CSV
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Print
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-border p-5">
        <form action="{{ route('reports.inventory') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-body mb-1">Category</label>
                <select name="category_id" class="rounded-lg border-border focus:border-primary focus:ring-primary/20 text-sm min-w-[200px]">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">Status</label>
                <select name="status" class="rounded-lg border-border focus:border-primary focus:ring-primary/20 text-sm">
                    <option value="">All</option>
                    <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="text-center">
                <div class="p-2 bg-teal-100 rounded-lg inline-flex mb-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                </div>
                <p class="text-sm text-muted">Total Items</p>
                <p class="text-xl font-bold text-heading">{{ number_format($totalItems ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="text-center">
                <div class="p-2 bg-gray-100 rounded-lg inline-flex mb-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <p class="text-sm text-muted">Stock Value (Cost)</p>
                <p class="text-xl font-bold text-heading">TZS {{ number_format($totalStockValue ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="text-center">
                <div class="p-2 bg-success-light rounded-lg inline-flex mb-2">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm text-muted">Retail Value</p>
                <p class="text-xl font-bold text-heading">TZS {{ number_format($totalRetailValue ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="text-center">
                <div class="p-2 bg-warning-light rounded-lg inline-flex mb-2">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <p class="text-sm text-muted">Low Stock</p>
                <p class="text-xl font-bold text-warning">{{ $lowStockItems ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="text-center">
                <div class="p-2 bg-danger-light rounded-lg inline-flex mb-2">
                    <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <p class="text-sm text-muted">Out of Stock</p>
                <p class="text-xl font-bold text-danger">{{ $outOfStockItems ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Inventory Table -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-border overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-heading">Stock Details</h2>
            </div>
            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white sticky top-0">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-muted">Item</th>
                            <th class="text-left px-4 py-3 font-medium text-muted">SKU</th>
                            <th class="text-left px-4 py-3 font-medium text-muted">Category</th>
                            <th class="text-center px-4 py-3 font-medium text-muted">Qty</th>
                            <th class="text-right px-4 py-3 font-medium text-muted">Cost</th>
                            <th class="text-right px-4 py-3 font-medium text-muted">Selling</th>
                            <th class="text-right px-4 py-3 font-medium text-muted">Value</th>
                            <th class="text-center px-4 py-3 font-medium text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items ?? [] as $item)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-3 font-medium text-heading">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-muted font-mono text-xs">{{ $item->sku }}</td>
                                <td class="px-4 py-3 text-body">{{ $item->category->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center font-medium {{ $item->quantity <= $item->low_stock_threshold ? ($item->quantity == 0 ? 'text-danger' : 'text-warning') : 'text-heading' }}">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right text-body">TZS {{ number_format($item->cost_price) }}</td>
                                <td class="px-4 py-3 text-right text-body">TZS {{ number_format($item->selling_price) }}</td>
                                <td class="px-4 py-3 text-right font-medium text-heading">TZS {{ number_format($item->quantity * $item->cost_price) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($item->quantity == 0)
                                        <span class="px-2 py-1 bg-danger-light text-danger rounded-full text-xs font-medium">Out of Stock</span>
                                    @elseif($item->quantity <= $item->low_stock_threshold)
                                        <span class="px-2 py-1 bg-warning-light text-warning rounded-full text-xs font-medium">Low Stock</span>
                                    @else
                                        <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-muted">No inventory items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($items) && $items->hasPages())
                <div class="px-6 py-4 border-t border-border">
                    {{ $items->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Category Breakdown -->
        <div class="bg-white rounded-lg border border-border p-5">
            <h2 class="text-lg font-semibold text-heading mb-4">Category Breakdown</h2>
            <div class="h-72">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @foreach($categoryBreakdown ?? [] as $index => $cat)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#06B6D4','#84CC16'][$index % 8] }}"></div>
                            <span class="text-body">{{ $cat['name'] }}</span>
                        </div>
                        <span class="font-medium text-heading">{{ $cat['count'] }} items</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function inventoryReport() {
    return {
        init() {
            const ctx = document.getElementById('categoryChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(collect($categoryBreakdown ?? [])->pluck('name')->toArray()) !!},
                        datasets: [{
                            data: {!! json_encode(collect($categoryBreakdown ?? [])->pluck('count')->toArray()) !!},
                            backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#06B6D4','#84CC16'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: { legend: { display: false } }
                    }
                });
            }
        }
    };
}
</script>
@endpush
@endsection