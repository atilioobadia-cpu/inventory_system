<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleId = $request->input('role_id')) {
            $query->where('role_id', $roleId);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $users = $query->latest()->paginate(25)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $validated['password'] = Hash::make($validated['password']);

            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::create($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_user',
                subject: $user,
                description: "Created user: {$user->name}",
                newValues: collect($user->toArray())->except('password')->toArray()
            );

            DB::commit();
            return redirect()->route('users.show', $user)->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load('role');

        $recentActivities = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('users.show', compact('user', 'recentActivities'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = collect($user->toArray())->except('password')->toArray();

            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user->update($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_user',
                subject: $user,
                description: "Updated user: {$user->name}",
                oldValues: $oldValues,
                newValues: collect($user->toArray())->except('password')->toArray()
            );

            DB::commit();
            return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_user',
                subject: $user,
                description: "Deleted user: {$user->name}",
                oldValues: collect($user->toArray())->except('password')->toArray()
            );

            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
