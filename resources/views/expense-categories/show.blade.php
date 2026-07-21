@extends('layouts.app')

@section('title', $expenseCategory->name . ' - Expense Category')

@section('header-title', $expenseCategory->name)

@section('breadcrumbs')
<span class="mx-2 text-muted">/</span>
<a href="{{ route('expense-categories.index') }}" class="hover:text-primary transition-colors">Expense Categories</a>
<span class="mx-2 text-muted">/</span>
<span class="text-body font-medium">{{ $expenseCategory->name }}</span>
@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-heading">{{ $expenseCategory->name }}</h2>
            <p class="text-sm text-muted mt-1">{{ $expenseCategory->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('expense-categories.edit', $expenseCategory) }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border border-border p-5">
            <p class="text-sm text-muted">Total Expenses</p>
            <p class="text-xl font-bold text-heading">{{ $expenseCategory->expenses_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <p class="text-sm text-muted">Status</p>
            <p class="text-xl font-bold {{ $expenseCategory->is_active ? 'text-success' : 'text-danger' }}">{{ $expenseCategory->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>

    @if(isset($recentExpenses) && $recentExpenses->count())
    <div class="bg-white rounded-lg border border-border">
        <div class="p-5 border-b border-border">
            <h3 class="text-lg font-semibold text-heading">Recent Expenses</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Reference</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Description</th>
                        <th class="text-right px-6 py-3 font-medium text-muted">Amount</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($recentExpenses as $expense)
                    <tr class="hover:bg-white">
                        <td class="px-6 py-3 text-muted">{{ $expense->reference_number }}</td>
                        <td class="px-6 py-3 text-heading">{{ $expense->description ?? '-' }}</td>
                        <td class="px-6 py-3 text-right font-medium">TZS {{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-3 text-muted">{{ $expense->expense_date->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
