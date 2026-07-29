<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 12, 2)->nullable()->after('total');
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 12, 2)->default(0)->after('shipping_cost');
            }
            if (!Schema::hasColumn('orders', 'grand_total')) {
                $table->decimal('grand_total', 12, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('grand_total');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('Paid')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'order_status')) {
                $table->string('order_status')->nullable()->after('payment_status');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'business_id')) {
                $table->foreignId('business_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('unit_price');
            }
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('product_reviews', 'review')) {
                $table->text('review')->nullable()->after('comment');
            }
            if (!Schema::hasColumn('product_reviews', 'image')) {
                $table->string('image')->nullable()->after('image_url');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'quantity')) {
                $table->integer('quantity')->nullable()->after('qty');
            }
            if (!Schema::hasColumn('cart_items', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->nullable()->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = ['total_price', 'discount', 'grand_total', 'payment_method', 'payment_status', 'order_status'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'business_id')) {
                $table->dropForeign(['business_id']);
                $table->dropColumn('business_id');
            }
            if (Schema::hasColumn('order_items', 'price')) {
                $table->dropColumn('price');
            }
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('product_reviews', 'review')) {
                $table->dropColumn('review');
            }
            if (Schema::hasColumn('product_reviews', 'image')) {
                $table->dropColumn('image');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('cart_items', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
        });
    }
};
