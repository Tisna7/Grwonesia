<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['promo_copy', 'ig_caption', 'tiktok_caption', 'wa_broadcast', 'hashtags', 'poster']);
            $table->text('brief');
            $table->longText('content');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_contents');
    }
};
