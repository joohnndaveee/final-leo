<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('messages', 'user_id')) {
            DB::statement('ALTER TABLE messages MODIFY user_id BIGINT UNSIGNED NULL');
        }
        if (Schema::hasColumn('messages', 'number')) {
            DB::statement('ALTER TABLE messages MODIFY number VARCHAR(20) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('messages', 'user_id')) {
            DB::statement('ALTER TABLE messages MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('messages', 'number')) {
            DB::statement('ALTER TABLE messages MODIFY number VARCHAR(20) NOT NULL');
        }
    }
};
