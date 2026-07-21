@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumbs')
<span class="mx-2">/</span>
<span class="text-heading">Dashboard</span>
@endsection

@section('content')
<div class="space-y-4">
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        {{-- Total Items --}}
        <div class="bg-card-bg rounded-lg border border-border p-4 flex items-center gap-4 card-hover">
            <div class="w-10 h-10 bg-accent-light rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                    <path d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-muted">Total Items</p>
                <p class="text-xl font-bold text-heading">{{ number_format($totalItems ?? 0) }}</p>
            </div>
        </div>

        {{-- Stock Value --}}
        <div class="bg-card-bg rounded-lg border border-border p-4 flex items-center gap-4 card-hover">
            <div class="w-10 h-10 bg-accent-light rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 0 1 2.828 0l1.414 1.42.879-.659M12 18V6m0 12H7.5m4.5 0h4.5"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-muted">Stock Value</p>
                <p class="text-xl font-bold text-heading">TZS {{ number_format($stockValue ?? 0) }}</p>
            </div>
        </div>

        {{-- Today's Sales --}}
        <div class="bg-card-bg rounded-lg border border-border p-4 flex items-center gap-4 card-hover">
            <div class="w-10 h-10 bg-success-light rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-muted">Today's Sales</p>
                <p class="text-xl font-bold text-heading">TZS {{ number_format($todaySalesAmount ?? 0) }}</p>
            </div>
        </div>

        {{-- Low Stock Items --}}
        <div class="bg-card-bg rounded-lg border border-border p-4 flex items-center gap-4 card-hover">
            <div class="w-10 h-10 bg-warning-light rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-warning" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.004ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-muted">Low Stock Items</p>
                <p class="text-xl font-bold text-warning">{{ $lowStockCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Charts and Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Monthly Sales Chart --}}
        <div class="lg:col-span-2 bg-white rounded-lg border border-border p-5">
            <h3 class="text-lg font-semibold text-heading mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                Monthly Sales
            </h3>
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-card-bg rounded-lg border border-border p-5">
            <h3 class="text-lg font-semibold text-heading mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Recent Activity
            </h3>
            <div class="space-y-4 max-h-[300px] overflow-y-auto">
                @forelse($recentActivities ?? [] as $activity)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-control-bg flex items-center justify-center flex-shrink-0 mt-0.5">
                        @if(str_contains($activity->action ?? '', 'sale'))
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 0 1 2.828 0l1.414 1.42.879-.659M12 18V6m0 12H7.5m4.5 0h4.5"/>
                        </svg>
                        @elseif(str_contains($activity->action ?? '', 'purchase'))
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.183A1.125 1.125 0 0 0 18.056 3H5.106"/>
                        </svg>
                        @elseif(str_contains($activity->action ?? '', 'stock'))
                        <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                        </svg>
                        @else
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-heading">{{ $activity->description }}</p>
                        <p class="text-xs text-muted mt-0.5">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-muted text-center py-8">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Top Selling Items --}}
        <div class="bg-card-bg rounded-lg border border-border p-5">
            <h3 class="text-lg font-semibold text-heading mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                </svg>
                Top Selling Items
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-muted border-b border-border">
                            <th class="pb-2 font-medium">Item</th>
                            <th class="pb-2 font-medium text-right">Qty Sold</th>
                            <th class="pb-2 font-medium text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($topSellingItems ?? [] as $item)
                        <tr>
                            <td class="py-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-control-bg rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="" class="w-8 h-8 rounded-lg object-cover">
                                        @else
                                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                        </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-heading">{{ $item->name }}</p>
                                        <p class="text-xs text-muted">{{ $item->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 text-right text-body">{{ $item->total_sold }}</td>
                            <td class="py-2 text-right font-medium text-heading">TZS {{ number_format($item->total_revenue) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-muted">No sales data yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="bg-card-bg rounded-lg border border-border p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-heading flex items-center gap-2">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008"/>
                    </svg>
                    Low Stock Alerts
                </h3>
                <a href="{{ route('items.index', ['status' => 'low_stock']) }}" class="text-sm text-accent hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-muted border-b border-border">
                            <th class="pb-2 font-medium">Item</th>
                            <th class="pb-2 font-medium text-right">Current Stock</th>
                            <th class="pb-2 font-medium text-right">Reorder Point</th>
                            <th class="pb-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($lowStockItems ?? [] as $item)
                        <tr>
                            <td class="py-2">
                                <a href="{{ route('items.show', $item) }}" class="font-medium text-heading hover:text-accent">{{ $item->name }}</a>
                            </td>
                            <td class="py-2 text-right text-body">{{ $item->current_stock }}</td>
                            <td class="py-2 text-right text-body">{{ $item->reorder_point }}</td>
                            <td class="py-2">
                                @if($item->current_stock == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">Out of Stock</span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-warning-light text-warning">Low Stock</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-muted">All items are well stocked</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']) !!},
                datasets: [{
                    label: 'Sales (TZS)',
                    data: {!! json_encode($monthlySalesData ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!},
                    backgroundColor: '#5e64ff',
                    borderRadius: 6,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'TZS ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + (value >= 1000000 ? (value/1000000).toFixed(1) + 'M' : value >= 1000 ? (value/1000).toFixed(0) + 'K' : value);
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
