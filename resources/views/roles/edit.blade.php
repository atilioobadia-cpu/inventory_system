@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Role: {{ $role->name }}</h1>
            <p class="text-slate-500 mt-1">Update role name, description, and permissions</p>
        </div>
        <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back
        </a>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST" x-data="roleForm()">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" x-model="name" @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" x-model="slug" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm bg-slate-50" readonly>
                </div>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('description', $role->description) }}</textarea>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Permissions</h2>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="allSelected" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Select All</span>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $modules = [
                        'dashboard' => ['view'],
                        'items' => ['view', 'create', 'edit', 'delete'],
                        'categories' => ['view', 'create', 'edit', 'delete'],
                        'suppliers' => ['view', 'create', 'edit', 'delete'],
                        'customers' => ['view', 'create', 'edit', 'delete'],
                        'purchases' => ['view', 'create', 'edit', 'delete'],
                        'sales' => ['view', 'create', 'edit', 'delete'],
                        'pos' => ['view', 'create', 'void'],
                        'stock' => ['view', 'adjust', 'reconcile'],
                        'reconciliations' => ['view', 'create', 'approve'],
                        'expenses' => ['view', 'create', 'edit', 'delete'],
                        'expense_categories' => ['view', 'create', 'edit', 'delete'],
                        'reports' => ['view', 'export'],
                        'import_export' => ['import', 'export'],
                        'roles' => ['view', 'create', 'edit', 'delete'],
                        'users' => ['view', 'create', 'edit', 'delete'],
                        'settings' => ['view', 'edit'],
                        'activity' => ['view'],
                    ];
                @endphp
                @foreach($modules as $module => $actions)
                    <div class="border border-slate-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-medium text-slate-700 capitalize">{{ str_replace('_', ' ', $module) }}</h3>
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="checkbox" @change="toggleModule('{{ $module }}', $event.target.checked)" :checked="isModuleSelected('{{ $module }}')" class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-xs text-slate-500">All</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            @foreach($actions as $action)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $module }}.{{ $action }}" x-model="permissions" class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-600 capitalize">{{ $action }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('roles.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">Update Role</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function roleForm() {
    const allPerms = {!! json_encode(collect($modules ?? [])->flatMap(function($actions, $module) { return collect($actions)->map(fn($a) => $module.'.'.$a); })->values()->toArray()) !!};
    return {
        name: '{{ old("name", $role->name) }}',
        slug: '{{ old("slug", $role->slug) }}',
        permissions: {!! json_encode(old('permissions', $role->permissions->pluck('name')->toArray())) !!},
        get allSelected() { return this.permissions.length === allPerms.length; },
        toggleAll(checked) { this.permissions = checked ? [...allPerms] : []; },
        toggleModule(module, checked) {
            const modulePerms = allPerms.filter(p => p.startsWith(module + '.'));
            if (checked) {
                this.permissions = [...new Set([...this.permissions, ...modulePerms])];
            } else {
                this.permissions = this.permissions.filter(p => !p.startsWith(module + '.'));
            }
        },
        isModuleSelected(module) {
            const modulePerms = allPerms.filter(p => p.startsWith(module + '.'));
            return modulePerms.every(p => this.permissions.includes(p));
        }
    };
}
</script>
@endpush
@endsection