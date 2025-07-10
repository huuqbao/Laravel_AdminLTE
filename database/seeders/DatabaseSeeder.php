<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@khgc.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Super',
                'password' => Hash::make('Abcd@1234'),
                'address' => 'Hà Nội',
                'status' => 1,
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'address' => 'Tp.HCM',
                'status' => 1,
                'role' => 'user',
            ]
        );

        Post::factory()->count(50)->create();
    }
}
