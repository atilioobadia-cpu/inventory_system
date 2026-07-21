@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="space-y-4" x-data="{ showDeleteModal: false, deleteId: null }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="page-title flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                Users
            </h1>
            <p class="text-gray-500 mt-1">Manage system users and their roles</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
            Add User
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">User</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Email</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Role</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Phone</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Status</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Last Login</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($users ?? [] as $user)
                        <tr class="hover:bg-white">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-sm font-medium text-primary">{{ substr($user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 text-primary rounded-full text-xs font-medium">{{ $user->role->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $user->is_active ? 'bg-success-light0' : 'bg-white' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ $user->is_active ? 'translate-x-4.5' : 'translate-x-0.5' }}" style="transform: translateX({{ $user->is_active ? '18px' : '2px' }})"></span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 text-gray-500 hover:text-warning hover:bg-warning-light rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-500/50 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                No users found. Create your first user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($users) && $users->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
