<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Property;
use App\Models\Room;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First create roles and permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@housekeeping.com'],
            [
                'name' => 'Admin User',
                'phone' => '+1234567890',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');

        // Create Property Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@housekeeping.com'],
            [
                'name' => 'Property Owner',
                'phone' => '+1234567891',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole('Owner');

        // Create Housekeeper
        $housekeeper = User::firstOrCreate(
            ['email' => 'housekeeper@housekeeping.com'],
            [
                'name' => 'Housekeeper',
                'phone' => '+1234567892',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper->assignRole('Housekeeper');
    }
}
