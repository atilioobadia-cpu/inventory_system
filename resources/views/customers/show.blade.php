@extends('layouts.app')

@section('title', $customer->name ?? 'Customer Details')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('customers.index') }}" class="hover:text-accent transition-colors">Customers</a>
<span class="mx-2">/</span>
<span class="text-heading">{{ $customer->name ?? 'Details' }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-xl font-bold text-heading">Customer Details</h1>
        <div class="flex gap-3">
            @can('edit_customers')
            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                </svg>
                Edit
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            {{-- Customer Info Card --}}
            <div class="bg-white rounded-lg border border-border p-6">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-white text-xl font-bold">{{ substr($customer->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-heading">{{ $customer->name }}</h2>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(($customer->customer_type ?? 'individual') === 'wholesale') bg-purple-50 text-purple-700
                            @elseif(($customer->customer_type ?? 'individual') === 'business') bg-accent-light text-accent
                            @else bg-control-bg text-body @endif">
                            {{ ucfirst($customer->customer_type ?? 'individual') }}
                        </span>
                        @if($customer->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Active</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-body">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="space-y-3 border-t border-border pt-4">
                    @if($customer->phone)
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                        <span class="text-body">{{ $customer->phone }}</span>
                    </div>
                    @endif
                    @if($customer->email)
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        <span class="text-body">{{ $customer->email }}</span>
                    </div>
                    @endif
                    @if($customer->address)
                    <div class="flex items-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        <span class="text-body">{{ $customer->address }}{{ $customer->city ? ', ' . $customer->city : '' }}</span>
                    </div>
                    @endif
                    @if($customer->tin_number)
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                        </svg>
                        <span class="text-body font-mono text-xs">TIN: {{ $customer->tin_number }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="bg-white rounded-lg border border-border p-6">
                <h3 class="text-sm font-medium text-muted mb-3">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Outstanding Balance</span>
                        <span class="font-medium {{ ($customer->current_balance ?? 0) > 0 ? 'text-danger' : 'text-accent' }}">TZS {{ number_format($customer->current_balance ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Credit Limit</span>
                        <span class="font-medium text-heading">TZS {{ number_format($customer->credit_limit ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Total Purchases</span>
                        <span class="font-medium text-heading">TZS {{ number_format($totalPurchases ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales History --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-heading mb-4">Sales History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-muted border-b border-border">
                                <th class="pb-3 font-medium">Invoice #</th>
                                <th class="pb-3 font-medium">Date</th>
                                <th class="pb-3 font-medium text-right">Items</th>
                                <th class="pb-3 font-medium text-right">Total</th>
                                <th class="pb-3 font-medium text-right">Paid</th>
                                <th class="pb-3 font-medium text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($sales ?? [] as $sale)
                            <tr class="hover:bg-card-bg">
                                <td class="py-3 font-medium text-heading">{{ $sale->invoice_number }}</td>
                                <td class="py-3 text-body">{{ $sale->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-right text-body">{{ $sale->items_count ?? $sale->items->count() }}</td>
                                <td class="py-3 text-right font-medium text-heading">TZS {{ number_format($sale->total_amount) }}</td>
                                <td class="py-3 text-right text-body">TZS {{ number_format($sale->paid_amount ?? 0) }}</td>
                                <td class="py-3 text-center">
                                    @if($sale->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Paid</span>
                                    @elseif($sale->payment_status === 'partial')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-warning-light text-warning">Partial</span>
                                    @elseif($sale->payment_status === 'unpaid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">Unpaid</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-control-bg text-body">{{ $sale->payment_status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-muted">No sales history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection