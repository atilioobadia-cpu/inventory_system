@extends('layouts.app')

@section('title', 'Reconciliations - Mtokoma')

@section('header-title', 'Reconciliations')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Reconciliations</span>
@endsection

@section('content')
<div x-data="{
    dateFrom: '{{ request('date_from') }}',
    dateTo: '{{ request('date_to') }}',
    type: '{{ request('type') }}',
    status: '{{ request('status') }}'
}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-heading">Reconciliations</h2>
            <p class="text-sm text-muted mt-1">Track cash reconciliation records</p>
        </div>
        <a href="{{ route('reconciliations.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Create Reconciliation
        </a>
    </div>

    <div class="bg-white rounded-xl border border-border">
        <form method="GET" action="{{ route('reconciliations.index') }}">
            <div class="p-4 border-b border-border flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-xs font-medium text-muted mb-1">Date From</label>
                    <input type="date" name="date_from" x-model="dateFrom" class="px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted mb-1">Date To</label>
                    <input type="date" name="date_to" x-model="dateTo" class="px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted mb-1">Type</label>
                    <select name="type" x-model="type" class="px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="">All Types</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted mb-1">Status</label>
                    <select name="status" x-model="status" class="px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="discrepancy">Discrepancy</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">Filter</button>
                    <a href="{{ route('reconciliations.index') }}" class="text-muted hover:text-body px-3 py-2 text-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-card-bg border-b border-border">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Date</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Type</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Expected Cash</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Actual Cash</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Difference</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Sales Total</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Expenses Total</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Created By</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($reconciliations ?? [] as $rec)
                        @php
                            $diff = ($rec->actual_cash ?? 0) - ($rec->expected_cash ?? 0);
                        @endphp
                        <tr class="hover:bg-card-bg transition-colors">
                            <td class="px-4 py-3 text-sm text-heading">{{ $rec->reconciliation_date ? $rec->reconciliation_date->format('d M Y') : $rec->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-heading">{{ ucfirst($rec->type) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($rec->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Completed
                                    </span>
                                @elseif($rec->status === 'discrepancy')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        Discrepancy
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-body">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($rec->expected_cash ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-heading text-right">TZS {{ number_format($rec->actual_cash ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-bold text-right {{ $diff != 0 ? 'text-danger' : 'text-success' }}">
                                {{ $diff >= 0 ? '+' : '' }}TZS {{ number_format($diff, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($rec->total_sales ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-body text-right">TZS {{ number_format($rec->total_expenses ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-muted">{{ $rec->reconciledBy->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('reconciliations.show', $rec) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-accent hover:bg-accent-light rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-16 text-center">
                                <svg class="w-16 h-16 text-muted mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <p class="text-muted font-medium">No reconciliations found</p>
                                <p class="text-sm text-muted mt-1">Create your first reconciliation to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($reconciliations) && $reconciliations->hasPages())
        <div class="px-4 py-3 border-t border-border">
            {{ $reconciliations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
