@extends('layouts.app')

@section('title', 'Customers')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('customers.index') }}" class="hover:text-accent transition-colors">Customers</a>
<span class="mx-2">/</span>
<span class="text-heading">All Customers</span>
@endsection

@section('content')
<div x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-heading flex items-center gap-2">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                Customers
            </h1>
            <p class="text-sm text-muted mt-1">{{ $customers->total() ?? 0 }} customers</p>
        </div>
        @can('create_customers')
        <a href="{{ route('customers.create') }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-hover transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Customer
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg border border-border p-4 mb-6">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="relative sm:col-span-2">
                    <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..."
                           class="w-full pl-10 pr-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
                </div>
                <select name="customer_type" class="border border-border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent text-body">
                    <option value="">All Types</option>
                    <option value="individual" {{ request('customer_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="business" {{ request('customer_type') == 'business' ? 'selected' : '' }}>Business</option>
                    <option value="wholesale" {{ request('customer_type') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="bg-accent text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-accent-light transition-colors">Filter</button>
                    <a href="{{ route('customers.index') }}" class="bg-control-bg text-body px-4 py-2 rounded-lg text-sm font-medium hover:bg-control-bg transition-colors">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-border overflow-hidden">
        @if(($customers ?? collect())->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-muted">Name</th>
                        <th class="text-left px-4 py-3 font-medium text-muted">Phone</th>
                        <th class="text-left px-4 py-3 font-medium text-muted">Email</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Type</th>
                        <th class="text-right px-4 py-3 font-medium text-muted">Balance</th>
                        <th class="text-center px-4 py-3 font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($customers as $customer)
                    <tr class="hover:bg-card-bg transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('customers.show', $customer) }}" class="font-medium text-heading hover:text-accent">{{ $customer->name }}</a>
                        </td>
                        <td class="px-4 py-3 text-body">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-body">{{ $customer->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($customer->customer_type === 'wholesale') bg-purple-50 text-purple-700
                                @elseif($customer->customer_type === 'business') bg-accent-light text-accent
                                @else bg-control-bg text-body @endif">
                                {{ ucfirst($customer->customer_type ?? 'individual') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-medium {{ ($customer->current_balance ?? 0) > 0 ? 'text-danger' : 'text-heading' }}">
                            TZS {{ number_format($customer->current_balance ?? 0) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('customers.show', $customer) }}" class="p-1.5 text-muted hover:text-accent rounded-lg hover:bg-accent-light transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>
                                @can('edit_customers')
                                <a href="{{ route('customers.edit', $customer) }}" class="p-1.5 text-muted hover:text-accent rounded-lg hover:bg-accent-light transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                    </svg>
                                </a>
                                @endcan
                                @can('delete_customers')
                                <button @click="deleteModal = true; deleteUrl = '{{ route('customers.destroy', $customer) }}'; deleteName = '{{ $customer->name }}'"
                                        class="p-1.5 text-muted hover:text-danger rounded-lg hover:bg-danger-light transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="px-4 py-3 border-t border-border">{{ $customers->withQueryString()->links() }}</div>
        @endif
        @else
        <div class="py-16 text-center">
            <svg class="w-16 h-16 text-muted/50 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
            </svg>
            <h3 class="text-lg font-medium text-muted mb-1">No customers found</h3>
            <p class="text-sm text-muted mb-4">Add your first customer to get started.</p>
            @can('create_customers')
            <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Customer
            </a>
            @endcan
        </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div x-show="deleteModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="deleteModal = false"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-heading mb-2">Delete Customer</h3>
                <p class="text-sm text-body mb-6">Are you sure you want to delete <span class="font-semibold" x-text="deleteName"></span>? This will affect sales records.</p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg">Cancel</button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-danger rounded-lg hover:bg-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
