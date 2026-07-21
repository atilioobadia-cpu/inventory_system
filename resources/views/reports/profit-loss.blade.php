@extends('layouts.app')

@section('title', 'Profit & Loss Report')

@section('content')
<div class="space-y-6" x-data="profitLossReport()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Profit & Loss Report</h1>
            <p class="text-muted mt-1">Revenue, costs and profitability analysis</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.profit-loss') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Export CSV
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Print
            </button>
        </div>
    </div>

    <!-- Date Range -->
    <div class="bg-white rounded-lg border border-border p-6">
        <form action="{{ route('reports.profit-loss') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-body mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date', now()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm">
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Generate Report</button>
        </form>
    </div>

    <!-- Profit & Loss Statement -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg border border-border overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-heading">Profit & Loss Statement</h2>
                <p class="text-sm text-muted">{{ request('from_date', now()->startOfMonth()->format('d M Y')) }} - {{ request('to_date', now()->format('d M Y')) }}</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Revenue Section -->
                <div>
                    <h3 class="text-sm font-semibold text-muted uppercase tracking-wider mb-3">Revenue</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-body">Total Sales</span>
                            <span class="font-medium text-heading">TZS {{ number_format($totalSales ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-body">Less: Returns & Adjustments</span>
                            <span class="font-medium text-danger">-TZS {{ number_format($returns ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-t border-border text-sm">
                            <span class="font-semibold text-heading">Net Revenue</span>
                            <span class="font-bold text-heading">TZS {{ number_format($netRevenue ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- COGS Section -->
                <div>
                    <h3 class="text-sm font-semibold text-muted uppercase tracking-wider mb-3">Cost of Goods Sold</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-body">Opening Stock</span>
                            <span class="font-medium text-heading">TZS {{ number_format($openingStock ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-body">Add: Purchases</span>
                            <span class="font-medium text-heading">TZS {{ number_format($purchases ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-body">Less: Closing Stock</span>
                            <span class="font-medium text-danger">-TZS {{ number_format($closingStock ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-t border-border text-sm">
                            <span class="font-semibold text-heading">Total COGS</span>
                            <span class="font-bold text-heading">TZS {{ number_format($totalCogs ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Gross Profit -->
                <div class="bg-success-light rounded-lg p-4">
                    <div class="flex justify-between">
                        <span class="font-semibold text-success">Gross Profit</span>
                        <span class="font-bold text-lg {{ ($grossProfit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">TZS {{ number_format($grossProfit ?? 0) }}</span>
                    </div>
                    @if(($netRevenue ?? 0) > 0)
                        <p class="text-sm text-success mt-1">Gross Margin: {{ number_format(($grossProfit ?? 0) / ($netRevenue ?? 1) * 100, 1) }}%</p>
                    @endif
                </div>

                <!-- Expenses Section -->
                <div>
                    <h3 class="text-sm font-semibold text-muted uppercase tracking-wider mb-3">Expenses</h3>
                    <div class="space-y-2">
                        @foreach($expenseBreakdown ?? [] as $expense)
                            <div class="flex justify-between py-2 text-sm">
                                <span class="text-body">{{ $expense['name'] }}</span>
                                <span class="font-medium text-heading">TZS {{ number_format($expense['amount']) }}</span>
                            </div>
                        @endforeach
                        <div class="flex justify-between py-3 border-t border-border text-sm">
                            <span class="font-semibold text-heading">Total Expenses</span>
                            <span class="font-bold text-danger">TZS {{ number_format($totalExpenses ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="bg-primary rounded-lg p-4">
                    <div class="flex justify-between">
                        <span class="font-semibold text-white">Net Profit</span>
                        <span class="font-bold text-xl {{ ($netProfit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">TZS {{ number_format($netProfit ?? 0) }}</span>
                    </div>
                    @if(($netRevenue ?? 0) > 0)
                        <p class="text-sm text-muted mt-1">Net Margin: {{ number_format(($netProfit ?? 0) / ($netRevenue ?? 1) * 100, 1) }}%</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Comparison Chart -->
        <div class="bg-white rounded-lg border border-border p-6">
            <h2 class="text-lg font-semibold text-heading mb-4">Monthly Comparison</h2>
            <div class="h-96">
                <canvas id="monthlyComparisonChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function profitLossReport() {
    return {
        init() {
            const ctx = document.getElementById('monthlyComparisonChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($monthlyLabels ?? []) !!},
                        datasets: [
                            {
                                label: 'Revenue',
                                data: {!! json_encode($monthlyRevenue ?? []) !!},
                                backgroundColor: '#10B981',
                                borderRadius: 4
                            },
                            {
                                label: 'COGS',
                                data: {!! json_encode($monthlyCogs ?? []) !!},
                                backgroundColor: '#F59E0B',
                                borderRadius: 4
                            },
                            {
                                label: 'Expenses',
                                data: {!! json_encode($monthlyExpenses ?? []) !!},
                                backgroundColor: '#EF4444',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } },
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: (v) => 'TZS ' + v.toLocaleString() } }
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