@extends('layouts.app')

@section('title', 'Create Supplier')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('suppliers.index') }}" class="hover:text-electric transition-colors">Suppliers</a>
<span class="mx-2">/</span>
<span class="text-gray-800">Create Supplier</span>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Supplier</h1>
        <div class="flex gap-3">
            <a href="{{ route('suppliers.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </div>

    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('contact_person')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('email')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric resize-none">{{ old('address') }}</textarea>
                @error('address')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tin_number" class="block text-sm font-medium text-gray-700 mb-1">TIN Number</label>
                    <input type="text" name="tin_number" id="tin_number" value="{{ old('tin_number') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric font-mono">
                    @error('tin_number')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                    <select name="payment_terms" id="payment_terms" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric text-gray-600">
                        <option value="cash" {{ old('payment_terms') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="net15" {{ old('payment_terms') == 'net15' ? 'selected' : '' }}>Net 15</option>
                        <option value="net30" {{ old('payment_terms') == 'net30' ? 'selected' : '' }}>Net 30</option>
                        <option value="net60" {{ old('payment_terms') == 'net60' ? 'selected' : '' }}>Net 60</option>
                        <option value="cod" {{ old('payment_terms') == 'cod' ? 'selected' : '' }}>COD</option>
                    </select>
                    @error('payment_terms')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-1">Credit Limit (TZS)</label>
                    <input type="number" name="credit_limit" id="credit_limit" value="{{ old('credit_limit', 0) }}" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                    @error('credit_limit')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                               class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-electric/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric resize-none"
                          placeholder="Additional notes...">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('suppliers.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-electric rounded-lg hover:bg-blue-600 transition-colors">Save Supplier</button>
        </div>
    </form>
</div>
@endsection