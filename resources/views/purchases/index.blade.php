@extends('layouts.app')

@section('title', 'Purchases')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-muted mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-accent">Dashboard</a>
                <svg class="h-4 w-4 mx-1 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-heading font-medium">Purchases</span>
            </nav>
            <h1 class="text-2xl font-bold text-heading">Purchases</h1>
        </div>
        @can('create_purchases')
        <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium text-sm transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Create Purchase
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice or supplier..." class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent focus:border-accent">
            </div>
            <div class="w-48">
                <select name="supplier_id" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <select name="status" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="w-40">
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent">
            </div>
            <div class="w-40">
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-accent">
            </div>
            <button type="submit" class="px-4 py-2 bg-control-bg hover:bg-control-bg text-body rounded-lg text-sm font-medium">Filter</button>
            @if(request()->hasAny(['search','supplier_id','status','from_date','to_date']))
                <a href="{{ route('purchases.index') }}" class="px-4 py-2 text-danger hover:text-danger text-sm font-medium">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-body">Invoice #</th>
                        <th class="px-4 py-3 text-left font-semibold text-body">Supplier</th>
                        <th class="px-4 py-3 text-left font-semibold text-body">Date</th>
                        <th class="px-4 py-3 text-center font-semibold text-body">Items</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Total</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Paid</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Due</th>
                        <th class="px-4 py-3 text-center font-semibold text-body">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-body">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($purchases ?? [] as $purchase)
                    <tr class="hover:bg-card-bg">
                        <td class="px-4 py-3">
                            <a href="{{ route('purchases.show', $purchase) }}" class="font-medium text-accent hover:text-accent">{{ $purchase->invoice_number }}</a>
                        </td>
                        <td class="px-4 py-3 text-body">{{ $purchase->supplier->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-body">{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center text-body">{{ $purchase->items->count() }}</td>
                        <td class="px-4 py-3 text-right font-medium text-heading">TZS {{ number_format($purchase->total_amount) }}</td>
                        <td class="px-4 py-3 text-right text-success">TZS {{ number_format($purchase->paid_amount) }}</td>
                        <td class="px-4 py-3 text-right {{ $purchase->due_amount > 0 ? 'text-danger font-medium' : 'text-body' }}">TZS {{ number_format($purchase->due_amount) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($purchase->status === 'received')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Received</span>
                            @elseif($purchase->status === 'cancelled')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">Cancelled</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-heading">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('purchases.show', $purchase) }}" class="p-1.5 text-muted hover:text-accent rounded-lg hover:bg-accent-light" title="View">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                @if($purchase->status === 'draft')
                                    <form method="POST" action="{{ route('purchases.receive', $purchase) }}" class="inline" x-data>
                                        @csrf
                                        <button type="submit" class="p-1.5 text-muted hover:text-success rounded-lg hover:bg-success-light" title="Receive" onclick="return confirm('Mark this purchase as received?')">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                @endif
                                @if(in_array($purchase->status, ['draft', 'received']))
                                <form method="POST" action="{{ route('purchases.destroy', $purchase) }}" class="inline" x-data>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-muted hover:text-danger rounded-lg hover:bg-danger-light" title="Delete" onclick="return confirm('Delete this purchase?')">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-muted">
                            <svg class="h-12 w-12 mx-auto text-muted mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            <p class="font-medium">No purchases found</p>
                            <a href="{{ route('purchases.create') }}" class="text-accent hover:text-accent text-sm mt-1 inline-block">Create your first purchase</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($purchases) && $purchases instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-4 py-3 border-t">
            {{ $purchases->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
