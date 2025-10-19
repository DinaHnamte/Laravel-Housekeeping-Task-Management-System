<?php

namespace Database\Seeders;

use App\Models\Assignment;
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

        // Create Properties
        $property1 = Property::create([
            'name' => 'Downtown Apartment',
            'beds' => 2,
            'baths' => 1,
            'user_id' => $owner->id,
        ]);

        $property2 = Property::create([
            'name' => 'Beach House',
            'beds' => 4,
            'baths' => 3,
            'user_id' => $owner->id,
        ]);

        // Create Rooms for Property 1
        $bedroom1 = Room::create([
            'property_id' => $property1->id,
            'name' => 'Master Bedroom',
            'is_default' => true,
        ]);

        $kitchen1 = Room::create([
            'property_id' => $property1->id,
            'name' => 'Kitchen',
            'is_default' => false,
        ]);

        $bathroom1 = Room::create([
            'property_id' => $property1->id,
            'name' => 'Bathroom',
            'is_default' => false,
        ]);

        // Create Rooms for Property 2
        $bedroom2 = Room::create([
            'property_id' => $property2->id,
            'name' => 'Master Bedroom',
            'is_default' => true,
        ]);

        $bedroom3 = Room::create([
            'property_id' => $property2->id,
            'name' => 'Guest Bedroom',
            'is_default' => false,
        ]);

        $kitchen2 = Room::create([
            'property_id' => $property2->id,
            'name' => 'Kitchen',
            'is_default' => false,
        ]);

        $bathroom2 = Room::create([
            'property_id' => $property2->id,
            'name' => 'Master Bathroom',
            'is_default' => false,
        ]);

        // Create Tasks for Master Bedroom
        Task::create([
            'property_id' => $property1->id,
            'room_id' => $bedroom1->id,
            'task' => 'Make the bed',
            'is_default' => true,
        ]);

        Task::create([
            'property_id' => $property1->id,
            'room_id' => $bedroom1->id,
            'task' => 'Vacuum the floor',
            'is_default' => true,
        ]);

        Task::create([
            'property_id' => $property1->id,
            'room_id' => $bedroom1->id,
            'task' => 'Dust all surfaces',
            'is_default' => true,
        ]);

        // Create Tasks for Kitchen
        Task::create([
            'property_id' => $property1->id,
            'room_id' => $kitchen1->id,
            'task' => 'Clean countertops',
            'is_default' => true,
        ]);

        Task::create([
            'property_id' => $property1->id,
            'room_id' => $kitchen1->id,
            'task' => 'Wash dishes',
            'is_default' => true,
        ]);

        Task::create([
            'property_id' => $property1->id,
            'room_id' => $kitchen1->id,
            'task' => 'Sweep and mop floor',
            'is_default' => true,
        ]);

        // Create Tasks for Bathroom
        Task::create([
            'property_id' => $property1->id,
            'room_id' => $bathroom1->id,
            'task' => 'Clean toilet',
            'is_default' => true,
        ]);

        Task::create([
            'property_id' => $property1->id,
            'room_id' => $bathroom1->id,
            'task' => 'Clean shower/bathtub',
            'is_default' => true,
        ]);

        Task::create([
            'property_id' => $property1->id,
            'room_id' => $bathroom1->id,
            'task' => 'Clean sink and mirror',
            'is_default' => true,
        ]);

        // Create Assignment
        Assignment::create([
            'property_id' => $property1->id,
            'user_id' => $housekeeper->id,
            'assignment_date' => now()->addDay(),
            'start_time' => now()->addDay()->setTime(9, 0),
            'end_time' => now()->addDay()->setTime(17, 0),
            'status' => 'pending',
            'notes' => 'Regular cleaning assignment',
        ]);
    }
}
