@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Reports</h1>
            <p class="text-muted mt-1">View and generate business reports</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-accent-light rounded-lg group-hover:bg-accent-light transition-colors">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-accent transition-colors">Sales Report</h3>
                    <p class="text-sm text-muted">View sales analytics and trends</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-accent font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Purchase Report -->
        <a href="{{ route('reports.purchases') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-indigo-600 transition-colors">Purchase Report</h3>
                    <p class="text-sm text-muted">Track purchases and suppliers</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-indigo-600 font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Inventory Report -->
        <a href="{{ route('reports.inventory') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-teal-100 rounded-lg group-hover:bg-teal-200 transition-colors">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-teal-600 transition-colors">Inventory Report</h3>
                    <p class="text-sm text-muted">Stock levels and valuations</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-teal-600 font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Expense Report -->
        <a href="{{ route('reports.expenses') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-orange-100 rounded-lg group-hover:bg-orange-200 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-orange-600 transition-colors">Expense Report</h3>
                    <p class="text-sm text-muted">Track and analyze expenses</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-orange-600 font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Profit & Loss -->
        <a href="{{ route('reports.profit-loss') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-accent-light rounded-lg group-hover:bg-success-light transition-colors">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-accent transition-colors">Profit & Loss</h3>
                    <p class="text-sm text-muted">Revenue, costs and profitability</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-accent font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Tax Report -->
        <a href="{{ route('reports.tax') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-purple-600 transition-colors">Tax Report</h3>
                    <p class="text-sm text-muted">VAT and tax summaries</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-purple-600 font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Customer Report -->
        <a href="{{ route('reports.customers') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-cyan-100 rounded-lg group-hover:bg-cyan-200 transition-colors">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-cyan-600 transition-colors">Customer Report</h3>
                    <p class="text-sm text-muted">Customer sales and balances</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-cyan-600 font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        <!-- Supplier Report -->
        <a href="{{ route('reports.suppliers') }}" class="group bg-card-bg rounded-lg border border-border p-4 hover:transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-warning-light rounded-lg group-hover:bg-amber-200 transition-colors">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-heading group-hover:text-warning transition-colors">Supplier Report</h3>
                    <p class="text-sm text-muted">Supplier purchases and balances</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-warning font-medium">
                View Report
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>
    </div>
</div>
@endsection