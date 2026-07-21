@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Payments</h1>
            <p class="text-gray-500 mt-1">View all payment transactions</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('payments.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm min-w-[160px]">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="mobile_money" {{ request('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="payable_type" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm min-w-[160px]">
                    <option value="">All Types</option>
                    <option value="sale" {{ request('payable_type') === 'sale' ? 'selected' : '' }}>Sale Payment</option>
                    <option value="purchase" {{ request('payable_type') === 'purchase' ? 'selected' : '' }}>Purchase Payment</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm">
            </div>
            <button type="submit" class="px-6 py-2 bg-tz-green text-white rounded-lg hover:bg-tz-green-dark transition-colors text-sm font-medium">Filter</button>
            <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">Reset</a>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Ref #</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Date</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Type</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Invoice #</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Amount</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Method</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Created By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments ?? [] as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-tz-green">{{ $payment->reference_number }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($payment->payable_type === 'App\\Models\\Sale' || (isset($payment->type) && $payment->type === 'sale'))
                                    <span class="px-2 py-1 bg-tz-green-light text-tz-green rounded-full text-xs font-medium">Sale</span>
                                @else
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">Purchase</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $payment->payable->invoice_number ?? $payment->payable->po_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-800">TZS {{ number_format($payment->amount) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $methodColors = [
                                        'cash' => 'bg-green-100 text-green-700',
                                        'bank_transfer' => 'bg-tz-green-light text-tz-green',
                                        'mobile_money' => 'bg-purple-100 text-purple-700',
                                        'card' => 'bg-cyan-100 text-cyan-700',
                                        'cheque' => 'bg-amber-100 text-amber-700',
                                    ];
                                    $methodLabels = [
                                        'cash' => 'Cash',
                                        'bank_transfer' => 'Bank Transfer',
                                        'mobile_money' => 'Mobile Money',
                                        'card' => 'Card',
                                        'cheque' => 'Cheque',
                                    ];
                                @endphp
                                <span class="px-2 py-1 {{ $methodColors[$payment->payment_method] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-medium">
                                    {{ $methodLabels[$payment->payment_method] ?? ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $payment->createdBy->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                No payments found matching your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($payments) && $payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection