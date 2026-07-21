@extends('layouts.app')

@section('title', $user->name . ' - User')

@section('header-title', $user->name)

@section('breadcrumbs')
<span class="mx-2 text-muted">/</span>
<a href="{{ route('users.index') }}" class="hover:text-accent transition-colors">Users</a>
<span class="mx-2 text-muted">/</span>
<span class="text-body font-medium">{{ $user->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                @else
                <span class="text-2xl font-bold text-accent">{{ substr($user->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-bold text-heading">{{ $user->name }}</h2>
                <p class="text-sm text-muted">{{ $user->email }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-hover transition-colors">Edit</a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-border p-6">
            <p class="text-sm text-muted">Role</p>
            <p class="text-lg font-bold text-heading">{{ $user->role->name ?? 'No Role' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <p class="text-sm text-muted">Phone</p>
            <p class="text-lg font-bold text-heading">{{ $user->phone ?? 'Not set' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-border p-6">
            <p class="text-sm text-muted">Last Login</p>
            <p class="text-lg font-bold text-heading">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    @if(isset($recentActivities) && $recentActivities->count())
    <div class="bg-white rounded-xl border border-border">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-heading">Recent Activity</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-card-bg">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-muted">Action</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Description</th>
                        <th class="text-left px-6 py-3 font-medium text-muted">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($recentActivities as $activity)
                    <tr class="hover:bg-card-bg">
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-control-bg text-heading">{{ ucfirst($activity->action) }}</span>
                        </td>
                        <td class="px-6 py-3 text-heading">{{ $activity->description }}</td>
                        <td class="px-6 py-3 text-muted">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
