@extends('layouts.app')

@section('title', 'Sales - Mtokoma')

@section('header-title', 'Sales')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Sales</span>
@endsection

@section('content')
<div x-data="{
    search: '{{ request('search') }}',
    dateFrom: '{{ request('date_from') }}',
    dateTo: '{{ request('date_to') }}',
    customer: '{{ request('customer_id') }}',
    status: '{{ request('payment_status', 'all') }}',
    showVoidModal: false,
    voidSaleId: null,
    voidReason: '',
    voidForm: null,
    viewReceiptModal: false,
    receiptSale: null
}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-heading flex items-center gap-2">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>
                </svg>
                Sales
            </h2>
            <p class="text-sm text-muted mt-1">Manage all sales transactions</p>
        </div>
        <a href="{{ route('pos.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            New Sale (POS)
        </a>
    </div>

    <div class="bg-card-bg rounded-lg border border-border">
        <form method="GET" action="{{ route('sales.index') }}">
            <div class="p-4 border-b border-border grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" x-model="search" placeholder="Search by invoice, customer..." class="w-full pl-10 pr-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                    </div>
                </div>
                <div>
                    <input type="date" name="date_from" x-model="dateFrom" placeholder="From" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                </div>
                <div>
                    <input type="date" name="date_to" x-model="dateTo" placeholder="To" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                </div>
                <div>
                    <select name="customer_id" x-model="customer" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-control-bg">
                        <option value="">All Customers</option>
                        @foreach($customers ?? [] as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-4 border-b border-border flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-body">Payment Status:</label>
                    <select name="status" x-model="status" class="px-3 py-1.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-control-bg">
                        <option value="all">All</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 ml-auto">
                    <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('sales.index') }}" class="text-muted hover:text-body px-3 py-1.5 text-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-card-bg border-b border-border">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Invoice #</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Customer</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Date</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Items</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Subtotal</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Tax</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Total</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Payment</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($sales ?? [] as $sale)
                        <tr class="{{ $sale->is_voided ? 'bg-card-bg opacity-60' : 'hover:bg-card-bg' }} transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('sales.show', $sale) }}" class="text-accent font-medium text-sm hover:underline">{{ $sale->invoice_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-sm text-body">{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                            <td class="px-4 py-3 text-sm text-muted">{{ $sale->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-muted text-center">{{ $sale->items_count ?? $sale->items->count() }}</td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($sale->subtotal, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-muted text-right">TZS {{ number_format($sale->tax_amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-heading text-right">TZS {{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($sale->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Paid
                                    </span>
                                @elseif($sale->payment_status === 'partial')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-light text-amber-800">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 11.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3A1 1 0 0011 10.586V7z" clip-rule="evenodd"/></svg>
                                        Partial
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                        Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($sale->is_voided)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-body">
                                        Voided
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 rounded-lg text-muted hover:text-body hover:bg-control-bg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 mt-2 w-48 bg-control-bg rounded-lg border border-border py-1 z-20">
                                        <a href="{{ route('sales.show', $sale) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-body hover:bg-card-bg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            View Details
                                        </a>
                                        <button @click="$dispatch('view-receipt', { id: {{ $sale->id }} }); open = false"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-body hover:bg-card-bg text-left">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                            View Receipt
                                        </button>
                                        @if(!$sale->is_voided)
                                        <hr class="my-1 border-border">
                                        <button @click="voidSaleId = {{ $sale->id }}; voidReason = ''; showVoidModal = true; open = false"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-danger hover:bg-danger-light text-left">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                                            Void Sale
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-16 text-center">
                                <svg class="w-16 h-16 text-muted/50 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 0 1 2.828 0l1.414 1.42.879-.659M12 18V6m0 12h4.5m-4.5 0a2 2 0 0 1-1.732-1M12 18h4.5m-4.5 0a2 2 0 0 0 1.732-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <p class="text-muted font-medium">No sales found</p>
                                <p class="text-sm text-muted mt-1">Sales will appear here after creating transactions via POS.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($sales) && $sales->hasPages())
        <div class="px-4 py-3 border-t border-border">
            {{ $sales->links() }}
        </div>
        @endif
    </div>

    {{-- Void Confirmation Modal --}}
    <div x-show="showVoidModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="showVoidModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-card-bg rounded-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-danger-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-heading">Void Sale</h3>
                        <p class="text-sm text-muted">This action cannot be undone.</p>
                    </div>
                </div>
                <form :action="'{{ url('/sales') }}/' + voidSaleId + '/void'" method="POST" x-ref="voidForm">
                    @csrf
                    <div class="mb-4">
                        <label for="void_reason" class="block text-sm font-medium text-body mb-1">Reason for voiding <span class="text-danger">*</span></label>
                        <textarea name="void_reason" x-model="voidReason" rows="3" required placeholder="Enter the reason for voiding this sale..."
                                  class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-danger/50 focus:border-danger"></textarea>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-card-bg rounded-b-lg">
                <button @click="showVoidModal = false" class="px-4 py-2 text-sm font-medium text-body bg-control-bg border border-border rounded-lg hover:bg-card-bg transition-colors">
                    Cancel
                </button>
                <button @click="$refs.voidForm.submit()" :disabled="!voidReason.trim()" x-bind:class="voidReason.trim() ? 'bg-danger hover:bg-danger text-white' : 'bg-control-bg text-muted cursor-not-allowed'"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    Void Sale
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
