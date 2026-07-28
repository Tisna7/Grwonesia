<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier', 50)->nullable()->after('status');
            $table->string('tracking_number', 50)->nullable()->after('courier');
            $table->string('shipping_status', 30)->default('pending')->after('tracking_number');
            $table->text('shipping_address')->nullable()->after('shipping_status');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_address');
            $table->string('estimated_arrival', 50)->nullable()->after('shipping_cost');
            $table->string('current_location', 100)->nullable()->after('estimated_arrival');
            $table->json('shipping_timeline')->nullable()->after('current_location');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier',
                'tracking_number',
                'shipping_status',
                'shipping_address',
                'shipping_cost',
                'estimated_arrival',
                'current_location',
                'shipping_timeline',
            ]);
        });
    }
};
