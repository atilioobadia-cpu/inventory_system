@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
                Roles & Permissions
            </h1>
            <p class="text-gray-500 mt-1">Manage user roles and their permissions</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Role
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($roles ?? [] as $role)
            <div class="card card-body transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                            <p class="text-xs text-gray-500 font-mono">{{ $role->slug }}</p>
                        </div>
                    </div>
                    @if($role->is_system)
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-warning-light text-warning rounded-full text-xs font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            System
                        </span>
                    @else
                        <span class="px-2 py-1 bg-success-light text-success rounded-full text-xs font-medium">Custom</span>
                    @endif
                </div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-2 py-1 bg-gray-100 text-primary rounded-full text-xs font-medium">{{ $role->permissions_count ?? $role->permissions->count() }} permissions</span>
                    <span class="px-2 py-1 bg-white text-gray-700 rounded-full text-xs font-medium">{{ $role->users_count ?? $role->users->count() }} users</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-border">
                    <a href="{{ route('roles.show', $role) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-sm text-gray-500 hover:text-primary hover:bg-gray-100 rounded-lg transition-colors" title="View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        View
                    </a>
                    @if(!$role->is_system)
                        <a href="{{ route('roles.edit', $role) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-sm text-gray-500 hover:text-warning hover:bg-warning-light rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-sm text-gray-500 hover:text-danger hover:bg-danger-light rounded-lg transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <svg class="w-16 h-16 text-gray-500/50 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                <p class="text-gray-500 font-medium">No roles found</p>
                <p class="text-sm text-gray-500 mt-1">Create your first role to get started.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
