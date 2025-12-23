<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists (User model handles password hashing automatically)
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => 'password', // Will be auto-hashed by User model 'hashed' cast
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Update password if user already exists (to ensure it's correct)
        if (!$admin->wasRecentlyCreated && !Hash::check('password', $admin->password)) {
            $admin->password = 'password'; // Auto-hashed
            $admin->save();
        }

        // Create chauffeur user if not exists
        $driver = User::firstOrCreate([
            'email' => 'driver@example.com'
        ], [
            'name' => 'John Driver',
            'password' => 'password', // Will be auto-hashed by User model 'hashed' cast
            'role' => 'chauffeur',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Update password if user already exists
        if (!$driver->wasRecentlyCreated && !Hash::check('password', $driver->password)) {
            $driver->password = 'password'; // Auto-hashed
            $driver->save();
        }

        // Create regular user if not exists
        $user = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Regular User',
            'password' => 'password', // Will be auto-hashed by User model 'hashed' cast
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Update password if user already exists
        if (!$user->wasRecentlyCreated && !Hash::check('password', $user->password)) {
            $user->password = 'password'; // Auto-hashed
            $user->save();
        }
    }
}
