<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\Property;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = auth()->user();
    if ($user && $user->hasRole('Housekeeper')) {
        return redirect()->route('housekeeper.dashboard');
    }
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes - require authentication
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::prefix('users')->middleware('permission:view:users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->middleware('permission:create:users')->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->middleware('permission:create:users')->name('users.store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->middleware('permission:edit:users')->name('users.edit');
        Route::put('/update/{user}', [UserController::class, 'update'])->middleware('permission:edit:users')->name('users.update');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    });

Route::prefix('properties')->middleware('permission:view:properties')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/create', [PropertyController::class, 'create'])->middleware('permission:create:properties')->name('properties.create');
    Route::post('/store', [PropertyController::class, 'store'])->middleware('permission:create:properties')->name('properties.store');

    // Nested routes for rooms - must come before the general {property} route
    Route::prefix('{property}/rooms')->middleware('permission:view:rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('properties.rooms.index');
        Route::get('/create', [RoomController::class, 'create'])->middleware('permission:create:rooms')->name('properties.rooms.create');
        Route::post('/store', [RoomController::class, 'store'])->middleware('permission:create:rooms')->name('properties.rooms.store');
        Route::get('/{room}', [RoomController::class, 'show'])->name('properties.rooms.show');
        Route::get('/edit/{room}', [RoomController::class, 'edit'])->middleware('permission:edit:rooms')->name('properties.rooms.edit');
        Route::put('/update/{room}', [RoomController::class, 'update'])->middleware('permission:edit:rooms')->name('properties.rooms.update');

        // Nested routes for tasks
        Route::prefix('{room}/tasks')->middleware('permission:view:tasks')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('properties.rooms.tasks.index');
            Route::get('/create', [TaskController::class, 'create'])->middleware('permission:create:tasks')->name('properties.rooms.tasks.create');
            Route::post('/store', [TaskController::class, 'store'])->middleware('permission:create:tasks')->name('properties.rooms.tasks.store');
            Route::get('/{task}', [TaskController::class, 'show'])->name('properties.rooms.tasks.show');
            Route::get('/edit/{task}', [TaskController::class, 'edit'])->middleware('permission:edit:tasks')->name('properties.rooms.tasks.edit');
            Route::put('/update/{task}', [TaskController::class, 'update'])->middleware('permission:edit:tasks')->name('properties.rooms.tasks.update');
            Route::delete('/{task}', [TaskController::class, 'destroy'])->middleware('permission:delete:tasks')->name('properties.rooms.tasks.destroy');
        });
    });

    // General property routes - must come after nested routes
    Route::get('/{property}', [PropertyController::class, 'show'])->name('properties.show');
    Route::get('/edit/{property}', [PropertyController::class, 'edit'])->middleware('permission:edit:properties')->name('properties.edit');
    Route::put('/update/{property}', [PropertyController::class, 'update'])->middleware('permission:edit:properties')->name('properties.update');
});

    // Assignments
    Route::prefix('assignments')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/create', [AssignmentController::class, 'create'])->middleware('permission:create:housekeepers')->name('assignments.create');
        Route::post('/store', [AssignmentController::class, 'store'])->middleware('permission:create:housekeepers')->name('assignments.store');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::get('/edit/{assignment}', [AssignmentController::class, 'edit'])->middleware('permission:edit:housekeepers')->name('assignments.edit');
        Route::put('/update/{assignment}', [AssignmentController::class, 'update'])->middleware('permission:edit:housekeepers')->name('assignments.update');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->middleware('permission:delete:housekeepers')->name('assignments.destroy');
    });

    // Housekeeper specific routes
    Route::prefix('housekeeper')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HousekeeperController::class, 'dashboard'])->name('housekeeper.dashboard');
        Route::get('/tasks/{assignment}', [App\Http\Controllers\HousekeeperController::class, 'showTasks'])->name('housekeeper.tasks');
    });


    // Checklists
    Route::prefix('checklists')->middleware('permission:view:checklists')->group(function () {
        Route::get('/', [ChecklistController::class, 'index'])->name('checklists.index');
        Route::get('/create', [ChecklistController::class, 'create'])->middleware('permission:submit:checklists')->name('checklists.create');
        Route::get('/start/{assignment}', [ChecklistController::class, 'startFromAssignment'])->middleware('permission:submit:checklists')->name('checklists.start');
        Route::get('/room/{assignment}/{room}', [ChecklistController::class, 'showRoomTasks'])->middleware('permission:submit:checklists')->name('checklists.room');
        Route::post('/store', [ChecklistController::class, 'store'])->middleware('permission:submit:checklists')->name('checklists.store');
        Route::get('/{checklist}', [ChecklistController::class, 'show'])->name('checklists.show');
        Route::get('/edit/{checklist}', [ChecklistController::class, 'edit'])->middleware('permission:edit:checklists')->name('checklists.edit');
        Route::put('/update/{checklist}', [ChecklistController::class, 'update'])->middleware('permission:edit:checklists')->name('checklists.update');
        Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->middleware('permission:edit:checklists')->name('checklists.destroy');
    });

    // Roles Management (Admin only)
    Route::prefix('roles')->middleware('auth')->group(function () {
        Route::get('/', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::get('/create', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
        Route::post('/', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
        Route::get('/{role}', [App\Http\Controllers\RoleController::class, 'show'])->name('roles.show');
        Route::get('/{role}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/{role}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
        Route::delete('/{role}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Permissions Management (Admin only)
    Route::prefix('permissions')->middleware('auth')->group(function () {
        Route::get('/', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/create', [App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/', [App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/{permission}', [App\Http\Controllers\PermissionController::class, 'show'])->name('permissions.show');
        Route::get('/{permission}/edit', [App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/{permission}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/{permission}', [App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy');
        Route::post('/{permission}/assign-roles', [App\Http\Controllers\PermissionController::class, 'assignToRoles'])->name('permissions.assign-roles');
    });

    // API Routes for AJAX
    Route::prefix('api')->group(function () {
        Route::get('/assignments/{assignment}/tasks', function($assignmentId) {
            $assignment = \App\Models\Assignment::with('property.rooms.tasks')->findOrFail($assignmentId);
            $tasks = $assignment->property->rooms->flatMap->tasks;
            return response()->json(['tasks' => $tasks]);
        });
    });
}); // End of auth middleware group
