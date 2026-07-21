@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-heading">Role: {{ $role->name }}</h1>
            <p class="text-muted mt-1">{{ $role->description ?? 'No description provided' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if(!$role->is_system)
                <a href="{{ route('roles.edit', $role) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    Edit
                </a>
            @endif
            <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-control-bg text-gray-600 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </div>
    </div>

    <!-- Role Info -->
    <div class="bg-white rounded-xl border border-border p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-muted">Name</p>
                <p class="font-medium text-heading">{{ $role->name }}</p>
            </div>
            <div>
                <p class="text-sm text-muted">Slug</p>
                <p class="font-medium text-heading font-mono">{{ $role->slug }}</p>
            </div>
            <div>
                <p class="text-sm text-muted">Type</p>
                @if($role->is_system)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-warning-light text-warning rounded-full text-xs font-medium">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        System Role
                    </span>
                @else
                    <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Custom Role</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Permissions by Module -->
    <div class="bg-white rounded-xl border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Permissions ({{ $role->permissions->count() }})</h2>
        </div>
        <div class="p-6">
            @php
                $grouped = $role->permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($grouped as $module => $perms)
                    <div class="border border-border rounded-lg p-4">
                        <h3 class="font-medium text-body capitalize mb-3">{{ str_replace('_', ' ', $module) }}</h3>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span class="text-sm text-gray-600">{{ explode('.', $perm->name)[1] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Users with this Role -->
    <div class="bg-white rounded-xl border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-heading">Users with this Role ({{ $role->users->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">User</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Email</th>
                        <th class="text-center px-6 py-3 font-medium text-muted">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($role->users as $user)
                        <tr class="hover:bg-card-bg">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-control-bg rounded-full flex items-center justify-center overflow-hidden">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-sm font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="font-medium text-heading">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-muted">No users assigned to this role.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection