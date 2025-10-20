<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Family;
use App\Models\Reservation;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use App\Models\Package;
use App\Models\PackageRoom;
use App\Models\Hotel;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'super', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'visitor', 'guard_name' => 'web']);

        Tenant::factory()->withDomain()->create();
        Tenant::factory()->count(5)->create();

        User::factory(10)->create()->each(function ($user) {
            $roles = ['employee', 'manager', 'visitor'];
            $user->assignRole($roles[array_rand($roles)]);
        });
        Branch::factory(10)->create();
        //Client::factory(10)->create();
        //  // Create 5 clients without families
        Client::factory(10)->create();

        // // Create 5 clients with families
        Client::factory(5)->withFamily()->create();

        Client::factory(5)->withFamilyMembers(3)->create();

        // Client::factory(5)->withFamilyMembers(3)->create();

        User::factory()->create([
            'username' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'super'
        ])->assignRole('super');

        User::factory()->create([
            'username' => 'manager',
            'email' => 'manager@exampil.com',
            'role' => 'manager'
        ])->assignRole('manager');

        User::factory()->create([
            'username' => 'employee',
            'email' => 'employee@gmail.com',
            'role' => 'employee'
        ])->assignRole('employee');

        User::factory()->create([
            'username' => 'visitor',
            'email' => 'visitor@gmail.com',
            'role' => 'visitor'
        ])->assignRole('visitor');
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Hotel::factory()->count(5)->create();

        Package::factory()->count(5)->create();
        PackageRoom::factory()->count(5)->create();

        Reservation::factory()->count(5)->create();


    }
}
