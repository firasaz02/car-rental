<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a persistent test user if it doesn't exist already (User model handles password hashing automatically)
        \App\Models\User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => 'password', // Will be auto-hashed by User model 'hashed' cast
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Seed admin users
        $this->call(AdminUserSeeder::class);
        
        // Seed vehicles and chauffeurs
        $this->call(VehicleSeeder::class);
    }
}
