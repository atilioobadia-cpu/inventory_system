@extends('layouts.app')

@section('title', 'Suppliers')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('suppliers.index') }}" class="hover:text-tz-green transition-colors">Suppliers</a>
<span class="mx-2">/</span>
<span class="text-gray-800">All Suppliers</span>
@endsection

@section('content')
<div x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Suppliers</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $suppliers->total() ?? 0 }} suppliers</p>
        </div>
        @can('create_suppliers')
        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center gap-2 bg-tz-green text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-tz-green-dark transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Supplier
        </a>
        @endcan
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('suppliers.index') }}">
            <div class="flex gap-4">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search suppliers..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/20 focus:border-tz-green">
                </div>
                <button type="submit" class="bg-tz-blue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-tz-blue-light transition-colors">Search</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if(($suppliers ?? collect())->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Name</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Contact Person</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Phone</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Email</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-500">Balance</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Status</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="font-medium text-gray-800 hover:text-tz-green">{{ $supplier->name }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $supplier->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $supplier->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-medium {{ ($supplier->current_balance ?? 0) > 0 ? 'text-danger' : 'text-gray-800' }}">
                            TZS {{ number_format($supplier->current_balance ?? 0) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($supplier->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Active</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="p-1.5 text-gray-400 hover:text-tz-green rounded-lg hover:bg-tz-green-light transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>
                                @can('edit_suppliers')
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="p-1.5 text-gray-400 hover:text-tz-green rounded-lg hover:bg-tz-green-light transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                    </svg>
                                </a>
                                @endcan
                                @can('delete_suppliers')
                                <button @click="deleteModal = true; deleteUrl = '{{ route('suppliers.destroy', $supplier) }}'; deleteName = '{{ $supplier->name }}'"
                                        class="p-1.5 text-gray-400 hover:text-danger rounded-lg hover:bg-red-50 transition-colors" title="Delete">
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

        @if($suppliers->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $suppliers->withQueryString()->links() }}
        </div>
        @endif
        @else
        <div class="py-16 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75m-7.5-2.25h7.5m-7.5 0H6.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125H9"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-500 mb-1">No suppliers found</h3>
            <p class="text-sm text-gray-400 mb-4">Add your first supplier to get started.</p>
            @can('create_suppliers')
            <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 bg-tz-green text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-tz-green-dark transition-colors">
                Add Supplier
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
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Delete Supplier</h3>
                <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete <span class="font-semibold" x-text="deleteName"></span>? This may affect purchase records.</p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-danger rounded-lg hover:bg-red-600">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection