@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-heading">Edit Role: {{ $role->name }}</h1>
            <p class="text-muted mt-1">Update role name, description, and permissions</p>
        </div>
        <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-control-bg text-body rounded-lg hover:bg-control-bg transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back
        </a>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST" x-data="roleForm()">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl border border-border p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" x-model="name" @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')" required>
                    @error('name') <p class="mt-1 text-sm text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" x-model="slug" readonly>
                </div>
            </div>
            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3">{{ old('description', $role->description) }}</textarea>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-xl border border-border overflow-hidden">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h2 class="text-lg font-semibold text-heading">Permissions</h2>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="allSelected" class="w-4 h-4 rounded border-border text-accent focus:ring-accent/20">
                    <span class="text-sm font-medium text-body">Select All</span>
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
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-medium text-body capitalize">{{ str_replace('_', ' ', $module) }}</h3>
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="checkbox" @change="toggleModule('{{ $module }}', $event.target.checked)" :checked="isModuleSelected('{{ $module }}')" class="w-3.5 h-3.5 rounded border-border text-accent focus:ring-accent/20">
                                <span class="text-xs text-muted">All</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            @foreach($actions as $action)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $module }}.{{ $action }}" x-model="permissions" class="w-3.5 h-3.5 rounded border-border text-accent focus:ring-accent/20">
                                    <span class="text-sm text-body capitalize">{{ $action }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('roles.index') }}" class="px-6 py-2 bg-control-bg text-body rounded-lg hover:bg-control-bg transition-colors text-sm font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Update Role</button>
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