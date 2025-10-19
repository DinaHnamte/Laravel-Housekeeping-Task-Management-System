<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
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

        // Only admins can manage permissions
        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to permission management.');
        }

        $permissions = Permission::with('roles')->get()->groupBy(function ($permission) {
            $parts = explode(':', $permission->name);
            return $parts[0] ?? 'Other';
        });

        return view('permissions.index', compact('permissions'));
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
            abort(403, 'Unauthorized access to permission management.');
        }

        return view('permissions.create');
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
            abort(403, 'Unauthorized access to permission management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web'
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to permission management.');
        }

        $permission->load('roles');
        $roles = Role::all();

        return view('permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to permission management.');
        }

        $roles = Role::all();
        $permission->load('roles');

        return view('permissions.edit', compact('permission', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to permission management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update(['name' => $validated['name']]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to permission management.');
        }

        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()
                ->route('permissions.index')
                ->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Assign permission to roles
     */
    public function assignToRoles(Request $request, Permission $permission): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (!$user->hasRole('Admin')) {
            abort(403, 'Unauthorized access to permission management.');
        }

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roles = isset($validated['roles']) ? Role::whereIn('id', $validated['roles'])->get() : collect();
        $permission->syncRoles($roles);

        return redirect()
            ->route('permissions.show', $permission)
            ->with('success', 'Permission roles updated successfully.');
    }
}
