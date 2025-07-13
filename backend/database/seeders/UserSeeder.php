<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tqrs.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'role' => 'admin'
        ]);

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@tqrs.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => false,
            'role' => 'user'
        ]);
    }
}
