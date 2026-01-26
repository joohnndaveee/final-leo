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
        ]);

        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => sha1('john123'),
        ]);
    }
}
