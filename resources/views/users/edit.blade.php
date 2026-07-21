@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-heading">Edit User: {{ $user->name }}</h1>
            <p class="text-muted mt-1">Update user information and role</p>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-control-bg text-body rounded-lg hover:bg-control-bg transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back
        </a>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" x-data="userForm()">
        @csrf
        @method('PUT')
        <div class="bg-card-bg rounded-lg border border-border p-5 space-y-5">
            <!-- Avatar -->
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-control-bg flex items-center justify-center overflow-hidden border-2 border-dashed border-border">
                    <img x-show="preview || existingAvatar" :src="preview || existingAvatar" class="w-full h-full object-cover">
                    <svg x-show="!preview && !existingAvatar" class="w-8 h-8 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <div>
                    <label class="form-label">Profile Photo</label>
                    <input type="file" name="avatar" accept="image/*" @change="handlePreview($event)" class="text-sm text-body file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-accent-light file:text-accent hover:file:bg-accent-light">
                    @if($user->avatar)
                        <label class="flex items-center gap-2 mt-1 cursor-pointer">
                            <input type="checkbox" name="remove_avatar" value="1" class="w-3.5 h-3.5 rounded border-border text-danger focus:ring-red-500">
                            <span class="text-xs text-muted">Remove avatar</span>
                        </label>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="mt-1 text-sm text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="mt-1 text-sm text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                <div>
                    <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role_id" id="role_id" required>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Password Section -->
            <div class="border-t border-border pt-6">
                <h3 class="text-sm font-semibold text-body mb-4">Change Password (leave blank to keep current)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" id="password">
                        @error('password') <p class="mt-1 text-sm text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>
            </div>

            <!-- Active Toggle -->
            <div class="flex items-center justify-between p-4 bg-card-bg rounded-lg">
                <div>
                    <p class="font-medium text-heading">Active Account</p>
                    <p class="text-sm text-muted">User can log in when enabled</p>
                </div>
                <button type="button" @click="active = !active" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="active ? 'bg-success-light0' : 'bg-control-bg'">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :style="active ? 'transform: translateX(22px)' : 'transform: translateX(2px)'"></span>
                </button>
                <input type="hidden" name="is_active" :value="active ? '1' : '0'">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-6 py-2 bg-control-bg text-body rounded-lg hover:bg-control-bg transition-colors text-sm font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Update User</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function userForm() {
    return {
        preview: null,
        existingAvatar: {{ $user->avatar ? "'" . asset('storage/' . $user->avatar) . "'" : 'null' }},
        active: {{ old('is_active', $user->is_active) ? 'true' : 'false' }},
        handlePreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.preview = URL.createObjectURL(file);
            }
        }
    };
}
</script>
@endpush
@endsection