@extends('layouts.app')

@section('title', 'Expense Details - Mtokoma')

@section('header-title', 'Expense Details')

@section('breadcrumbs')
<span class="mx-2 text-muted">/</span>
<a href="{{ route('expenses.index') }}" class="hover:text-primary transition-colors">Expenses</a>
<span class="mx-2 text-muted">/</span>
<span class="text-body font-medium">{{ $expense->reference_number }}</span>
@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-heading">{{ $expense->reference_number }}</h2>
            <p class="text-sm text-muted mt-1">{{ $expense->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($expense->status !== 'approved')
            <a href="{{ route('expenses.edit', $expense) }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">Edit</a>
            @endif
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $expense->status === 'approved' ? 'bg-success-light text-success' : ($expense->status === 'rejected' ? 'bg-danger-light text-danger' : 'bg-warning-light text-warning') }}">
                {{ ucfirst($expense->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-border p-5">
            <p class="text-sm text-muted">Amount</p>
            <p class="text-xl font-bold text-heading">TZS {{ number_format($expense->amount, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <p class="text-sm text-muted">Category</p>
            <p class="text-lg font-bold text-heading">{{ $expense->category->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <p class="text-sm text-muted">Payment Method</p>
            <p class="text-lg font-bold text-heading">{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</p>
        </div>
        <div class="bg-white rounded-lg border border-border p-5">
            <p class="text-sm text-muted">Date</p>
            <p class="text-lg font-bold text-heading">{{ $expense->expense_date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-border p-5">
        <h3 class="text-lg font-semibold text-heading mb-4">Details</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-muted">Description</dt>
                <dd class="text-heading mt-1">{{ $expense->description ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-muted">Created By</dt>
                <dd class="text-heading mt-1">{{ $expense->createdBy->name ?? 'System' }}</dd>
            </div>
            <div>
                <dt class="text-muted">Approved By</dt>
                <dd class="text-heading mt-1">{{ $expense->approvedBy->name ?? 'Pending' }}</dd>
            </div>
        </dl>
    </div>

    @if($expense->receipt_path)
    <div class="bg-white rounded-lg border border-border p-5">
        <h3 class="text-lg font-semibold text-heading mb-4">Receipt</h3>
        <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-primary hover:underline">View Receipt</a>
    </div>
    @endif
</div>
@endsection
