<?php

namespace Database\Seeders;

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
        // Tạo tài khoản Admin theo yêu cầu
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Super',
            'email' => 'superadmin@khgc.com',
            'password' => Hash::make('Abcd@1234'),
            'address' => 'Hà Nội',
            'status' => 1,
            'role' => 'admin',
        ]);

        // Tạo user test
        User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'address' => 'Tp.HCM',
            'status' => 1,
            'role' => 'user',
        ]);
    }
}
