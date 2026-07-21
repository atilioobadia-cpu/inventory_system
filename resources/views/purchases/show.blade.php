@extends('layouts.app')

@section('title', 'Purchase Details - ' . $purchase->invoice_number)

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center text-sm text-muted mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-accent">Dashboard</a>
                <svg class="h-4 w-4 mx-1 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('purchases.index') }}" class="hover:text-accent">Purchases</a>
                <svg class="h-4 w-4 mx-1 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-heading font-medium">{{ $purchase->invoice_number }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-heading">{{ $purchase->invoice_number }}</h1>
                @if($purchase->status === 'received')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Received</span>
                @elseif($purchase->status === 'cancelled')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">Cancelled</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-heading">Draft</span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('receive_purchases')
            @if($purchase->status === 'draft')
            <form method="POST" action="{{ route('purchases.receive', $purchase) }}" x-data onsubmit="return confirm('Are you sure you want to mark this purchase as received?')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Receive
                </button>
            </form>
            @endif
            @endcan

            @can('edit_purchases')
            @if($purchase->status === 'draft')
            <a href="{{ route('purchases.edit', $purchase) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-border hover:bg-card-bg text-body rounded-lg text-sm font-medium transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit
            </a>
            @endif
            @endcan

            @if($purchase->status === 'draft')
            <form method="POST" action="{{ route('purchases.cancel', $purchase) }}" x-data onsubmit="return confirm('Are you sure you want to cancel this purchase? This action cannot be undone.')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-danger hover:bg-danger text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancel
                </button>
            </form>
            @endif

            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-control-bg hover:bg-control-bg text-body rounded-lg text-sm font-medium transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Print
            </button>

            <a href="{{ route('purchases.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-border hover:bg-card-bg text-body rounded-lg text-sm font-medium transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Invoice Number</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ $purchase->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Supplier</p>
                    <p class="text-sm font-medium text-heading mt-1">
                        @if($purchase->supplier)
                        <a href="{{ route('suppliers.show', $purchase->supplier) }}" class="text-accent hover:text-accent">{{ $purchase->supplier->name }}</a>
                        @else
                        -
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Purchase Date</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ $purchase->purchase_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Due Date</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ isset($purchase->due_date) ? $purchase->due_date->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Payment Terms</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ str_replace('_', ' ', ucfirst($purchase->payment_terms ?? 'cash')) }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Status</p>
                    <div class="mt-1">
                        @if($purchase->status === 'received')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">Received</span>
                        @elseif($purchase->status === 'cancelled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">Cancelled</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-heading">Draft</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Created By</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ $purchase->createdBy->name ?? 'System' }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Approved By</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ $purchase->approvedBy->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Received At</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ isset($purchase->received_at) ? $purchase->received_at->format('d M Y, h:i A') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider">Notes</p>
                    <p class="text-sm font-medium text-heading mt-1">{{ $purchase->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="px-6 py-4 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Purchase Items</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-body">Item Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-body">SKU</th>
                        <th class="px-4 py-3 text-center font-semibold text-body">Qty</th>
                        <th class="px-4 py-3 text-center font-semibold text-body">Received Qty</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Unit Cost</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Discount</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Tax</th>
                        <th class="px-4 py-3 text-right font-semibold text-body">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($purchase->items as $item)
                    @php
                        $lineTax = ($item->quantity * $item->unit_cost - ($item->discount ?? 0)) * 0.18;
                        $lineTotal = ($item->quantity * $item->unit_cost) - ($item->discount ?? 0) + $lineTax;
                    @endphp
                    <tr class="hover:bg-card-bg {{ ($item->received_quantity ?? 0) > 0 && ($item->received_quantity ?? 0) < $item->quantity ? 'bg-warning-light' : '' }}">
                        <td class="px-4 py-3 font-medium text-heading">{{ $item->item->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-muted font-mono">{{ $item->item->sku ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-body">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-center">
                            @php $recv = $item->received_quantity ?? 0; @endphp
                            <span class="{{ $recv < $item->quantity ? 'text-warning font-medium' : 'text-success' }}">
                                {{ $recv }}
                            </span>
                            @if($recv < $item->quantity && $purchase->status === 'received')
                            <span class="text-xs text-warning ml-1">(partial)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-body">TZS {{ number_format($item->unit_cost) }}</td>
                        <td class="px-4 py-3 text-right text-body">TZS {{ number_format($item->discount ?? 0) }}</td>
                        <td class="px-4 py-3 text-right text-muted">TZS {{ number_format($lineTax) }}</td>
                        <td class="px-4 py-3 text-right font-medium text-heading">TZS {{ number_format($lineTotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-card-bg border-t">
                    <tr>
                        <td colspan="6"></td>
                        <td class="px-4 py-2 text-right text-sm font-medium text-body">Subtotal</td>
                        <td class="px-4 py-2 text-right text-sm font-medium text-heading">TZS {{ number_format($purchase->subtotal ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td class="px-4 py-2 text-right text-sm text-body">Tax (18%)</td>
                        <td class="px-4 py-2 text-right text-sm text-heading">TZS {{ number_format($purchase->tax_amount ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td class="px-4 py-2 text-right text-sm text-body">Discount</td>
                        <td class="px-4 py-2 text-right text-sm text-danger">-TZS {{ number_format($purchase->discount_amount ?? 0) }}</td>
                    </tr>
                    <tr class="border-t-2 border-border">
                        <td colspan="6"></td>
                        <td class="px-4 py-2 text-right text-sm font-bold text-heading">Total</td>
                        <td class="px-4 py-2 text-right text-sm font-bold text-heading">TZS {{ number_format($purchase->total_amount ?? 0) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Financial Summary Card --}}
    <div class="bg-white rounded-xl border p-6 max-w-md">
        <h2 class="text-lg font-semibold text-heading mb-4">Financial Summary</h2>
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-body">Subtotal</span>
                <span class="font-medium text-heading">TZS {{ number_format($purchase->subtotal ?? 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-body">Tax (18%)</span>
                <span class="font-medium text-heading">TZS {{ number_format($purchase->tax_amount ?? 0) }}</span>
            </div>
            @if(($purchase->discount_amount ?? 0) > 0)
            <div class="flex justify-between text-sm">
                <span class="text-body">Discount</span>
                <span class="font-medium text-danger">-TZS {{ number_format($purchase->discount_amount) }}</span>
            </div>
            @endif
            <div class="flex justify-between text-base font-bold border-t pt-3">
                <span class="text-heading">Total</span>
                <span class="text-heading">TZS {{ number_format($purchase->total_amount ?? 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-body">Paid</span>
                <span class="font-medium text-success">TZS {{ number_format($purchase->paid_amount ?? 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-body">Due</span>
                <span class="font-bold {{ ($purchase->due_amount ?? 0) > 0 ? 'text-danger' : 'text-success' }}">TZS {{ number_format($purchase->due_amount ?? 0) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
