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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled', 'refunded'])
                ->default('pending')
                ->after('payment_status');
            $table->string('payment_reference')->nullable()->after('status');
            $table->string('shipping_method')->nullable()->after('payment_reference');
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('shipping_method');
            $table->string('tracking_number')->nullable()->after('shipping_fee');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'payment_reference',
                'shipping_method',
                'shipping_fee',
                'tracking_number',
                'shipped_at',
                'delivered_at',
                'cancelled_at',
            ]);
        });
    }
};
