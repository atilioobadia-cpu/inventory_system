@extends('layouts.app')

@section('title', 'Edit Customer - ' . ($customer->name ?? ''))

@section('breadcrumbs')
<span class="mx-2">/</span>
<a href="{{ route('customers.index') }}" class="hover:text-primary transition-colors">Customers</a>
<span class="mx-2">/</span>
<a href="{{ route('customers.show', $customer) }}" class="hover:text-primary transition-colors">{{ $customer->name }}</a>
<span class="mx-2">/</span>
<span class="text-gray-900">Edit</span>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="page-title">Edit Customer</h1>
        <div class="flex gap-3">
            <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>

    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')
        <div class="card card-body space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="form-label flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Customer Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                           class="">
                    @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="form-label flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        Phone <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" required
                           class="">
                    @error('phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="form-label flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                           class="">
                    @error('email')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="form-label flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    Address
                </label>
                <textarea name="address" rows="2"
                          class="resize-none">{{ old('address', $customer->address) }}</textarea>
                @error('address')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $customer->city) }}"
                           class="">
                    @error('city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tin_number" class="form-label">TIN Number</label>
                    <input type="text" name="tin_number" id="tin_number" value="{{ old('tin_number', $customer->tin_number) }}"
                           class="font-mono">
                    @error('tin_number')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type <span class="text-danger">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="individual" {{ old('type', $customer->type ?? 'individual') == 'individual' ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-border focus:ring-primary">
                        <span class="text-sm text-gray-700">Individual</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="business" {{ old('type', $customer->type) == 'business' ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-border focus:ring-primary">
                        <span class="text-sm text-gray-700">Business</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="wholesale" {{ old('type', $customer->type) == 'wholesale' ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-border focus:ring-primary">
                        <span class="text-sm text-gray-700">Wholesale</span>
                    </label>
                </div>
                @error('type')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="credit_limit" class="form-label flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Credit Limit (TZS)
                    </label>
                    <input type="number" name="credit_limit" id="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}" min="0" step="0.01"
                           class="">
                    @error('credit_limit')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" rows="3"
                          class="resize-none">{{ old('notes', $customer->notes) }}</textarea>
                @error('notes')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Update Customer
            </button>
        </div>
    </form>
</div>
@endsection
