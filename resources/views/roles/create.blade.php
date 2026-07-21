@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title">Create Role</h1>
            <p class="text-gray-500 mt-1">Define a new role with specific permissions</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back
        </a>
    </div>

    <form action="{{ route('roles.store') }}" method="POST" x-data="roleForm()">
        @csrf
        <div class="card card-body space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" x-model="name" @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')" required>
                    @error('name') <p class="mt-1 text-sm text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" x-model="slug" readonly>
                </div>
            </div>
            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Brief description of this role...">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Permissions -->
        <div class="mt-6 card overflow-hidden">
            <div class="p-5 border-b border-border flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Permissions</h2>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="allSelected" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20">
                    <span class="text-sm font-medium text-gray-700">Select All</span>
                </label>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
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
                            <h3 class="font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $module) }}</h3>
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="checkbox" @change="toggleModule('{{ $module }}', $event.target.checked)" :checked="isModuleSelected('{{ $module }}')" class="w-3.5 h-3.5 rounded border-border text-primary focus:ring-primary/20">
                                <span class="text-xs text-gray-500">All</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            @foreach($actions as $action)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $module }}.{{ $action }}" x-model="permissions" class="w-3.5 h-3.5 rounded border-border text-primary focus:ring-primary/20">
                                    <span class="text-sm text-gray-700 capitalize">{{ $action }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Role</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function roleForm() {
    const allPerms = {!! json_encode(collect($modules ?? [])->flatMap(function($actions, $module) { return collect($actions)->map(fn($a) => $module.'.'.$a); })->values()->toArray()) !!};
    const moduleKeys = {!! json_encode(array_keys($modules ?? [])) !!};
    return {
        name: '{{ old("name") }}',
        slug: '{{ old("slug") }}',
        permissions: {!! json_encode(old('permissions', [])) !!},
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
