<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_admin' => true,
            'department' => 'IT'
        ]);

        // Create Staff User
        User::create([
            'first_name' => 'Staff',
            'last_name' => 'User',
            'email' => 'staff@test.com',
            'password' => Hash::make('Staff@123'),
            'role' => 'staff',
            'is_admin' => false,
            'department' => 'Operations'
        ]);
    }
} 