@extends('layouts.app')

@section('title', $user->name . ' - User')

@section('header-title', $user->name)

@section('breadcrumbs')
<span class="mx-2 text-gray-500">/</span>
<a href="{{ route('users.index') }}" class="hover:text-primary transition-colors">Users</a>
<span class="mx-2 text-gray-500">/</span>
<span class="text-gray-700 font-medium">{{ $user->name }}</span>
@endsection

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                @else
                <span class="text-xl font-bold text-primary">{{ substr($user->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card card-body">
            <p class="text-sm text-gray-500">Role</p>
            <p class="text-lg font-bold text-gray-900">{{ $user->role->name ?? 'No Role' }}</p>
        </div>
        <div class="card card-body">
            <p class="text-sm text-gray-500">Phone</p>
            <p class="text-lg font-bold text-gray-900">{{ $user->phone ?? 'Not set' }}</p>
        </div>
        <div class="card card-body">
            <p class="text-sm text-gray-500">Last Login</p>
            <p class="text-lg font-bold text-gray-900">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    @if(isset($recentActivities) && $recentActivities->count())
    <div class="card">
        <div class="p-5 border-b border-border">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Action</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Description</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($recentActivities as $activity)
                    <tr class="hover:bg-white">
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-gray-900">{{ ucfirst($activity->action) }}</span>
                        </td>
                        <td class="px-6 py-3 text-gray-900">{{ $activity->description }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
