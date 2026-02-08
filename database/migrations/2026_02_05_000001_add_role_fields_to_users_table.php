<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('buyer')->after('password');
            $table->string('shop_name')->nullable()->after('role');
            $table->enum('seller_status', ['pending', 'approved', 'rejected'])->default('pending')->after('shop_name');
        });

        // Backfill the new role column from legacy user_type if present
        if (Schema::hasColumn('users', 'user_type')) {
            DB::table('users')->select('id', 'user_type')->orderBy('id')->chunkById(500, function ($users) {
                foreach ($users as $user) {
                    $role = $user->user_type === 'admin' ? 'admin' : 'buyer';
                    DB::table('users')->where('id', $user->id)->update(['role' => $role]);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'shop_name', 'seller_status']);
        });
    }
};
