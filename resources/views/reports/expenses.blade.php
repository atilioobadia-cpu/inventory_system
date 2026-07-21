@extends('layouts.app')

@section('title', 'Expense Report')

@section('content')
<div class="space-y-6" x-data="expenseReport()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Expense Report</h1>
            <p class="text-muted mt-1">Track and analyze business expenses</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.expenses') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
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
    <div class="bg-white rounded-lg border border-border p-6">
        <form action="{{ route('reports.expenses') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-body mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date', now()->format('Y-m-d')) }}" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-body mb-1">Category</label>
                <select name="category_id" class="rounded-lg border-border focus:border-accent focus:ring-accent/20 text-sm min-w-[200px]">
                    <option value="">All Categories</option>
                    @foreach($expenseCategories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Total Expenses</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($totalExpenses ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-accent-light rounded-lg">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Average Expense</p>
                    <p class="text-xl font-bold text-heading">TZS {{ number_format($averageExpense ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Top Category</p>
                    <p class="text-xl font-bold text-heading">{{ $topCategory ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border border-border p-6">
            <h2 class="text-lg font-semibold text-heading mb-4">Expenses by Category</h2>
            <div class="h-72">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-6">
            <h2 class="text-lg font-semibold text-heading mb-4">Monthly Trend</h2>
            <div class="h-72">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Expense Table -->
    <div class="bg-white rounded-lg border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Expense Details</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Date</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Category</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Amount</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Payment Method</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenses ?? [] as $expense)
                        <tr class="hover:bg-card-bg">
                            <td class="px-6 py-4 text-body">{{ $expense->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-control-bg text-body rounded-full text-xs font-medium">{{ $expense->category->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-danger">TZS {{ number_format($expense->amount) }}</td>
                            <td class="px-6 py-4 text-body">{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                            <td class="px-6 py-4 text-body max-w-xs truncate">{{ $expense->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-muted">
                                <svg class="w-12 h-12 mx-auto text-muted mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                No expenses found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($expenses) && $expenses->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $expenses->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function expenseReport() {
    return {
        init() {
            const barCtx = document.getElementById('categoryBarChart');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($categoryLabels ?? []) !!},
                        datasets: [{
                            label: 'Amount (TZS)',
                            data: {!! json_encode($categoryAmounts ?? []) !!},
                            backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#06B6D4'],
                            borderRadius: 6,
                            barThickness: 32
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { callback: (v) => 'TZS ' + v.toLocaleString() } }
                        }
                    }
                });
            }
            const trendCtx = document.getElementById('monthlyTrendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthlyLabels ?? []) !!},
                        datasets: [{
                            label: 'Expenses (TZS)',
                            data: {!! json_encode($monthlyAmounts ?? []) !!},
                            borderColor: '#F59E0B',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#F59E0B',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
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