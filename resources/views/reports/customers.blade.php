@extends('layouts.app')

@section('title', 'Customer Report')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Customer Report</h1>
            <p class="text-muted mt-1">Customer sales and outstanding balances</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.customers') }}?export=csv" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Export CSV
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Print
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-cyan-100 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Total Customers</p>
                    <p class="text-xl font-bold text-heading">{{ number_format($totalCustomers ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-light rounded-lg">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Active Customers</p>
                    <p class="text-xl font-bold text-success">{{ number_format($activeCustomers ?? 0) }}</p>
                </div>
            </div>
        </div>
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
                <div class="p-2 bg-danger-light rounded-lg">
                    <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted">Outstanding Balances</p>
                    <p class="text-xl font-bold text-danger">TZS {{ number_format($outstandingBalances ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-lg border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Customer Details</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Customer</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Phone</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Total Sales</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Total Paid</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Balance</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Last Purchase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers ?? [] as $customer)
                        <tr class="hover:bg-white">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-cyan-700">{{ substr($customer->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-heading">{{ $customer->name }}</p>
                                        <p class="text-xs text-muted">{{ $customer->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-body">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-medium text-heading">TZS {{ number_format($customer->total_sales ?? 0) }}</td>
                            <td class="px-6 py-4 text-right text-success">TZS {{ number_format($customer->total_paid ?? 0) }}</td>
                            <td class="px-6 py-4 text-right {{ ($customer->balance ?? 0) > 0 ? 'text-danger font-semibold' : 'text-body' }}">TZS {{ number_format($customer->balance ?? 0) }}</td>
                            <td class="px-6 py-4 text-body">{{ $customer->last_purchase?->format('d M Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-muted">
                                <svg class="w-12 h-12 mx-auto text-muted mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($customers) && $customers->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection