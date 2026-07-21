@extends('layouts.app')

@section('title', 'Expenses - Mtokoma')

@section('header-title', 'Expenses')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Expenses</span>
@endsection

@section('content')
<div x-data="{
    search: '{{ request('search') }}',
    category: '{{ request('category_id') }}',
    dateFrom: '{{ request('from') }}',
    dateTo: '{{ request('to') }}',
    status: '{{ request('status') }}',
    paymentMethod: '{{ request('payment_method') }}',
    showDeleteModal: false,
    deleteId: null,
    deleteForm: null
}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-heading">Expenses</h2>
            <p class="text-sm text-muted mt-1">Track and manage all business expenses</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Expense
        </a>
    </div>

    <div class="bg-white rounded-xl border border-border">
        <form method="GET" action="{{ route('expenses.index') }}">
            <div class="p-4 border-b border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <div class="relative">
                        <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" x-model="search" placeholder="Search expenses..." class="w-full pl-10 pr-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                    </div>
                </div>
                <div>
                    <select name="category_id" x-model="category" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="payment_method" x-model="paymentMethod" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Card</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <select name="status" x-model="status" class="w-full px-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent bg-white">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-b border-gray-100 flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <input type="date" name="from" x-model="dateFrom" class="px-3 py-1.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                    <span class="text-muted text-sm">to</span>
                    <input type="date" name="to" x-model="dateTo" class="px-3 py-1.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                </div>
                <div class="flex items-center gap-2 ml-auto">
                    <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">Filter</button>
                    <a href="{{ route('expenses.index') }}" class="text-muted hover:text-body px-3 py-1.5 text-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-card-bg border-b border-border">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Ref#</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Category</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Amount (TZS)</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Date</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Payment Method</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Description</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Created By</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $methodIcons = [
                            'cash' => ['bg-success-light text-success', 'Cash', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                            'bank_transfer' => ['bg-accent-light text-accent', 'Bank', 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21'],
                            'mobile_money' => ['bg-purple-100 text-purple-600', 'Mobile', 'M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3'],
                            'card' => ['bg-warning-light text-warning', 'Card', 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
                            'other' => ['bg-control-bg text-gray-600', 'Other', 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ];
                        $totalAmount = 0;
                    @endphp
                    @forelse($expenses ?? [] as $exp)
                        @php
                            $m = $methodIcons[$exp->payment_method] ?? $methodIcons['other'];
                            $totalAmount += $exp->amount;
                        @endphp
                        <tr class="hover:bg-card-bg transition-colors">
                            <td class="px-4 py-3 text-sm font-mono text-body">{{ $exp->reference_number ?? $exp->ref_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-heading">{{ $exp->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-heading text-right">{{ number_format($exp->amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-muted">{{ $exp->expense_date ? $exp->expense_date->format('d M Y') : $exp->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $m[0] }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $m[2] }}"/></svg>
                                    {{ $m[1] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-muted max-w-[200px] truncate" title="{{ $exp->description ?? '' }}">{{ $exp->description ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($exp->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-green-800">Approved</span>
                                @elseif($exp->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-light text-amber-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-muted">{{ $exp->createdBy->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1" x-data="{ open: false }">
                                    <a href="{{ route('expenses.edit', $exp) }}" class="p-1.5 rounded-lg text-muted hover:text-accent hover:bg-primary-light transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                    <button @click="deleteId = {{ $exp->id }}; showDeleteModal = true" class="p-1.5 rounded-lg text-muted hover:text-danger hover:bg-danger-light transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 012.828 0l1.414 1.42.879-.659M12 18V6m0 12H7.5m4.5 0h4.5"/>
                                </svg>
                                <p class="text-muted font-medium">No expenses found</p>
                                <p class="text-sm text-muted mt-1">Start by adding your first expense.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(isset($expenses) && $expenses->count())
                <tfoot>
                    <tr class="bg-card-bg border-t-2 border-border">
                        <td colspan="2" class="px-4 py-3 text-sm font-semibold text-heading">Total</td>
                        <td class="px-4 py-3 text-sm font-bold text-right text-heading">{{ number_format($totalAmount, 2) }}</td>
                        <td colspan="6" class="text-right px-4 py-3">
                            <span class="text-xs text-muted">{{ $expenses->total() }} expense(s)</span>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        @if(isset($expenses) && $expenses->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="showDeleteModal = false"
             class="bg-white rounded-xl w-full max-w-md p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-heading">Delete Expense</h3>
                    <p class="text-sm text-muted">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete this expense? This will permanently remove it.</p>
            <div class="flex items-center justify-end gap-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 text-sm font-medium text-body bg-white border border-border rounded-lg hover:bg-card-bg transition-colors">Cancel</button>
                <form :action="'{{ url('/expenses') }}/' + deleteId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-danger rounded-lg hover:bg-danger transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
