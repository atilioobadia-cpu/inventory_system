<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function __construct()
    {
    }

    public function hasPermission(User $user, string $permission): bool
    {
        if ($user->role && $user->role->slug === 'super-admin') {
            return true;
        }

        return $user->role
            ?->permissions()
            ->where('slug', $permission)
            ->exists() ?? false;
    }

    public function hasRole(User $user, string $roleSlug): bool
    {
        return $user->role && $user->role->slug === $roleSlug;
    }

    public function giveRole(User $user, Role $role): void
    {
        $user->role_id = $role->id;
        $user->save();
    }

    public function getPermissionsForRole(Role $role): Collection
    {
        return $role->permissions;
    }

    public function syncPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }
}
