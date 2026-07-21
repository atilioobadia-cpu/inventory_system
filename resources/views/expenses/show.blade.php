@extends('layouts.app')

@section('title', 'Expense Details - Mtokoma')

@section('header-title', 'Expense Details')

@section('breadcrumbs')
<span class="mx-2 text-gray-400">/</span>
<a href="{{ route('expenses.index') }}" class="hover:text-tz-green transition-colors">Expenses</a>
<span class="mx-2 text-gray-400">/</span>
<span class="text-gray-700 font-medium">{{ $expense->reference_number }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $expense->reference_number }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $expense->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($expense->status !== 'approved')
            <a href="{{ route('expenses.edit', $expense) }}" class="px-4 py-2 bg-tz-green text-white rounded-lg text-sm font-medium hover:bg-tz-green-dark transition-colors">Edit</a>
            @endif
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $expense->status === 'approved' ? 'bg-green-100 text-green-800' : ($expense->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                {{ ucfirst($expense->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Amount</p>
            <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($expense->amount, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Category</p>
            <p class="text-lg font-bold text-gray-900">{{ $expense->category->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Payment Method</p>
            <p class="text-lg font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Date</p>
            <p class="text-lg font-bold text-gray-900">{{ $expense->expense_date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Details</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Description</dt>
                <dd class="text-gray-900 mt-1">{{ $expense->description ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Created By</dt>
                <dd class="text-gray-900 mt-1">{{ $expense->createdBy->name ?? 'System' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Approved By</dt>
                <dd class="text-gray-900 mt-1">{{ $expense->approvedBy->name ?? 'Pending' }}</dd>
            </div>
        </dl>
    </div>

    @if($expense->receipt_path)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Receipt</h3>
        <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-tz-green hover:underline">View Receipt</a>
    </div>
    @endif
</div>
@endsection
