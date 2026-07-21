@extends('layouts.app')

@section('title', $supplier->name ?? 'Supplier Details')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('suppliers.index') }}" class="hover:text-primary transition-colors">Suppliers</a>
<span class="mx-2">/</span>
<span class="text-heading">{{ $supplier->name ?? 'Details' }}</span>
@endsection

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-xl font-bold text-heading">Supplier Details</h1>
        <div class="flex gap-3">
            @can('edit_suppliers')
            <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-body bg-white rounded-lg hover:bg-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                </svg>
                Edit
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-5">
            {{-- Supplier Info Card --}}
            <div class="bg-white rounded-lg border border-border p-5">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-white text-xl font-bold">{{ substr($supplier->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-heading">{{ $supplier->name }}</h2>
                    @if($supplier->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success mt-1">Active</span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-body mt-1">Inactive</span>
                    @endif
                </div>
                <div class="space-y-3 border-t border-border pt-4">
                    @if($supplier->contact_person)
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                        </svg>
                        <span class="text-body">{{ $supplier->contact_person }}</span>
                    </div>
                    @endif
                    @if($supplier->phone)
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                        <span class="text-body">{{ $supplier->phone }}</span>
                    </div>
                    @endif
                    @if($supplier->email)
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        <span class="text-body">{{ $supplier->email }}</span>
                    </div>
                    @endif
                    @if($supplier->address)
                    <div class="flex items-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-muted mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        <span class="text-body">{{ $supplier->address }}{{ $supplier->city ? ', ' . $supplier->city : '' }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="bg-white rounded-lg border border-border p-5">
                <h3 class="text-sm font-medium text-muted mb-3">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Outstanding Balance</span>
                        <span class="font-medium {{ ($supplier->current_balance ?? 0) > 0 ? 'text-danger' : 'text-primary' }}">TZS {{ number_format($supplier->current_balance ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Credit Limit</span>
                        <span class="font-medium text-heading">TZS {{ number_format($supplier->credit_limit ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Payment Terms</span>
                        <span class="font-medium text-heading">{{ ucfirst(str_replace('net', 'Net ', $supplier->payment_terms ?? 'cash')) }}</span>
                    </div>
                    @if($supplier->tin_number)
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">TIN</span>
                        <span class="font-medium text-heading font-mono">{{ $supplier->tin_number }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Purchase History --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-border p-5">
                <h3 class="text-lg font-semibold text-heading mb-4">Purchase History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-muted border-b border-border">
                                <th class="pb-3 font-medium">Reference</th>
                                <th class="pb-3 font-medium">Date</th>
                                <th class="pb-3 font-medium text-right">Items</th>
                                <th class="pb-3 font-medium text-right">Total</th>
                                <th class="pb-3 font-medium text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($purchases ?? [] as $purchase)
                            <tr class="hover:bg-white">
                                <td class="py-3 font-medium text-heading">{{ $purchase->invoice_number }}</td>
                                <td class="py-3 text-body">{{ $purchase->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-right text-body">{{ $purchase->items_count ?? $purchase->items->count() }}</td>
                                <td class="py-3 text-right font-medium text-heading">TZS {{ number_format($purchase->total_amount) }}</td>
                                <td class="py-3 text-center">
                                    @if($purchase->status === 'received')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Received</span>
                                    @elseif($purchase->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-warning-light text-warning">Pending</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white text-body">{{ $purchase->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-muted">No purchase history</td>
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