@extends('layouts.app')

@section('title', 'Create Customer')

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('customers.index') }}" class="hover:text-accent transition-colors">Customers</a>
<span class="mx-2">/</span>
<span class="text-heading">Create Customer</span>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-heading">Create Customer</h1>
        <div class="flex gap-3">
            <a href="{{ route('customers.index') }}" class="px-4 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </div>

    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-border p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="">
                    @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                           class="">
                    @error('phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="">
                    @error('email')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="form-label">Address</label>
                <textarea name="address" rows="2"
                          class="resize-none">{{ old('address') }}</textarea>
                @error('address')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                           class="">
                    @error('city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tin_number" class="form-label">TIN Number</label>
                    <input type="text" name="tin_number" id="tin_number" value="{{ old('tin_number') }}"
                           class="font-mono">
                    @error('tin_number')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-body mb-2">Customer Type <span class="text-danger">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="customer_type" value="individual" {{ old('customer_type', 'individual') == 'individual' ? 'checked' : '' }}
                               class="w-4 h-4 text-accent border-border focus:ring-accent">
                        <span class="text-sm text-body">Individual</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="customer_type" value="business" {{ old('customer_type') == 'business' ? 'checked' : '' }}
                               class="w-4 h-4 text-accent border-border focus:ring-accent">
                        <span class="text-sm text-body">Business</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="customer_type" value="wholesale" {{ old('customer_type') == 'wholesale' ? 'checked' : '' }}
                               class="w-4 h-4 text-accent border-border focus:ring-accent">
                        <span class="text-sm text-body">Wholesale</span>
                    </label>
                </div>
                @error('customer_type')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="credit_limit" class="form-label">Credit Limit (TZS)</label>
                    <input type="number" name="credit_limit" id="credit_limit" value="{{ old('credit_limit', 0) }}" min="0" step="0.01"
                           class="">
                    @error('credit_limit')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-body mb-3">Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                               class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" rows="3"
                          class="resize-none"
                          placeholder="Additional notes...">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('customers.index') }}" class="px-6 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors">Save Customer</button>
        </div>
    </form>
</div>
@endsection