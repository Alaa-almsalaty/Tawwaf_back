<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Family;
use App\Models\Passport;
use App\Models\Tenant;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Tenant::factory()->count(5)->withDomain()->create();

        User::factory(10)->create();
        Branch::factory(10)->create();
        //Client::factory(10)->create();
         // Create 5 clients without families
        Client::factory(5)->create();

        // Create 5 clients with families
        Client::factory(5)->withFamily()->create();

       // Client::factory(5)->withFamilyMembers(3)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
