@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title">Role: {{ $role->name }}</h1>
            <p class="text-gray-500 mt-1">{{ $role->description ?? 'No description provided' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if(!$role->is_system)
                <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    Edit
                </a>
            @endif
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </div>
    </div>

    <!-- Role Info -->
    <div class="card card-body">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="font-medium text-gray-900">{{ $role->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Slug</p>
                <p class="font-medium text-gray-900 font-mono">{{ $role->slug }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Type</p>
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
    <div class="card overflow-hidden">
        <div class="p-5 border-b border-border">
            <h2 class="text-lg font-semibold text-gray-900">Permissions ({{ $role->permissions->count() }})</h2>
        </div>
        <div class="p-5">
            @php
                $grouped = $role->permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($grouped as $module => $perms)
                    <div class="border border-border rounded-lg p-4">
                        <h3 class="font-medium text-gray-700 capitalize mb-3">{{ str_replace('_', ' ', $module) }}</h3>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span class="text-sm text-gray-700">{{ explode('.', $perm->name)[1] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Users with this Role -->
    <div class="card overflow-hidden">
        <div class="p-5 border-b border-border">
            <h2 class="text-lg font-semibold text-gray-900">Users with this Role ({{ $role->users->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">User</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Email</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($role->users as $user)
                        <tr class="hover:bg-white">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center overflow-hidden">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-danger-light text-danger rounded-full text-xs font-medium">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">No users assigned to this role.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
