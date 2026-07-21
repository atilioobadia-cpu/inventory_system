@extends('layouts.app')

@section('title', 'Purchase Report')

@section('content')
<div class="space-y-6" x-data="purchaseReport()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-heading">Purchase Report</h1>
            <p class="text-muted mt-1">Track purchases and supplier spending</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.purchases') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
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
    <div class="bg-white rounded-xl border border-border p-6">
        <form action="{{ route('reports.purchases') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-body mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date', now()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">Supplier</label>
                <select name="supplier_id" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm min-w-[200px]">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Filter</button>
            <a href="{{ route('reports.purchases') }}" class="px-4 py-2 bg-control-bg text-body rounded-lg hover:bg-control-bg transition-colors text-sm font-medium">Reset</a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Total Purchases</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($totalPurchases ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-light rounded-lg">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Items Received</p>
                    <p class="text-xl font-bold text-heading">{{ number_format($itemsReceived ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-warning-light rounded-lg">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Average Purchase</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($averagePurchase ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">VAT Paid</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($vatPaid ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl border border-border p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-heading">Purchase Trend</h2>
            <div class="flex items-center gap-2">
                <button @click="chartPeriod = 'daily'" :class="chartPeriod === 'daily' ? 'bg-primary text-white' : 'bg-control-bg text-body'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">Daily</button>
                <button @click="chartPeriod = 'weekly'" :class="chartPeriod === 'weekly' ? 'bg-primary text-white' : 'bg-control-bg text-body'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">Weekly</button>
                <button @click="chartPeriod = 'monthly'" :class="chartPeriod === 'monthly' ? 'bg-primary text-white' : 'bg-control-bg text-body'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">Monthly</button>
            </div>
        </div>
        <div class="h-80">
            <canvas id="purchaseChart"></canvas>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-xl border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Purchase Details</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Date</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">PO Number</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Supplier</th>
                        <th class="text-center px-6 py-3 font-medium text-muted">Items</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Subtotal</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">VAT</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Total</th>
                        <th class="text-center px-6 py-3 font-medium text-muted">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($purchases ?? [] as $purchase)
                        <tr class="hover:bg-card-bg">
                            <td class="px-6 py-4 text-body">{{ $purchase->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-medium text-accent">{{ $purchase->po_number }}</td>
                            <td class="px-6 py-4 text-heading">{{ $purchase->supplier->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center text-body">{{ $purchase->items_count }}</td>
                            <td class="px-6 py-4 text-right text-body">TZS {{ number_format($purchase->subtotal) }}</td>
                            <td class="px-6 py-4 text-right text-body">TZS {{ number_format($purchase->vat_amount) }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-heading">TZS {{ number_format($purchase->total) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($purchase->status === 'received')
                                    <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Received</span>
                                @elseif($purchase->status === 'pending')
                                    <span class="px-2 py-1 bg-warning-light text-warning rounded-full text-xs font-medium">Pending</span>
                                @else
                                    <span class="px-2 py-1 bg-danger-light text-danger rounded-full text-xs font-medium">{{ ucfirst($purchase->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-muted">
                                <svg class="w-12 h-12 mx-auto text-muted mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                No purchases found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($purchases) && $purchases->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $purchases->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function purchaseReport() {
    return {
        chartPeriod: 'daily',
        init() {
            const ctx = document.getElementById('purchaseChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!},
                        datasets: [{
                            label: 'Purchases (TZS)',
                            data: {!! json_encode($chartData ?? []) !!},
                            borderColor: '#6366F1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#6366F1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: (ctx) => 'TZS ' + ctx.parsed.y.toLocaleString() } }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: (v) => 'TZS ' + v.toLocaleString() }
                            }
                        }
                    }
                });
            }
        }
    };
}
</script>
@endpush
@endsection