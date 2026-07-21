@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="space-y-4" x-data="salesReport()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Sales Report</h1>
            <p class="text-muted mt-1">Analyze your sales performance</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('reports.export') }}" method="POST" class="inline-flex">
                @csrf
                <input type="hidden" name="type" value="sales">
                <input type="hidden" name="from" value="{{ $from }}">
                <input type="hidden" name="to" value="{{ $to }}">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Print
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-border p-5">
        <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-body mb-1">From Date</label>
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-primary focus:ring-primary/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">To Date</label>
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-primary focus:ring-primary/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">Customer</label>
                <select name="customer_id" class="rounded-lg border-border focus:border-primary focus:ring-primary/20 text-sm min-w-[200px]">
                    <option value="">All Customers</option>
                    @foreach($customers ?? [] as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                Filter
            </button>
            <a href="{{ route('reports.sales') }}" class="px-4 py-2 bg-white text-body rounded-lg hover:bg-white transition-colors text-sm font-medium">Reset</a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Total Sales</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($totalSales ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-light rounded-lg">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Total Items Sold</p>
                    <p class="text-xl font-bold text-heading">{{ number_format($totalItemsSold ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-warning-light rounded-lg">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Average Sale Value</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($averageSale ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">VAT Collected</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($vatCollected ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-lg border border-border p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-heading">Sales Trend</h2>
            <div class="flex items-center gap-2">
                <button @click="chartPeriod = 'daily'" :class="chartPeriod === 'daily' ? 'bg-primary text-white' : 'bg-white text-body'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">Daily</button>
                <button @click="chartPeriod = 'weekly'" :class="chartPeriod === 'weekly' ? 'bg-primary text-white' : 'bg-white text-body'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">Weekly</button>
                <button @click="chartPeriod = 'monthly'" :class="chartPeriod === 'monthly' ? 'bg-primary text-white' : 'bg-white text-body'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">Monthly</button>
            </div>
        </div>
        <div class="h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-lg border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Sales Details</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Date</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Invoice #</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Customer</th>
                        <th class="text-center px-6 py-3 font-medium text-muted">Items</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Subtotal</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">VAT</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Discount</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Total</th>
                        <th class="text-center px-6 py-3 font-medium text-muted">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales ?? [] as $sale)
                        <tr class="hover:bg-white">
                            <td class="px-6 py-4 text-body">{{ $sale->sale_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-medium text-primary">{{ $sale->invoice_number }}</td>
                            <td class="px-6 py-4 text-heading">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                            <td class="px-6 py-4 text-center text-body">{{ $sale->items_count }}</td>
                            <td class="px-6 py-4 text-right text-body">TZS {{ number_format($sale->total_before_tax) }}</td>
                            <td class="px-6 py-4 text-right text-body">{{ $sale->vat_enabled ? 'TZS ' . number_format($sale->vat_amount) : '—' }}</td>
                            <td class="px-6 py-4 text-right text-danger">-TZS {{ number_format($sale->discount_amount) }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-heading">TZS {{ number_format($sale->total_after_tax) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($sale->payment_status === 'paid')
                                    <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Completed</span>
                                @elseif($sale->payment_status === 'partial')
                                    <span class="px-2 py-1 bg-warning-light text-warning rounded-full text-xs font-medium">Pending</span>
                                @else
                                    <span class="px-2 py-1 bg-danger-light text-danger rounded-full text-xs font-medium">{{ ucfirst($sale->payment_status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-muted">
                                <svg class="w-12 h-12 mx-auto text-muted mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                No sales found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($sales) && $sales->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $sales->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function salesReport() {
    return {
        chartPeriod: 'daily',
        init() {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!},
                        datasets: [{
                            label: 'Sales (TZS)',
                            data: {!! json_encode($chartData ?? []) !!},
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3B82F6',
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
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => 'TZS ' + ctx.parsed.y.toLocaleString()
                                }
                            }
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