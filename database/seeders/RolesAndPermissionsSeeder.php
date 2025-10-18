<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // Dashboard
            'view:dashboard',
            // Users
            'create:users',
            'view:users',
            'edit:users',
            'delete:users',
            // Roles
            'create:roles',
            'view:roles',
            'edit:roles',
            'delete:roles',
            // Properties
            'create:properties',
            'view:properties',
            'edit:properties',
            'delete:properties',
            // Rooms
            'create:rooms',
            'view:rooms',
            'edit:rooms',
            'delete:rooms',
            // Tasks
            'create:tasks',
            'view:tasks',
            'edit:tasks',
            'delete:tasks',
            // Checklists
            'view:checklists',
            'edit:checklists',
            'submit:checklists',
            // Images/Photos
            'view:images',
            'upload:images',
            // Calendar
            'view:calendar',
            // Housekeepers
            'create:housekeepers',
            'view:housekeepers',
            'edit:housekeepers',
            'delete:housekeepers',
        ];

        // Create all permissions in database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define role permissions
        $roles = [
            'Admin' => [
                // Dashboard with key system stats
                'view:dashboard',
                // Manage all users, roles, properties, rooms, and tasks
                'create:users',
                'view:users',
                'edit:users',
                'delete:users',
                'create:roles',
                'view:roles',
                'edit:roles',
                'delete:roles',
                'create:properties',
                'view:properties',
                'edit:properties',
                'delete:properties',
                'create:rooms',
                'view:rooms',
                'edit:rooms',
                'delete:rooms',
                'create:tasks',
                'view:tasks',
                'edit:tasks',
                'delete:tasks',
                'create:housekeepers',
                'view:housekeepers',
                'edit:housekeepers',
                'delete:housekeepers',
                // View all checklist submissions and uploaded photos
                'view:checklists',
                'view:images',
                'view:calendar',
            ],
            'Owner' => [
                // Add/edit housekeepers
                'create:housekeepers',
                'edit:housekeepers',
                // Add/edit properties, rooms, and tasks
                'create:properties',
                'view:properties',
                'edit:properties',
                'create:rooms',
                'view:rooms',
                'edit:rooms',
                'create:tasks',
                'view:tasks',
                'edit:tasks',
                // View completed checklists, photos, and calendar of scheduled cleanings
                'view:checklists',
                'view:images',
                'view:calendar',
            ],
            'Housekeeper' => [
                // Open daily checklist
                'view:checklists',
                'edit:checklists',
                // Upload required photos and check off tasks per room
                'upload:images',
                'edit:tasks',
                // Submit checklist
                'submit:checklists',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
