<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Family;
use App\Models\Passport;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory(10)->create();
        Branch::factory(10)->create();
        User::factory(10)->create();
        Client::factory(10)->create();
        Family::factory(10)->create();
        Passport::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
