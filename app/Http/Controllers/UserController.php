<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Optional: If hashing password elsewhere

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $perPage = (int) ($request->get('per_page') ?? 10); // Cast to int; use get() for safety
        $users = User::with('roles')->paginate($perPage);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email', // Fixed unique for create
            'phone' => 'required|string|digits:10', // Added digits rule (assumes numeric phone)
            'role' => 'required|exists:roles,name',
        ]);

        $password = Hash::make($request['phone']);

        $role = $validated['role']; // No need for isset—validation ensures it's there
        $userData = $request->except('role'); // Or use $validated directly
        $userData = array_merge($validated, ['password' => $password]); // Properly add password
        $user = User::create($userData);
        $user->assignRole($role);

        return redirect()
            ->route('users.index') // Fixed: Use route name
            ->with('success', 'User created successfully!'); // Flash message for view
    }

    public function show(User $user): View
    {
        $user->load('roles');

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|digits:10',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update($request->except('role'));

        if (isset($request['role'])) {
            $user->syncRoles([$request['role']]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }
}
