@extends('layouts.app')

@section('title', 'Add Expense - Mtokoma')

@section('header-title', 'Add Expense')

@section('breadcrumbs')
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('expenses.index') }}" class="hover:text-electric transition-colors">Expenses</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-700 font-medium">Add Expense</span>
@endsection

@section('content')
<div x-data="{ isRecurring: {{ old('is_recurring') ? 'true' : 'false' }} }">
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-3xl mx-auto space-y-6">
            {{-- Basic Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Expense Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="expense_category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select name="expense_category_id" id="expense_category_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric bg-white">
                            <option value="">Select Category</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (TZS) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">TZS</span>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                                   class="w-full pl-14 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric"
                                   placeholder="0.00">
                        </div>
                        @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                        @error('expense_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            @php
                                $methods = [
                                    'cash' => 'Cash',
                                    'bank_transfer' => 'Bank Transfer',
                                    'mobile_money' => 'Mobile Money',
                                    'card' => 'Card',
                                    'other' => 'Other',
                                ];
                            @endphp
                            @foreach($methods as $value => $label)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="{{ $value }}" {{ old('payment_method', 'cash') === $value ? 'checked' : '' }} class="peer sr-only" required>
                                    <div class="border-2 border-gray-200 rounded-lg px-3 py-2 text-center text-xs font-medium peer-checked:border-electric peer-checked:bg-blue-50 peer-checked:text-electric transition-all hover:border-gray-300">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Reference & Description --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Additional Information</h3>
                <div class="space-y-6">
                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference / Transaction ID</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" placeholder="e.g. MPesa receipt number"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                        @error('reference') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="What was this expense for?"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1">Receipt Upload</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-electric transition-colors">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <p class="text-sm text-gray-500">Click to upload or drag & drop</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, PDF up to 5MB</p>
                            <input type="file" name="receipt" id="receipt" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('receipt') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Recurring --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recurring Expense</h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_recurring" value="1" x-model="isRecurring" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-electric/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-electric"></div>
                    </label>
                </div>
                <div x-show="isRecurring" x-cloak x-transition class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="recurring_frequency" class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                        <select name="recurring_frequency" id="recurring_frequency" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric bg-white">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @error('recurring_frequency') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric">
                        @error('recurring_end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-electric rounded-lg hover:bg-blue-600 transition-colors">
                    Save Expense
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
