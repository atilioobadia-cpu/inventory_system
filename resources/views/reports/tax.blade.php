@extends('layouts.app')

@section('title', 'Tax Report')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-heading">Tax Report</h1>
            <p class="text-muted mt-1">VAT collected and paid summaries</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.tax') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
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
    <div class="bg-white rounded-xl border border-border p-6">
        <form action="{{ route('reports.tax') }}" method="GET" class="flex flex-wrap items-end gap-4">
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-light rounded-lg">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Sales VAT Collected</p>
                    <p class="text-xl font-bold text-success">TZS {{ number_format($salesVat ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-danger-light rounded-lg">
                    <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Purchase VAT Paid</p>
                    <p class="text-xl font-bold text-danger">TZS {{ number_format($purchaseVat ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-accent-light rounded-lg">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Net VAT Payable</p>
                    <p class="text-xl font-bold {{ ($netVat ?? 0) >= 0 ? 'text-accent' : 'text-success' }}">TZS {{ number_format($netVat ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly VAT Breakdown -->
    <div class="bg-white rounded-xl border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Monthly VAT Breakdown</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Month</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Sales VAT</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Purchase VAT</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Net VAT</th>
                        <th class="text-center px-6 py-3 font-medium text-muted">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($monthlyVat ?? [] as $month)
                        <tr class="hover:bg-card-bg">
                            <td class="px-6 py-4 font-medium text-heading">{{ $month['label'] }}</td>
                            <td class="px-6 py-4 text-right text-success">TZS {{ number_format($month['sales_vat']) }}</td>
                            <td class="px-6 py-4 text-right text-danger">TZS {{ number_format($month['purchase_vat']) }}</td>
                            <td class="px-6 py-4 text-right font-semibold {{ $month['net'] >= 0 ? 'text-accent' : 'text-success' }}">TZS {{ number_format($month['net']) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($month['net'] > 0)
                                    <span class="px-2 py-1 bg-accent-light text-accent rounded-full text-xs font-medium">Payable</span>
                                @elseif($month['net'] < 0)
                                    <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Refundable</span>
                                @else
                                    <span class="px-2 py-1 bg-control-bg text-body rounded-full text-xs font-medium">Nil</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-muted">No VAT data found for the selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(isset($monthlyVat) && count($monthlyVat) > 0)
                    <tfoot class="bg-card-bg font-semibold">
                        <tr>
                            <td class="px-6 py-3 text-heading">Total</td>
                            <td class="px-6 py-3 text-right text-success">TZS {{ number_format(collect($monthlyVat)->sum('sales_vat')) }}</td>
                            <td class="px-6 py-3 text-right text-danger">TZS {{ number_format(collect($monthlyVat)->sum('purchase_vat')) }}</td>
                            <td class="px-6 py-3 text-right text-accent">TZS {{ number_format(collect($monthlyVat)->sum('net')) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- VAT by Rate -->
    <div class="bg-white rounded-xl border border-border p-6">
        <h2 class="text-lg font-semibold text-heading mb-4">VAT Breakdown by Rate</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">VAT Rate</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Taxable Sales</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Sales VAT</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Taxable Purchases</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Purchase VAT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vatByRate ?? [] as $rate)
                        <tr class="hover:bg-card-bg">
                            <td class="px-6 py-4 font-medium text-heading">{{ $rate['rate'] }}%</td>
                            <td class="px-6 py-4 text-right text-body">TZS {{ number_format($rate['taxable_sales']) }}</td>
                            <td class="px-6 py-4 text-right text-success">TZS {{ number_format($rate['sales_vat']) }}</td>
                            <td class="px-6 py-4 text-right text-body">TZS {{ number_format($rate['taxable_purchases']) }}</td>
                            <td class="px-6 py-4 text-right text-danger">TZS {{ number_format($rate['purchase_vat']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-muted">No rate data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection