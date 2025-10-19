<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        // Only admins can manage roles
        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        $roles = Role::with('permissions')->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode(':', $permission->name);
            return $parts[0] ?? 'Other';
        });

        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        $role->load('permissions');
        $users = $role->users;

        return view('roles.show', compact('role', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode(':', $permission->name);
            return $parts[0] ?? 'Other';
        });

        $role->load('permissions');

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to role management.');
        }

        // Prevent deletion of default roles
        if (in_array($role->name, ['Admin', 'Owner', 'Housekeeper'])) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Cannot delete default system roles.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Cannot delete role that is assigned to users.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
