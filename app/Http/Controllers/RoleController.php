<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'is_system' => false,
            ]);

            if (!empty($validated['permissions'])) {
                $permissionIds = Permission::whereIn('slug', $validated['permissions'])->pluck('id')->toArray();
                $role->permissions()->sync($permissionIds);
            }

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_role',
                subject: $role,
                description: "Created role: {$role->name}",
                newValues: $role->toArray()
            );

            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        return view('roles.show', compact('role', 'permissions'));
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $role->toArray();
            $role->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
            ]);

            $permissionIds = Permission::whereIn('slug', $validated['permissions'] ?? [])->pluck('id')->toArray();
            $role->permissions()->sync($permissionIds);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_role',
                subject: $role,
                description: "Updated role: {$role->name}",
                oldValues: $oldValues,
                newValues: $role->toArray()
            );

            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'Cannot delete system roles.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role with assigned users. Reassign users first.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_role',
                subject: $role,
                description: "Deleted role: {$role->name}",
                oldValues: $role->toArray()
            );

            $role->permissions()->detach();
            $role->delete();
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}
