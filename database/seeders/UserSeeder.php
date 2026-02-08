<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user with SHA1 password
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => sha1('password123'), // SHA1 hash of 'password123'
            'role' => 'buyer',
            'seller_status' => 'pending',
        ]);

        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => sha1('john123'),
            'role' => 'buyer',
            'seller_status' => 'pending',
        ]);

        DB::table('users')->insert([
            'name' => 'Seller One',
            'email' => 'seller@example.com',
            'password' => sha1('seller123'),
            'role' => 'seller',
            'shop_name' => 'Default Seller Shop',
            'seller_status' => 'approved',
        ]);

        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => sha1('admin123'),
            'role' => 'admin',
            'seller_status' => 'approved',
        ]);
    }
}
