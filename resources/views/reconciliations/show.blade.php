@extends('layouts.app')

@section('title', 'Reconciliation Details - Mtokoma')

@section('header-title', 'Reconciliation Details')

@section('breadcrumbs')
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('reconciliations.index') }}" class="hover:text-electric transition-colors">Reconciliations</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-700 font-medium">{{ $reconciliation->date ? $reconciliation->date->format('d M Y') : 'Details' }}</span>
@endsection

@section('content')
@php $rec = $reconciliation ?? null; @endphp
@if($rec)
@php
    $diff = ($rec->actual_cash ?? 0) - ($rec->expected_cash ?? 0);
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('reconciliations.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">Reconciliation</h2>
                @if($rec->status === 'completed')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                @elseif($rec->status === 'discrepancy')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Discrepancy</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Pending</span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">{{ ucfirst($rec->type) }} | {{ $rec->date ? $rec->date->format('d M Y') : $rec->created_at->format('d M Y') }}</p>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 012.828 0l1.414 1.42.879-.659M12 18V6m0 12h4.5m-4.5 0a2 2 0 01-1.732-1M12 18h4.5m-4.5 0a2 2 0 001.732-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm text-gray-500">Expected Cash</p>
        </div>
        <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($rec->expected_cash ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
            <p class="text-sm text-gray-500">Actual Cash</p>
        </div>
        <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($rec->actual_cash ?? 0, 2) }}</p>
    </div>
    <div class="rounded-xl shadow-sm border p-6 {{ $diff != 0 ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' }}">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg {{ $diff != 0 ? 'bg-red-100' : 'bg-green-100' }} flex items-center justify-center">
                @if($diff != 0)
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                @else
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </div>
            <p class="text-sm text-gray-500">Difference</p>
        </div>
        <p class="text-2xl font-bold {{ $diff != 0 ? 'text-red-600' : 'text-green-600' }}">{{ $diff >= 0 ? '+' : '' }}TZS {{ number_format($diff, 2) }}</p>
    </div>
</div>

@if($diff != 0)
<div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
    </svg>
    <p class="text-sm text-red-800">Discrepancy detected. Actual cash does not match expected amount by TZS {{ number_format(abs($diff), 2) }}.</p>
</div>
@endif

{{-- Breakdown --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Sales Total</h3>
        <p class="text-xl font-bold text-green-600">TZS {{ number_format($rec->sales_total ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Purchases Total</h3>
        <p class="text-xl font-bold text-amber-600">TZS {{ number_format($rec->purchases_total ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Expenses Total</h3>
        <p class="text-xl font-bold text-red-600">TZS {{ number_format($rec->expenses_total ?? 0, 2) }}</p>
    </div>
</div>

{{-- Detail Transactions --}}
@if(isset($rec->transactions) && $rec->transactions->count())
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Period Transactions</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rec->transactions as $tx)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm text-gray-500">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $tx->description ?? $tx->reference ?? '-' }}</td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($tx->type ?? 'other') }}</span>
                    </td>
                    <td class="px-6 py-3 text-sm font-medium text-right {{ ($tx->type ?? '') === 'expense' || ($tx->type ?? '') === 'purchase' ? 'text-red-600' : 'text-green-600' }}">
                        {{ ($tx->type ?? '') === 'expense' || ($tx->type ?? '') === 'purchase' ? '-' : '+' }} TZS {{ number_format($tx->amount ?? 0, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Notes --}}
@if($rec->notes)
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $rec->notes }}</p>
</div>
@endif

@else
<div class="text-center py-16">
    <p class="text-gray-500">Reconciliation not found.</p>
</div>
@endif
@endsection
