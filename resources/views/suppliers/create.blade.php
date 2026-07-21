@extends('layouts.app')

@section('title', 'Create Supplier')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('suppliers.index') }}" class="hover:text-tz-green transition-colors">Suppliers</a>
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
                    <label for="name" class="form-label">Company Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}"
                           class="">
                    @error('contact_person')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="">
                    @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="form-label">Address</label>
                <textarea name="address" rows="2"
                          class="resize-none">{{ old('address') }}</textarea>
                @error('address')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                           class="">
                    @error('city')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tin_number" class="form-label">TIN Number</label>
                    <input type="text" name="tin_number" id="tin_number" value="{{ old('tin_number') }}"
                           class="font-mono">
                    @error('tin_number')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="payment_terms" class="form-label">Payment Terms</label>
                    <select name="payment_terms" id="payment_terms" class="">
                        <option value="cash" {{ old('payment_terms') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="net15" {{ old('payment_terms') == 'net15' ? 'selected' : '' }}>Net 15</option>
                        <option value="net30" {{ old('payment_terms') == 'net30' ? 'selected' : '' }}>Net 30</option>
                        <option value="net60" {{ old('payment_terms') == 'net60' ? 'selected' : '' }}>Net 60</option>
                        <option value="cod" {{ old('payment_terms') == 'cod' ? 'selected' : '' }}>COD</option>
                    </select>
                    @error('payment_terms')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="credit_limit" class="form-label">Credit Limit (TZS)</label>
                    <input type="number" name="credit_limit" id="credit_limit" value="{{ old('credit_limit', 0) }}" min="0" step="0.01"
                           class="">
                    @error('credit_limit')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                               class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-tz-green/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" rows="3"
                          class="resize-none"
                          placeholder="Additional notes...">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('suppliers.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-tz-green rounded-lg hover:bg-tz-green-dark transition-colors">Save Supplier</button>
        </div>
    </form>
</div>
@endsection