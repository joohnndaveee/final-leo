<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('status', ['unread', 'read', 'replied'])->default('unread')->after('message');
            $table->text('admin_reply')->nullable()->after('status');
            $table->timestamp('read_at')->nullable()->after('admin_reply');
            $table->timestamp('replied_at')->nullable()->after('read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_reply', 'read_at', 'replied_at']);
        });
    }
};
