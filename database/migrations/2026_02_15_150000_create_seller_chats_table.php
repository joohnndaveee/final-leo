<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->text('message');
            $table->enum('sender_type', ['seller', 'admin'])->default('seller');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index('seller_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_chats');
    }
};
