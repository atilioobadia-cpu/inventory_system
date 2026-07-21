@extends('layouts.app')

@section('title', $expenseCategory->name . ' - Expense Category')

@section('header-title', $expenseCategory->name)

@section('breadcrumbs')
<span class="mx-2 text-gray-400">/</span>
<a href="{{ route('expense-categories.index') }}" class="hover:text-tz-green transition-colors">Expense Categories</a>
<span class="mx-2 text-gray-400">/</span>
<span class="text-gray-700 font-medium">{{ $expenseCategory->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $expenseCategory->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $expenseCategory->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('expense-categories.edit', $expenseCategory) }}" class="px-4 py-2 bg-tz-green text-white rounded-lg text-sm font-medium hover:bg-tz-green-dark transition-colors">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Total Expenses</p>
            <p class="text-2xl font-bold text-gray-900">{{ $expenseCategory->expenses_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-2xl font-bold {{ $expenseCategory->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $expenseCategory->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>

    @if(isset($recentExpenses) && $recentExpenses->count())
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Recent Expenses</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Reference</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Description</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Amount</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentExpenses as $expense)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-500">{{ $expense->reference_number }}</td>
                        <td class="px-6 py-3 text-gray-800">{{ $expense->description ?? '-' }}</td>
                        <td class="px-6 py-3 text-right font-medium">TZS {{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $expense->expense_date->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
