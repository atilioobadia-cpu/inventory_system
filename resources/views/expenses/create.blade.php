@extends('layouts.app')

@section('title', 'Add Expense - Mtokoma')

@section('header-title', 'Add Expense')

@section('breadcrumbs')
    <span class="mx-2 text-muted">/</span>
    <a href="{{ route('expenses.index') }}" class="hover:text-accent transition-colors">Expenses</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-body font-medium">Add Expense</span>
@endsection

@section('content')
<div x-data="{ isRecurring: {{ old('is_recurring') ? 'true' : 'false' }} }">
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-heading">Add Expense</h1>
                <div class="flex gap-3">
                    <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">Cancel</a>
                </div>
            </div>

            {{-- Basic Details --}}
            <div class="bg-card-bg rounded-lg border border-border p-5">
                <h3 class="text-lg font-semibold text-heading mb-6">Expense Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="expense_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="expense_category_id" id="expense_category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="amount" class="form-label flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Amount (TZS) <span class="text-danger">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted text-sm font-medium">TZS</span>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                                   class="pl-14 pr-4"
                                   placeholder="0.00">
                        </div>
                        @error('amount') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="expense_date" class="form-label flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required>
                        @error('expense_date') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
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
                                    <div class="border-2 border-border rounded-lg px-3 py-2 text-center text-xs font-medium peer-checked:border-accent peer-checked:bg-accent-light peer-checked:text-accent transition-all hover:border-border">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Reference & Description --}}
            <div class="bg-card-bg rounded-lg border border-border p-5">
                <h3 class="text-lg font-semibold text-heading mb-6">Additional Information</h3>
                <div class="space-y-6">
                    <div>
                        <label for="reference" class="form-label">Reference / Transaction ID</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" placeholder="e.g. MPesa receipt number">
                        @error('reference') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="What was this expense for?">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="receipt" class="form-label">Receipt Upload</label>
                        <div class="relative border-2 border-dashed border-border rounded-lg p-6 text-center hover:border-accent transition-colors">
                            <svg class="w-8 h-8 text-muted mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <p class="text-sm text-muted">Click to upload or drag & drop</p>
                            <p class="text-xs text-muted mt-1">PNG, JPG, PDF up to 5MB</p>
                            <input type="file" name="receipt" id="receipt" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('receipt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Recurring --}}
            <div class="bg-card-bg rounded-lg border border-border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-heading">Recurring Expense</h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_recurring" value="1" x-model="isRecurring" class="sr-only peer">
                        <div class="w-11 h-6 bg-control-bg peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
                <div x-show="isRecurring" x-cloak x-transition class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="recurring_frequency" class="form-label">Frequency</label>
                        <select name="recurring_frequency" id="recurring_frequency">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @error('recurring_frequency') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="recurring_end_date" class="form-label">End Date</label>
                        <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date') }}">
                        @error('recurring_end_date') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-hover transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Save Expense
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
