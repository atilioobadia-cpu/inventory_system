@extends('layouts.app')

@section('title', 'Create Reconciliation - Mtokoma')

@section('header-title', 'Create Reconciliation')

@section('breadcrumbs')
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('reconciliations.index') }}" class="hover:text-tz-green transition-colors">Reconciliations</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-700 font-medium">Create</span>
@endsection

@section('content')
<div x-data="{
    type: 'daily',
    dateFrom: '{{ now()->startOfDay()->format('Y-m-d') }}',
    dateTo: '{{ now()->endOfDay()->format('Y-m-d') }}',
    expectedSales: {{ $expectedSales ?? 0 }},
    expectedExpenses: {{ $expectedExpenses ?? 0 }},
    expectedPurchases: {{ $expectedPurchases ?? 0 }},
    actualCash: 0,
    get expectedCash() {
        return this.expectedSales - this.expectedExpenses - this.expectedPurchases;
    },
    get difference() {
        return this.actualCash - this.expectedCash;
    },
    init() {
        this.$watch('type', (val) => {
            const today = new Date();
            if (val === 'daily') {
                this.dateFrom = today.toISOString().split('T')[0];
                this.dateTo = today.toISOString().split('T')[0];
            } else if (val === 'weekly') {
                const start = new Date(today);
                start.setDate(today.getDate() - today.getDay() + 1);
                this.dateFrom = start.toISOString().split('T')[0];
                this.dateTo = today.toISOString().split('T')[0];
            } else if (val === 'monthly') {
                const start = new Date(today.getFullYear(), today.getMonth(), 1);
                this.dateFrom = start.toISOString().split('T')[0];
                this.dateTo = today.toISOString().split('T')[0];
            }
        });
        this.$watch('dateFrom', () => this.fetchExpected());
        this.$watch('dateTo', () => this.fetchExpected());
    },
    async fetchExpected() {
        try {
            const params = new URLSearchParams({ date_from: this.dateFrom, date_to: this.dateTo });
            const res = await fetch('{{ route('api.reconciliation.expected') }}?' + params.toString());
            const data = await res.json();
            this.expectedSales = data.sales_total || 0;
            this.expectedExpenses = data.expenses_total || 0;
            this.expectedPurchases = data.purchases_total || 0;
        } catch(e) {}
    }
}">
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('reconciliations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date_from" :value="dateFrom">
            <input type="hidden" name="date_to" :value="dateTo">

            <div class="space-y-6">
                {{-- Reconciliation Type --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reconciliation Period</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="daily" x-model="type" class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-electric peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                <svg class="w-6 h-6 text-tz-green mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span class="text-sm font-medium text-gray-700">Daily</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="weekly" x-model="type" class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-electric peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                <svg class="w-6 h-6 text-tz-green mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 9.75h4M10 14.25h4"/></svg>
                                <span class="text-sm font-medium text-gray-700">Weekly</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="monthly" x-model="type" class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-electric peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                <svg class="w-6 h-6 text-tz-green mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v5.25m0 0H9.75m2.25 0H15"/></svg>
                                <span class="text-sm font-medium text-gray-700">Monthly</span>
                            </div>
                        </label>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                            <input type="date" name="date_from_display" x-model="dateFrom" readonly class="cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                            <input type="date" name="date_to_display" x-model="dateTo" readonly class="cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Expected Cash Breakdown --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Expected Cash (Auto-calculated)</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 012.828 0l1.414 1.42.879-.659M12 18V6m0 12h4.5m-4.5 0a2 2 0 01-1.732-1M12 18h4.5m-4.5 0a2 2 0 001.732-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Total Sales</span>
                            </div>
                            <span class="text-sm font-semibold text-green-600">+ TZS <span x-text="Number(expectedSales).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 012.828 0l1.414 1.42.879-.659M12 18V6m0 12H7.5m4.5 0h4.5"/></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Less Total Expenses</span>
                            </div>
                            <span class="text-sm font-semibold text-red-600">- TZS <span x-text="Number(expectedExpenses).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.183A1.125 1.125 0 0018.056 3H5.106m2.394 11.25L7.5 14.25m0 0h9.75m-9.75 0L6.375 3M20.25 7.5H21"/></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Less Total Purchases</span>
                            </div>
                            <span class="text-sm font-semibold text-amber-600">- TZS <span x-text="Number(expectedPurchases).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-base font-bold text-gray-900">Expected Cash</span>
                            <span class="text-base font-bold text-tz-green">TZS <span x-text="Number(expectedCash).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                        </div>
                    </div>
                </div>

                {{-- Actual Cash & Difference --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cash Count</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="actual_cash" class="form-label">Actual Cash Count (TZS) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">TZS</span>
                                <input type="number" name="actual_cash" x-model.number="actualCash" step="0.01" min="0" required
                                       class="pl-14 pr-4 text-lg font-semibold"
                                       placeholder="0.00">
                            </div>
                            @error('actual_cash') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="rounded-lg p-4" :class="difference != 0 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <template x-if="difference != 0">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                        </svg>
                                    </template>
                                    <template x-if="difference == 0 && actualCash > 0">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </template>
                                    <span class="text-sm font-medium" :class="difference != 0 ? 'text-red-800' : 'text-green-800'">Difference</span>
                                </div>
                                <span class="text-lg font-bold" :class="difference != 0 ? 'text-red-600' : 'text-green-600'">
                                    <span x-text="(difference >= 0 ? '+' : '') + 'TZS ' + Number(difference).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                    <textarea name="notes" rows="3" placeholder="Optional notes about this reconciliation..."></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('reconciliations.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-tz-green rounded-lg hover:bg-tz-green-dark transition-colors">
                        Submit Reconciliation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
