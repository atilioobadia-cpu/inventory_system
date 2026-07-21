@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Activity Log</h1>
            <p class="text-gray-500 mt-1">Track all system actions and user activities</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('activity.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                <select name="user_id" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm min-w-[180px]">
                    <option value="">All Users</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select name="action" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm min-w-[160px]">
                    <option value="">All Actions</option>
                    <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="view" {{ request('action') === 'view' ? 'selected' : '' }}>View</option>
                    <option value="export" {{ request('action') === 'export' ? 'selected' : '' }}>Export</option>
                    <option value="import" {{ request('action') === 'import' ? 'selected' : '' }}>Import</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg focus:ring-tz-green/20 focus:border-tz-green text-sm">
            </div>
            <button type="submit" class="px-6 py-2 bg-tz-green text-white rounded-lg hover:bg-tz-green-dark transition-colors text-sm font-medium">Filter</button>
            <a href="{{ route('activity.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">Reset</a>
        </form>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="space-y-0">
            @forelse($activities ?? [] as $activity)
                <div class="relative flex gap-4 pb-8 last:pb-0">
                    <!-- Timeline line -->
                    @if(!$loop->last)
                        <div class="absolute left-4 top-10 w-0.5 h-full bg-gray-200 -ml-0.5"></div>
                    @endif

                    <!-- Avatar -->
                    <div class="relative z-10 flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center border-2 border-white">
                            @if($activity->user && $activity->user->avatar)
                                <img src="{{ asset('storage/' . $activity->user->avatar) }}" alt="" class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-xs font-medium text-gray-600">{{ substr($activity->user->name ?? '?', 0, 1) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-medium text-gray-800">{{ $activity->user->name ?? 'System' }}</span>
                                    @php
                                        $actionColors = [
                                            'create' => 'bg-green-100 text-green-700',
                                            'update' => 'bg-tz-green-light text-tz-green',
                                            'delete' => 'bg-red-100 text-red-700',
                                            'login' => 'bg-gray-100 text-gray-700',
                                            'logout' => 'bg-gray-100 text-gray-700',
                                            'view' => 'bg-cyan-100 text-cyan-700',
                                            'export' => 'bg-purple-100 text-purple-700',
                                            'import' => 'bg-amber-100 text-amber-700',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $actionColors[$activity->action] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($activity->action) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                @if($activity->subject)
                                    <div class="mt-1">
                                        <span class="text-xs text-gray-400">{{ class_basename($activity->subject_type) }}</span>
                                        @if(method_exists($activity->subject, 'getTable'))
                                            <a href="#" class="text-xs text-tz-green hover:underline ml-1">#{{ $activity->subject_id }}</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-gray-400 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</p>
                                @if($activity->ip_address)
                                    <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $activity->ip_address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-gray-500">No activity found matching your filters.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if(isset($activities) && $activities->hasPages())
        <div class="flex justify-center">
            {{ $activities->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection