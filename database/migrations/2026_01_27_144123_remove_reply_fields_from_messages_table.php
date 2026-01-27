<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop reply-related columns
            $table->dropColumn(['admin_reply', 'replied_at']);
        });
        
        // Update existing 'replied' status to 'read'
        DB::table('messages')->where('status', 'replied')->update(['status' => 'read']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Restore reply-related columns
            $table->text('admin_reply')->nullable()->after('status');
            $table->timestamp('replied_at')->nullable()->after('admin_reply');
        });
    }
};
