@extends('layouts.app')

@section('title', 'Import & Export')

@section('content')
<div class="space-y-8" x-data="{ importModal: false, importType: '', importing: false, importResult: null }">
    <div>
        <h1 class="text-xl font-bold text-heading">Import & Export</h1>
        <p class="text-muted mt-1">Bulk import data from CSV files or export your data</p>
    </div>

    <!-- Import Section -->
    <div>
        <h2 class="text-lg font-semibold text-heading mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Import Data
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Items -->
            <div class="bg-card-bg rounded-lg border border-border p-5 hover:transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-accent-light rounded-lg">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-heading">Items</h3>
                        <p class="text-xs text-muted">Products and parts</p>
                    </div>
                </div>
                <button @click="importType = 'items'; importModal = true; importResult = null" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Import</button>
            </div>

            <!-- Categories -->
            <div class="bg-card-bg rounded-lg border border-border p-5 hover:transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-accent-light rounded-lg">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-heading">Categories</h3>
                        <p class="text-xs text-muted">Product categories</p>
                    </div>
                </div>
                <button @click="importType = 'categories'; importModal = true; importResult = null" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Import</button>
            </div>

            <!-- Customers -->
            <div class="bg-card-bg rounded-lg border border-border p-5 hover:transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-cyan-100 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-heading">Customers</h3>
                        <p class="text-xs text-muted">Customer records</p>
                    </div>
                </div>
                <button @click="importType = 'customers'; importModal = true; importResult = null" class="w-full px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">Import</button>
            </div>

            <!-- Suppliers -->
            <div class="bg-card-bg rounded-lg border border-border p-5 hover:transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-warning-light rounded-lg">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-heading">Suppliers</h3>
                        <p class="text-xs text-muted">Supplier records</p>
                    </div>
                </div>
                <button @click="importType = 'suppliers'; importModal = true; importResult = null" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium">Import</button>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div>
        <h2 class="text-lg font-semibold text-heading mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Export Data
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $exports = [
                    ['name' => 'Items', 'route' => 'import-export.export', 'type' => 'items', 'icon' => 'box', 'color' => 'blue'],
                    ['name' => 'Categories', 'route' => 'import-export.export', 'type' => 'categories', 'icon' => 'tag', 'color' => 'green'],
                    ['name' => 'Customers', 'route' => 'import-export.export', 'type' => 'customers', 'icon' => 'users', 'color' => 'cyan'],
                    ['name' => 'Suppliers', 'route' => 'import-export.export', 'type' => 'suppliers', 'icon' => 'truck', 'color' => 'amber'],
                    ['name' => 'Sales', 'route' => 'import-export.export', 'type' => 'sales', 'icon' => 'banknotes', 'color' => 'blue'],
                    ['name' => 'Purchases', 'route' => 'import-export.export', 'type' => 'purchases', 'icon' => 'shopping-cart', 'color' => 'indigo'],
                    ['name' => 'Stock', 'route' => 'import-export.export', 'type' => 'stock', 'icon' => 'archive', 'color' => 'teal'],
                    ['name' => 'Expenses', 'route' => 'import-export.export', 'type' => 'expenses', 'icon' => 'currency-dollar', 'color' => 'orange'],
                ];
            @endphp
            @foreach($exports as $export)
                <div class="bg-card-bg rounded-lg border border-border p-5 hover:transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-{{ $export['color'] }}-100 rounded-lg">
                            @if($export['icon'] === 'box')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                            @elseif($export['icon'] === 'tag')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                            @elseif($export['icon'] === 'users')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            @elseif($export['icon'] === 'truck')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                            @elseif($export['icon'] === 'banknotes')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                            @elseif($export['icon'] === 'shopping-cart')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            @elseif($export['icon'] === 'archive')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                            @elseif($export['icon'] === 'currency-dollar')
                                <svg class="w-5 h-5 text-{{ $export['color'] }}-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <h3 class="font-semibold text-heading">{{ $export['name'] }}</h3>
                    </div>
                    <a href="{{ route($export['route'], ['type' => $export['type']]) }}" class="block w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium text-center">
                        <span class="flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Export CSV
                        </span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="importModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-card-bg0 bg-opacity-75" @click="importModal = false"></div>
            <div class="relative bg-card-bg rounded-lg max-w-lg w-full p-5" @click.stop>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-heading capitalize">Import <span x-text="importType"></span></h3>
                    <button @click="importModal = false" class="p-1 text-muted hover:text-body rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Success Results -->
                <div x-show="importResult && importResult.success" class="mb-4 p-4 bg-success-light border border-success rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-medium text-success" x-text="importResult.message"></span>
                    </div>
                </div>

                <!-- Error Results -->
                <div x-show="importResult && !importResult.success" class="mb-4 p-4 bg-danger-light border border-danger rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        <span class="text-sm font-medium text-danger" x-text="importResult.message"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Download Template -->
                    <div class="p-4 bg-card-bg rounded-lg">
                        <p class="text-sm text-body mb-2">1. Download the template CSV file:</p>
                        <span class="inline-flex items-center gap-2 text-sm text-muted font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Prepare a CSV with the required columns
                        </span>
                    </div>

                    <!-- Upload -->
                    <form :action="'{{ url('/import-export/import') }}/' + importType" method="POST" enctype="multipart/form-data" @submit.prevent="submitImport($el)">
                        @csrf
                        <input type="hidden" name="type" :value="importType">
                        <p class="text-sm text-body mb-2">2. Select your completed CSV or Excel file:</p>
                        <input type="file" name="file" accept=".csv,.txt" required class="w-full text-sm text-body file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-accent-light file:text-accent hover:file:bg-accent-light mb-4">
                        <button type="submit" :disabled="importing" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium flex items-center justify-center gap-2 disabled:opacity-50">
                            <svg x-show="importing" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            <span x-text="importing ? 'Importing...' : 'Import'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function submitImport(form) {
    this.importing = true;
    this.importResult = null;
    const formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        this.importResult = data;
        this.importing = false;
    })
    .catch(err => {
        this.importResult = { success: false, message: 'An error occurred during import.' };
        this.importing = false;
    });
}
</script>
@endpush
@endsection