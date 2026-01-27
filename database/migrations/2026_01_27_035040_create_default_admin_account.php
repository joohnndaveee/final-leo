<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default admin account
        // Username: admin
        // Password: admin
        DB::table('admins')->insert([
            'name' => 'admin',
            'password' => Hash::make('admin'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the default admin account
        DB::table('admins')->where('name', 'admin')->delete();
    }
};
