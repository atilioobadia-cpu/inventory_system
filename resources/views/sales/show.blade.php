@extends('layouts.app')

@section('title', 'Sale Details - Mtokoma')

@section('header-title', 'Sale Details')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <a href="{{ route('sales.index') }}" class="hover:text-primary transition-colors">Sales</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">{{ $sale->invoice_number ?? 'Sale' }}</span>
@endsection

@section('content')
@php
    $s = $sale ?? null;
@endphp
@if($s)
<div x-data="{ showReceiptModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('sales.index') }}" class="p-2 rounded-lg text-muted hover:text-body hover:bg-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-heading">{{ $s->invoice_number }}</h2>
                    @if($s->is_voided)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-body">Voided</span>
                    @endif
                </div>
                <p class="text-sm text-muted mt-1">Created {{ $s->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showReceiptModal = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-body bg-white border border-border rounded-lg hover:bg-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                View Receipt
            </button>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-body bg-white border border-border rounded-lg hover:bg-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Print
            </button>
            @if(!$s->is_voided)
            <form action="{{ route('sales.void', $s) }}" method="POST" onsubmit="return confirm('Are you sure you want to void this sale?')">
                @csrf
                <input type="text" name="void_reason" placeholder="Reason for voiding" required minlength="3" maxlength="500"
                       class="px-3 py-2 border border-danger rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-danger/50 focus:border-danger mr-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-danger bg-danger-light border border-danger rounded-lg hover:bg-danger-light transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    Void
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-5">
            {{-- Sale Info --}}
            <div class="bg-white rounded-lg border border-border p-5">
                <h3 class="text-lg font-semibold text-heading mb-4">Sale Information</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider">Customer</p>
                        <p class="text-sm font-medium text-heading mt-1">{{ $s->customer->name ?? 'Walk-in Customer' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider">Date</p>
                        <p class="text-sm font-medium text-heading mt-1">{{ $s->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider">Sale Type</p>
                        <p class="text-sm font-medium text-heading mt-1">{{ ucfirst($s->sale_type ?? 'walk-in') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider">Created By</p>
                        <p class="text-sm font-medium text-heading mt-1">{{ $s->createdBy->name ?? 'System' }}</p>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="bg-white rounded-lg border border-border">
                <div class="px-5 py-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-heading">Sale Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-white border-b border-border">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Item</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">SKU</th>
                                <th class="text-center px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Qty</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Unit Price</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Cost</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Discount</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Tax</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Total</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($s->items ?? [] as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-6 py-3 text-sm font-medium text-heading">{{ $item->item->name ?? $item->name ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm text-muted font-mono">{{ $item->item->sku ?? $item->sku ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm text-body text-center">{{ $item->quantity }}</td>
                                    <td class="px-6 py-3 text-sm text-body text-right">TZS {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-3 text-sm text-muted text-right">TZS {{ number_format($item->cost_price ?? 0, 2) }}</td>
                                    <td class="px-6 py-3 text-sm text-muted text-right">TZS {{ number_format($item->discount ?? 0, 2) }}</td>
                                    <td class="px-6 py-3 text-sm text-muted text-right">TZS {{ number_format($item->tax_amount ?? 0, 2) }}</td>
                                    <td class="px-6 py-3 text-sm font-semibold text-heading text-right">TZS {{ number_format($item->total ?? ($item->unit_price * $item->quantity), 2) }}</td>
                                    <td class="px-6 py-3 text-sm text-right">
                                        @php $profit = ($item->unit_price - ($item->cost_price ?? 0)) * $item->quantity - ($item->discount ?? 0); @endphp
                                        <span class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }} font-medium">
                                            TZS {{ number_format($profit, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment History --}}
            <div class="bg-white rounded-lg border border-border p-5">
                <h3 class="text-lg font-semibold text-heading mb-4">Payment History</h3>
                @if(isset($s->payments) && $s->payments->count())
                    <div class="space-y-3">
                        @foreach($s->payments as $payment)
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-success-light flex items-center justify-center">
                                        <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 0 1 2.828 0l1.414 1.42.879-.659M12 18V6m0 12h4.5m-4.5 0a2 2 0 0 1-1.732-1M12 18h4.5m-4.5 0a2 2 0 0 0 1.732-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-heading">Payment via {{ ucfirst($payment->method ?? 'Cash') }}</p>
                                        <p class="text-xs text-muted">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-success">+ TZS {{ number_format($payment->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-muted text-center py-4">No payments recorded.</p>
                @endif
            </div>

            {{-- Void Info --}}
            @if($s->is_voided && isset($s->void_reason))
            <div class="bg-danger-light rounded-lg border border-danger p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-danger-light flex items-center justify-center">
                        <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-danger">Void Information</h3>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-danger uppercase tracking-wider">Reason</p>
                        <p class="text-sm font-medium text-danger mt-1">{{ $s->void_reason }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-danger uppercase tracking-wider">Voided On</p>
                        <p class="text-sm font-medium text-danger mt-1">{{ isset($s->voided_at) ? $s->voided_at->format('d M Y, h:i A') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-danger uppercase tracking-wider">Voided By</p>
                        <p class="text-sm font-medium text-danger mt-1">{{ $s->voidedBy->name ?? 'System' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar: Financial Summary --}}
        <div class="space-y-5">
            <div class="bg-white rounded-lg border border-border p-5 sticky top-20">
                <h3 class="text-lg font-semibold text-heading mb-4">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-muted">Subtotal</span>
                        <span class="text-sm text-heading">TZS {{ number_format($s->subtotal, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-muted">VAT ({{ $s->tax_rate ?? 18 }}%)</span>
                        <span class="text-sm text-heading">TZS {{ number_format($s->tax_amount, 2) }}</span>
                    </div>
                    @if(($s->discount_amount ?? 0) > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-muted">Discount</span>
                        <span class="text-sm text-danger">- TZS {{ number_format($s->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <hr class="border-border">
                    <div class="flex items-center justify-between">
                        <span class="text-base font-semibold text-heading">Total</span>
                        <span class="text-base font-bold text-heading">TZS {{ number_format($s->total_amount, 2) }}</span>
                    </div>
                    <hr class="border-border">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-muted">Amount Paid</span>
                        <span class="text-sm font-medium text-success">TZS {{ number_format($s->paid_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-muted">Amount Due</span>
                        @php $due = $s->total_amount - ($s->paid_amount ?? 0); @endphp
                        <span class="text-sm font-medium {{ $due > 0 ? 'text-danger' : 'text-success' }}">TZS {{ number_format($due, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($s->notes)
            <div class="bg-white rounded-lg border border-border p-5">
                <h3 class="text-sm font-semibold text-heading mb-2">Notes</h3>
                <p class="text-sm text-body whitespace-pre-line">{{ $s->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="text-center py-16">
    <p class="text-muted">Sale not found.</p>
</div>
@endif
@endsection
